const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('tail -n 100 /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/storage/logs/laravel.log', (err, stream) => {
    if (err) throw err;
    let data = '';
    stream.on('data', (d) => { data += d; });
    stream.on('close', () => {
      const lines = data.split('\n');
      const errorLines = lines.filter(l => l.includes('.ERROR'));
      console.log('Found ' + errorLines.length + ' error entries in last 100 lines');
      console.log(errorLines[errorLines.length - 1]);
      // Also print the lines after the last error to see the message
      const lastErrorIdx = lines.findLastIndex(l => l.includes('.ERROR'));
      if (lastErrorIdx !== -1) {
        console.log('Full Error Details:');
        console.log(lines.slice(lastErrorIdx, lastErrorIdx + 10).join('\n'));
      }
      conn.end();
    });
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
