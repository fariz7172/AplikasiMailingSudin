const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('tail -n 1000 /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/storage/logs/laravel.log', (err, stream) => {
    if (err) throw err;
    let data = '';
    stream.on('data', d => data += d);
    stream.on('close', () => {
      const parts = data.split(/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/);
      const lastPart = parts[parts.length - 1];
      console.log('Last Error Block:');
      console.log(lastPart.substring(0, 1000)); // Print first 1000 chars of the last error
      conn.end();
    });
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
