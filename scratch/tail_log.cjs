const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('tail -c 10000 /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/storage/logs/laravel.log', (err, stream) => {
    if (err) throw err;
    let data = '';
    stream.on('data', d => data += d);
    stream.on('close', () => {
      console.log('Log Data:');
      console.log(data);
      conn.end();
    });
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
