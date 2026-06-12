const fs = require('fs');
const file = 'resources/views/payments/print.blade.php';
let content = fs.readFileSync(file, 'utf8');

// Inject CSS
content = content.replace(
    'overflow: hidden;\n            }\n        }\n        .print-area {',
    'overflow: hidden;\n                page-break-before: always;\n            }\n            .print-container > .print-area:first-of-type {\n                page-break-before: auto !important;\n            }\n        }\n        .print-area {'
);

const containerStart = content.indexOf('<div class="print-container flex flex-col items-center">');
let headerPart = content.substring(0, containerStart);
let bodyPart = content.substring(containerStart);

// Remove all existing @if and @endif in bodyPart
bodyPart = bodyPart.replace(/@if\(\$type === [^\n]+\n/g, '');
bodyPart = bodyPart.replace(/@endif\n/g, '');

// Clean up page-break ternary inside div classes
bodyPart = bodyPart.replace(/<div class="print-area type-[\w_]+ (page-break )?font-serif( {{[^}]+}})?">/g, '<div class="print-area font-serif">');
bodyPart = bodyPart.replace(/<div class="print-area type-[\w_]+ font-serif( {{[^}]+}})?">/g, '<div class="print-area font-serif">');
bodyPart = bodyPart.replace(/<div class="print-area font-serif {{[^}]+}}">/g, '<div class="print-area font-serif">');
bodyPart = bodyPart.replace(/<div class="print-area type-sptjm_gu page-break font-serif">/g, '<div class="print-area font-serif">');
bodyPart = bodyPart.replace(/<div class="print-area type-sptjm_ls page-break font-serif">/g, '<div class="print-area font-serif">');
bodyPart = bodyPart.replace(/<div class="print-area type-[\w_]+ page-break font-serif">/g, '<div class="print-area font-serif">');


const pageLogic = {
    1: `@if($type === 'all' || $type === 'spp' || $type === 'sptjm_ls')`,
    2: `@if($type === 'all' || $type === 'spp')`,
    3: `@if($type === 'all' || $type === 'spp')`,
    4: `@if($type === 'all' || $type === 'spp')`,
    5: `@if($type === 'all' || $type === 'spm' || $type === 'sptjm_ls')`,
    6: `@if($type === 'all' || $type === 'spm')`,
    7: `@if($type === 'all' || $type === 'spm')`,
    8: `@if($type === 'all' || $type === 'spm')`,
    9: `@if($type === 'all' || $type === 'kontrak' || $type === 'sptjm_ls')`,
    10: `@if($type === 'all' || $type === 'kontrak')`,
    11: `@if($type === 'all' || $type === 'kontrak')`,
    12: `@if($type === 'all' || $type === 'sptjm_gu')`,
    13: `@if($type === 'all' || $type === 'sptjm_gu')`,
    14: `@if($type === 'all' || $type === 'sptjm_gu')`,
    15: `@if($type === 'all' || $type === 'sptjm_ls')`,
    16: `@if($type === 'all')`,
    17: `@if($type === 'all' || $type === 'sptjm_ls')`,
    18: `@if($type === 'all' || $type === 'sptjm_gu')`,
};

const chunks = bodyPart.split('<!-- PAGE ');

let finalBody = chunks[0];

for (let i = 1; i < chunks.length; i++) {
    let chunk = chunks[i];
    let match = chunk.match(/^(\d+):/);
    if (!match) {
        finalBody += '<!-- PAGE ' + chunk;
        continue;
    }
    let pageNum = parseInt(match[1]);
    let condition = pageLogic[pageNum] || `@if($type === 'all')`;
    
    if (i === chunks.length - 1) {
        // Last chunk has closing tags for the container
        let parts = chunk.split('</div>\n\n    </div>');
        if (parts.length >= 2) {
            // Trim the page content
            let trimmed = parts[0].replace(/\s+$/, '');
            let trailing = parts[0].substring(trimmed.length);
            finalBody += `        ${condition}\n        <!-- PAGE ${trimmed}\n        @endif\n${trailing}</div>\n\n    </div>` + parts.slice(1).join('</div>\n\n    </div>');
            continue;
        }
    }
    
    let trimmed = chunk.replace(/\s+$/, '');
    let trailing = chunk.substring(trimmed.length);
    
    finalBody += `        ${condition}\n        <!-- PAGE ${trimmed}\n        @endif\n${trailing}`;
}

fs.writeFileSync(file, headerPart + finalBody);
console.log('done');
