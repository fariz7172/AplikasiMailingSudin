const fs = require('fs');
const path = require('path');

const files = [
    'resources/views/payments/create.blade.php',
    'resources/views/payments/edit.blade.php'
];

files.forEach(file => {
    let p = path.join(__dirname, '..', file);
    if (!fs.existsSync(p)) return;
    let content = fs.readFileSync(p, 'utf-8');

    // Find x-data="{
    const startToken = 'x-data="{';
    const endToken = '}" class="space-y-6 pb-20">';

    let startIndex = content.indexOf(startToken);
    if (startIndex !== -1) {
        let endIndex = content.indexOf(endToken, startIndex);
        if (endIndex !== -1) {
            // Extract the whole body inside {...}
            let objectBody = content.substring(startIndex + 'x-data="{'.length - 1, endIndex + 1); // Extract including { and }
            
            // Replace the attribute
            let before = content.substring(0, startIndex);
            let after = content.substring(endIndex + 2); // skip }"

            let newHtml = `x-data="paymentForm()" class="space-y-6 pb-20">`;
            
            // Put script at the end before @endsection
            let scriptHtml = `
@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('paymentForm', () => (${objectBody}));
});
</script>
@endpush
`;
            
            // Let's just put it at the end of the file, before @endsection
            content = before + newHtml + after;
            
            let endsectionIndex = content.lastIndexOf('@endsection');
            if(endsectionIndex !== -1) {
                content = content.substring(0, endsectionIndex) + scriptHtml + '\n' + content.substring(endsectionIndex);
            } else {
                content += scriptHtml;
            }

            fs.writeFileSync(p, content, 'utf-8');
            console.log('Fixed Alpine in ' + file);
        }
    }
});
