const fs = require('fs');
const file = 'resources/views/payments/print.blade.php';
let content = fs.readFileSync(file, 'utf8');

// First, clean up the existing broad @if blocks and any trailing @endif
const blocksToRemove = [
    /@if\(\$type === 'all' \|\| \$type === 'spp'\)\s*<!-- PAGE 1:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'spp'\)\s*<!-- PAGE 2:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'spm'\)\s*<!-- PAGE 5:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'spm'\)\s*<!-- PAGE 6:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'kontrak'\)\s*<!-- PAGE 9:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'kontrak'\)\s*<!-- PAGE 10:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'sptjm_gu'\)\s*<!-- PAGE 12:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'sptjm_ls'\)\s*<!-- PAGE 15:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all'\)\s*<!-- PAGE 16:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'sptjm_ls'\)\s*<!-- PAGE 17:/g,
    /<\/div>\s*@endif\s*@if\(\$type === 'all' \|\| \$type === 'sptjm_gu'\)\s*<!-- PAGE 18:/g,
];

// Instead of complex regex, let's just use string replacement line by line to strip ALL `@if` and `@endif` outside the UI header!
// We know the print container starts at: <div class="print-container flex flex-col items-center">
const containerStart = content.indexOf('<div class="print-container flex flex-col items-center">');
if (containerStart === -1) {
    console.error("Could not find print-container");
    process.exit(1);
}

const headerPart = content.substring(0, containerStart);
let bodyPart = content.substring(containerStart);

// Remove all @if and @endif from the bodyPart
bodyPart = bodyPart.replace(/@if\(\$type === [^\n]+\n/g, '');
bodyPart = bodyPart.replace(/@endif\n/g, '');

// Clean up all `page-break` and ternary operators from `<div class="print-area ...">`
bodyPart = bodyPart.replace(/<div class="print-area type-[\w_]+ (page-break )?font-serif( {{[^}]+}})?">/g, (match) => {
    // Keep it simple, just make it <div class="print-area font-serif"> (Wait, some might need `type-` for some reason? No, we removed type- CSS anyway, but let's keep it).
    // Actually let's just strip the extra classes and leave `print-area font-serif`
    return `<div class="print-area font-serif">`;
});

// Also remove `page-break` class if any remains
bodyPart = bodyPart.replace(/<div class="print-area type-[\w_]+ font-serif page-break">/g, '<div class="print-area font-serif">');

// Now re-insert the specific @if around each PAGE
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

// We will split the bodyPart by `<!-- PAGE `
const chunks = bodyPart.split('<!-- PAGE ');
let newBodyPart = chunks[0]; // The `<div class="print-container...">\n\n` part

for (let i = 1; i < chunks.length; i++) {
    const chunk = chunks[i];
    // Find the page number
    const match = chunk.match(/^(\d+):/);
    if (!match) {
        newBodyPart += '<!-- PAGE ' + chunk;
        continue;
    }
    const pageNum = parseInt(match[1]);
    
    // Find where the print-area div ends. The last </div> before the end of the chunk (or next page)
    // Wait, since we are splitting by `<!-- PAGE `, the chunk contains exactly one page and ends where the next begins!
    // But it might have some trailing whitespace.
    // The very last chunk ends with `</div>\n\n    </div>\n\n<script>`
    
    let condition = pageLogic[pageNum];
    if (!condition) condition = `@if($type === 'all')`;
    
    // For the last chunk, we need to insert `@endif` before the closing `</div>` of the container.
    // Actually, we can just append `@endif` at the very end of the page content.
    
    if (i === chunks.length - 1) {
        // Find the last `</div>` which closes the `.print-area`
        // Then append `@endif` after it
        // The last chunk looks like: `... </div>\n\n    </div>\n\n<script>...`
        // We find the FIRST </div> that closes the print-area. Wait, a print-area might have many </div>.
        // It's safer to just inject `@if` before `<!-- PAGE ` and `@endif` before the next `<!-- PAGE `!
        // But since we are splitting, we can prepend `condition + '\n        <!-- PAGE '` and append `@endif` at the end of the page!
    }
}

// A safer approach:
// Use regex to wrap each page!
let safeBodyPart = bodyPart;
for (let i = 18; i >= 1; i--) {
    const condition = pageLogic[i] || `@if($type === 'all')`;
    // We look for `<!-- PAGE i: ` until either `<!-- PAGE i+1: ` or `<script>` or the end of the container `</div>\n\n    </div>`.
    // Let's just find `<!-- PAGE i:` and the next `<!-- PAGE` or `<script>`
    const regex = new RegExp(`(<!-- PAGE ${i}:[\\s\\S]*?)(?=<!-- PAGE |</div>\\s*</div>\\s*<script>)`, 'g');
    safeBodyPart = safeBodyPart.replace(regex, (match) => {
        // Strip any trailing whitespace
        let trimmed = match.replace(/\s+$/, '');
        let trailingWhitespace = match.substring(trimmed.length);
        return `        ${condition}\n        ${trimmed}\n        @endif\n${trailingWhitespace}`;
    });
}

const finalContent = headerPart + safeBodyPart;

fs.writeFileSync(file, finalContent);
console.log('done');
