const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'resources', 'views', 'payments', 'print.blade.php');
let content = fs.readFileSync(filePath, 'utf8');

// Replace number_format calls to handle nulls
content = content.replace(/number_format\(\$payment->contract\?->nilai_kontrak, 2, ',', '\.'\)/g, "number_format($payment->contract?->nilai_kontrak ?? 0, 2, ',', '.')");

// Also check for terbilangTeks which might be unescaped or error prone if null
// Oh wait, terbilangTeks is '{{ $payment->contract?->terbilang_kontrak ?? '' }}', which is fine.

fs.writeFileSync(filePath, content, 'utf8');
console.log('Number format replacements done.');
