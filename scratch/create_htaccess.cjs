const { Client } = require('ssh2');

const conn = new Client();
const htaccess = `<IfModule mod_rewrite.c>
   RewriteEngine On
   RewriteRule ^(.*)$ public/$1 [L]
</IfModule>`;

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.exec(`echo "${htaccess}" > /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/.htaccess`, (err, stream) => {
    if (err) throw err;
    stream.on('close', (code) => {
      console.log('HTACCESS created');
      conn.end();
    });
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
