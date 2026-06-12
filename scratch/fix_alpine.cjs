const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'resources', 'views', 'payments', 'print.blade.php');
let content = fs.readFileSync(filePath, 'utf-8');

// Find the start of x-data="{
const startStr = 'x-data="{';
const startIndex = content.indexOf(startStr);
if (startIndex === -1) {
    console.log("Could not find x-data='{");
    process.exit(1);
}

// Find the matching end }">
// We need to count braces to be safe, but since we know it ends right before <!-- UI Overlay (No Print) -->
const endSearch = '}">\n\n    <!-- UI Overlay (No Print) -->';
const endIndex = content.indexOf(endSearch);

if (endIndex === -1) {
    console.log("Could not find }\"&gt;");
    process.exit(1);
}

// Extract the object body (everything between x-data="{ and }">)
// Note: x-data="{ includes the opening brace, so we extract from startIndex + startStr.length - 1
const objectBody = content.substring(startIndex + startStr.length - 1, endIndex + 1);

// Replace the x-data attribute
const beforeBody = content.substring(0, startIndex);
const afterBody = content.substring(endIndex + 2); // skip }">

const newBodyStart = `x-data="printComponent()">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('printComponent', () => (${objectBody}));
        });
    </script>`;

content = beforeBody + newBodyStart + afterBody;

fs.writeFileSync(filePath, content, 'utf-8');
console.log('Alpine component extracted successfully.');
