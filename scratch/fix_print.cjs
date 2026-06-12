const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'resources', 'views', 'payments', 'print.blade.php');
let content = fs.readFileSync(filePath, 'utf-8');

// 1. Add data-eid to all static contenteditable elements
let eidCounter = 1;
content = content.replace(/contenteditable="true"/g, (match, offset, str) => {
    // Check if it's inside a template x-for. If so, do not add data-eid.
    // A heuristic: if the surrounding tag has x-text="item.something", we skip it.
    // We can look ahead a bit to see if there is x-text="item.
    let nextText = str.substring(offset, offset + 100);
    if (nextText.includes('x-text="item.')) {
        return match;
    }
    return `contenteditable="true" data-eid="${eidCounter++}"`;
});

// 2. Add @input to dynamic checklist items (item.label, item.note, item.syarat, item.jenis)
content = content.replace(/x-text="item\.label"/g, 'x-text="item.label" @input="item.label = $el.innerText"');
content = content.replace(/x-text="item\.note"/g, 'x-text="item.note" @input="item.note = $el.innerText"');
content = content.replace(/x-text="item\.syarat"/g, 'x-text="item.syarat" @input="item.syarat = $el.innerText"');
content = content.replace(/x-text="item\.jenis"/g, 'x-text="item.jenis" @input="item.jenis = $el.innerText"');

// 3. Inject Alpine.js x-data logic and saveData method
const alpineDataStart = content.indexOf('x-data="{');
if (alpineDataStart !== -1) {
    // Replace the opening of x-data
    // We will inject savedData variable
    let newAlpineStart = `x-data="{
    savedContentData: @json($payment->print_data['savedContentData'] ?? new stdClass()),
    isSaving: false,
    async saveData() {
        this.isSaving = true;
        try {
            let response = await fetch('{{ route('payments.save-print', $payment->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    print_data: {
                        savedContentData: this.savedContentData,
                        checklist: this.checklist,
                        checklistGaji: this.checklistGaji,
                        checklistUP: this.checklistUP,
                        checklistGU: this.checklistGU,
                        checklistSPM: this.checklistSPM,
                        checklistSPM2: this.checklistSPM2,
                        checklistSPM3: this.checklistSPM3,
                        checklistSPM4: this.checklistSPM4,
                    }
                })
            });
            let result = await response.json();
            if(result.success) {
                alert('Data cetak berhasil disimpan permanen!');
            }
        } catch(e) {
            alert('Gagal menyimpan data.');
        } finally {
            this.isSaving = false;
        }
    },`;

    content = content.replace('x-data="{', newAlpineStart);

    // Modify init()
    // We need to inject the logic to populate data-eid elements
    const initFunc = `init() {
        this.updateTerbilang();`;
    const newInitFunc = `init() {
        this.updateTerbilang();
        // Populate static editables
        setTimeout(() => {
            document.querySelectorAll('[data-eid]').forEach(el => {
                let eid = el.getAttribute('data-eid');
                if (this.savedContentData[eid] !== undefined) {
                    el.innerText = this.savedContentData[eid];
                }
                el.addEventListener('input', () => {
                    this.savedContentData[eid] = el.innerText;
                });
            });
        }, 100);`;
    
    content = content.replace(initFunc, newInitFunc);

    // Now for the checklists, we need to initialize them from $payment->print_data if available.
    // E.g., checklist: [ -> checklist: @json($payment->print_data['checklist'] ?? null) || [
    const checklists = ['checklist', 'checklistGaji', 'checklistUP', 'checklistGU', 'checklistSPM', 'checklistSPM2', 'checklistSPM3', 'checklistSPM4'];
    checklists.forEach(list => {
        content = content.replace(new RegExp(`${list}: \\[`, 'g'), `${list}: @json($payment->print_data['${list}'] ?? null) || [`);
    });
}

// 4. Add the Save button to the UI header
const headerButtons = `<button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/30">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Dokumen
            </button>`;
const newHeaderButtons = `<button @click="saveData()" :disabled="isSaving" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-600/30 mr-2">
                <i data-lucide="save" class="w-4 h-4"></i> <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
            </button>
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/30">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Dokumen
            </button>`;
content = content.replace(headerButtons, newHeaderButtons);

fs.writeFileSync(filePath, content, 'utf-8');
console.log('Modified print.blade.php successfully.');
