const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'resources', 'views', 'payments', 'print.blade.php');
let content = fs.readFileSync(filePath, 'utf8');

// Replace standard object access with null-safe access
content = content.replace(/\$payment->pptk->/g, '$payment->pptk?->');
content = content.replace(/\$payment->vendor->/g, '$payment->vendor?->');
content = content.replace(/\$payment->contract->/g, '$payment->contract?->');

fs.writeFileSync(filePath, content, 'utf8');
console.log('Replacements done.');
