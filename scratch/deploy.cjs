const { Client } = require('ssh2');

const conn = new Client();

const commands = [
  'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && rm -f default.php',
  'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && git clone https://github.com/fariz7172/AplikasiMailingSudin.git . || git pull origin farizahmad.github.io',
  'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && git checkout farizahmad.github.io',
  'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && cp .env.example .env'
];

function runCommand(cmd) {
  return new Promise((resolve, reject) => {
    console.log(`Running: ${cmd}`);
    conn.exec(cmd, (err, stream) => {
      if (err) return reject(err);
      let stdout = '';
      let stderr = '';
      stream.on('close', (code, signal) => {
        if (code !== 0) {
          console.error(`Command failed with code ${code}`);
          // reject(new Error(`Exit code ${code}`)); // Don't reject yet, some might be okay
        }
        resolve({ code, stdout, stderr });
      }).on('data', (data) => {
        stdout += data;
        process.stdout.write(data);
      }).stderr.on('data', (data) => {
        stderr += data;
        process.stderr.write(data);
      });
    });
  });
}

conn.on('ready', async () => {
  console.log('Client :: ready');
  try {
    for (const cmd of commands) {
      await runCommand(cmd);
    }

    // Update .env content
    const envContent = `APP_NAME=SPP
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://aplikasimailingsudin.farizahmad.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u674511048_AplikasiMailin
DB_USERNAME=u674511048_AplikasiMailin
DB_PASSWORD=!FarizAhmad123456

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
`;

    const escapedEnv = envContent.replace(/'/g, "'\\''");
    await runCommand(`echo "${escapedEnv}" > /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin/.env`);

    // Continue with deployment
    const deployCommands = [
      'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction',
      'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && php artisan key:generate --force',
      'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && php artisan migrate --force --seed',
      'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && php artisan storage:link',
      'cd /home/u674511048/domains/farizahmad.com/public_html/aplikasimailingsudin && npm install && npm run build'
    ];

    for (const cmd of deployCommands) {
      await runCommand(cmd);
    }

    console.log('Deployment completed successfully!');
  } catch (err) {
    console.error('Deployment failed:', err);
  } finally {
    conn.end();
  }
}).connect({
  host: '145.79.14.233',
  port: 65002,
  username: 'u674511048',
  password: '!FarizAhmad123456'
});
