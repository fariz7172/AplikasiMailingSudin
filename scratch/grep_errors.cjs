const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('grep "production.ERROR" /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/storage/logs/laravel.log | tail -n 5', (err, stream) => {
    if (err) throw err;
    stream.on('data', d => console.log(''+d));
    stream.on('close', () => conn.end());
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
