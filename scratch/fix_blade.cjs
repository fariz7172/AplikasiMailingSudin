const fs = require('fs');
const file = 'resources/views/payments/print.blade.php';
let content = fs.readFileSync(file, 'utf8');

// First, remove any existing @if($type === 'all' || ...) logic that might have been injected
content = content.replace(/@if\(\$type === 'all' \|\| \$type === '[^']+'\)\s*<!--/g, '<!--');
// Remove dangling @endifs that come after </div>
content = content.replace(/<\/div>\s*@endif/g, '</div>');

// Remove the `type-xxx` classes to avoid confusion, they are no longer needed for CSS display: none.
// Actually keep them just in case.

// Now properly insert them.
// PAGE 1 starts the SPP section
content = content.replace(/<!-- PAGE 1: CHECKLIST SPP -->/, `@if($type === 'all' || $type === 'spp')\n        <!-- PAGE 1: CHECKLIST SPP -->`);
// PAGE 5 starts the SPM section, end SPP before it
content = content.replace(/<!-- PAGE 5: CHECKLIST SPM -->/, `@endif\n\n        @if($type === 'all' || $type === 'spm')\n        <!-- PAGE 5: CHECKLIST SPM -->`);
// PAGE 9 starts Kontrak section
content = content.replace(/<!-- PAGE 9: RINGKASAN KONTRAK -->/, `@endif\n\n        @if($type === 'all' || $type === 'kontrak')\n        <!-- PAGE 9: RINGKASAN KONTRAK -->`);
// PAGE 12 starts SPTJM GU
content = content.replace(/<!-- PAGE 12: SPTJM -->/, `@endif\n\n        @if($type === 'all' || $type === 'sptjm_gu')\n        <!-- PAGE 12: SPTJM -->`);
// PAGE 15 starts SPTJM LS
content = content.replace(/<!-- PAGE 15: SPTJM UP\/LS -->/, `@endif\n\n        @if($type === 'all' || $type === 'sptjm_ls')\n        <!-- PAGE 15: SPTJM UP/LS -->`);
// PAGE 18 starts SPTJM GU again (wait, user put PAGE 18 as SPTJM GU).
content = content.replace(/<!-- PAGE 18: VERIFIKASI PPK \(SPP LS\) -->/, `@endif\n\n        @if($type === 'all' || $type === 'sptjm_gu')\n        <!-- PAGE 18: VERIFIKASI PPK (SPP LS) -->`);

// Finally add the closing @endif at the end of the container
// The end is before `    </div>\n</body>`
content = content.replace(/(<\/div>\s*<\/div>\s*)(<\/body>)/, '$1@endif\n$2');
// Wait, the main container is <div class="print-container flex flex-col items-center">
// It ends right before </body> or </html>.
// Let's just find the last </div> before </body>
const lastDivIndex = content.lastIndexOf('</div>');
content = content.slice(0, lastDivIndex) + '@endif\n    ' + content.slice(lastDivIndex);

// Also we need to fix the `page-break` on the first page of each section.
content = content.replace(/<div class="print-area type-spp font-serif">/, `<div class="print-area type-spp font-serif {{ $type === 'all' ? '' : '' }}">`);
content = content.replace(/<div class="print-area type-spm page-break font-serif">/, `<div class="print-area type-spm font-serif {{ $type === 'spm' ? '' : 'page-break' }}">`);
content = content.replace(/<div class="print-area type-kontrak page-break font-serif">/, `<div class="print-area type-kontrak font-serif {{ $type === 'kontrak' ? '' : 'page-break' }}">`);
content = content.replace(/<div class="print-area type-sptjm_gu page-break font-serif">/, `<div class="print-area type-sptjm_gu font-serif {{ $type === 'sptjm_gu' ? '' : 'page-break' }}">`);
content = content.replace(/<div class="print-area type-sptjm_ls page-break font-serif">/, `<div class="print-area type-sptjm_ls font-serif {{ $type === 'sptjm_ls' ? '' : 'page-break' }}">`);

fs.writeFileSync(file, content);
console.log('done');
