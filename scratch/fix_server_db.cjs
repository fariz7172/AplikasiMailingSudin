const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec('cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && php artisan tinker --execute="use Illuminate\\\\Database\\\\Schema\\\\Blueprint; Schema::table(\'payments\', function(Blueprint \\$table) { \\$table->dropForeign([\'perusahaan_id\']); \\$table->dropColumn(\'perusahaan_id\'); }); Schema::dropIfExists(\'perusahaans\'); DB::table(\'migrations\')->where(\'migration\', \'like\', \'%perusahaan%\')->delete();"', (err, stream) => {
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
