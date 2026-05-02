const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();
const htaccess = `RewriteEngine on
RewriteCond %{REQUEST_URI} !^/public
RewriteRule ^(.*)$ public/$1 [L]`;

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    const remotePath = '/home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/.htaccess';
    const stream = sftp.createWriteStream(remotePath);
    stream.on('close', () => {
      console.log('.htaccess updated');
      conn.end();
    });
    stream.write(htaccess);
    stream.end();
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
