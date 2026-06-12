const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && git pull && php artisan migrate --force && php artisan optimize:clear', (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      console.log('Stream :: close :: code: ' + code + ', signal: ' + signal);
      conn.end();
    }).on('data', (data) => {
      console.log('STDOUT: ' + data);
    }).stderr.on('data', (data) => {
      console.log('STDERR: ' + data);
    });
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
