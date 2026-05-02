const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('mysql -u u674511048_AplikasiMailin -p\'!FarizAhmad123456\' u674511048_AplikasiMailin -e "SELECT id, pptk_id, vendor_id, contract_id FROM payments WHERE id=1"', (err, stream) => {
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
