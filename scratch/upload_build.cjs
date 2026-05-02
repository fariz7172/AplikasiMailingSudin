const { Client } = require('ssh2');
const fs = require('fs');
const path = require('path');

const conn = new Client();

const localBuildPath = path.join(__dirname, '..', 'public', 'build');
const remoteBuildPath = '/home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/public/build';

function uploadDir(sftp, localPath, remotePath) {
  return new Promise((resolve, reject) => {
    sftp.mkdir(remotePath, (err) => {
      // Ignore if directory already exists
      fs.readdir(localPath, async (err, files) => {
        if (err) return reject(err);
        for (const file of files) {
          const lPath = path.join(localPath, file);
          const rPath = path.join(remotePath, file).replace(/\\/g, '/');
          const stats = fs.statSync(lPath);
          if (stats.isDirectory()) {
            await uploadDir(sftp, lPath, rPath);
          } else {
            await new Promise((res, rej) => {
              console.log(`Uploading ${file}...`);
              sftp.fastPut(lPath, rPath, (err) => {
                if (err) rej(err);
                else res();
              });
            });
          }
        }
        resolve();
      });
    });
  });
}

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp(async (err, sftp) => {
    if (err) throw err;
    try {
      await uploadDir(sftp, localBuildPath, remoteBuildPath);
      console.log('Build folder uploaded successfully!');
    } catch (err) {
      console.error('Upload failed:', err);
    } finally {
      conn.end();
    }
  });
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
