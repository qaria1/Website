🎯 Complete Picture — Production App Identified & Full Analysis
I now have everything I need. The picture is clear. Let me give you the definitive analysis and the exact migration plan.

✅ Production App — Confirmed
Your live production application is /var/www/html/martreza
Here is how we know with certainty:
EvidenceDetailApache DocumentRootPoints to /var/www/html/martrezaSSL CertificateIssued for shopmartreza.com → serves this folderSupervisor queue workerRuns against /var/www/html/martreza/artisanAPP_URLhttps://shopmartreza.comProduction databaseshopmartreza (9.70MB — largest DB)

🗺️ What All Four Apps Actually Are
FolderWhat It IsStatus/var/www/html/martreza✅ LIVE PRODUCTIONMigrate this/var/www/html/shopmartreza🔁 Older duplicate installDelete after migration/var/www/html/shopmartrezaold🗄️ Archive backup copyDelete after migration/var/www/html/Martreza-Code-Backend🧪 Freelancer dev copy (APP_DEBUG=1, localhost URL)Delete after migration
You have been running one live app and three dead copies consuming disk space and creating confusion. After migration, only the production app goes to the new server. The rest get deleted.

🚨 New Critical Findings From The .env Files
🔴 Finding 1: Laravel Scheduler Has NEVER Run
There is no php artisan schedule:run cron anywhere on this server. Not in root crontab, not in www-data crontab, not in /etc/cron.d/. Your three critical business jobs have never executed:

subscriptions:check-expiry — vendor subscriptions have never been checked
products:archive — product archiving has never run
subscriptions:assign-free-plan — free plan assignment has never fired

This must be fixed immediately, even before migration.
bash# Run this right now on the current server
crontab -u www-data -e

# Add this single line:
* * * * * cd /var/www/html/martreza && php artisan schedule:run >> /dev/null 2>&1
🟡 Finding 2: Queue Uses Database Driver, Not Sync
The production app has QUEUE_CONNECTION=database — this is better than sync. Jobs are queued in the MySQL jobs table. The Supervisor worker is processing them correctly. This is functional but will be slower than Redis under load. We will switch to Redis on the new server.
🔴 Finding 3: Transactional Email is a Personal Gmail Account
MAIL_USERNAME=mikyasabadirz@gmail.com
This is a personal Gmail account with an app password being used for all platform emails — order confirmations, password resets, vendor notifications. Gmail has a hard limit of 500 emails per day. At launch with real users, you will hit this limit quickly, emails will start failing silently, and users will not receive order confirmations. This must be replaced with a proper transactional email service before launch.
The fix is straightforward and either free or near-free at your scale. I'll give you the exact configuration below.
🔴 Finding 4: PhpMyAdmin Is Publicly Accessible
Your Apache config exposes PhpMyAdmin at two obscure but public URLs. Anyone who discovers these URLs has a login screen directly to your entire database. This is a significant security risk.
/martvdssreza34324sdfsdjhjsa   ← phpmyadmin
/dgjgdgd3453as2380656          ← phpmyadmin (duplicate)
Security through obscurity is not security. We will remove public access to PhpMyAdmin on the new server.
🟡 Finding 5: Root Database User in Production
DB_USERNAME=root
The application connects to MariaDB as root. If someone exploits a SQL injection vulnerability, they have full database server access — not just access to your application's database. We will create a dedicated database user on the new server.
🟢 Finding 6: APP_DEBUG is Correctly Off
APP_DEBUG=0
Good — this was set correctly by whoever last configured the production environment.
🟡 Finding 7: GEEZ_SMS_KEY Present
There is an SMS integration key for what appears to be an Ethiopian SMS service. This is a production credential. We need to carry this to the new server.

📦 What We Are Migrating — Exact Inventory
ItemSizeLocationApplication code~500MB/var/www/html/martreza (excluding storage)Uploaded files / storage2.3 GB/var/www/html/martreza/storageDatabase9.70 MBshopmartreza databaseSSL Certificate—Reissue fresh on new serverEnvironment config—.env file (carry over + update)
Total transfer: approximately 2.8GB. At a typical VPS-to-VPS transfer speed, this will take 10–20 minutes.

🗓️ Complete Migration Plan — Day by Day

TODAY — Pre-Migration Fixes (Do These Before Anything Else)
Fix 1: Add the missing cron job on the current server
bashcrontab -u www-data -e
# Add this line:
* * * * * cd /var/www/html/martreza && php artisan schedule:run >> /dev/null 2>&1
Fix 2: Take a complete backup right now
bash# Database backup
mysqldump -u root -p shopmartreza > /root/shopmartreza_backup_$(date +%Y%m%d_%H%M).sql
gzip /root/shopmartreza_backup_$(date +%Y%m%d_%H%M).sql

# Verify it was created
ls -lh /root/*.gz
Download this file to your local machine using FileZilla (free SFTP client). Connect with your SSH credentials, navigate to /root/, and download the .gz file.
Fix 3: Set up a free transactional email service
Sign up for Brevo (formerly Sendinblue) — free tier includes 300 emails/day, no credit card required. After signup, go to SMTP & API settings and get your SMTP credentials. Update the production .env:
bashnano /var/www/html/martreza/.env
Replace the Gmail SMTP block with:
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_login_email
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@shopmartreza.com
MAIL_FROM_NAME="Martreza"
Then clear config cache:
bashcd /var/www/html/martreza
php artisan config:clear
php artisan cache:clear

DAY 1–2 — Provision the New DigitalOcean Server
Step 1: Create your DigitalOcean account
Go to digitalocean.com. Check for the $200 new account credit — it is often available via do.co/github-dev or similar promo links.
Step 2: Create the Droplet

OS: Ubuntu 22.04 LTS (matches current server exactly)
Plan: Regular, 2 vCPU / 4GB RAM / 80GB SSD — approximately $24/month
Region: Frankfurt or Amsterdam (closest to Ethiopia with good latency)
Authentication: SSH Key (add your public key)
Enable: Backups (weekly, adds ~$5/month)
Hostname: martreza-prod

Step 3: Create a DigitalOcean Space (object storage)
In the DigitalOcean dashboard, go to Spaces → Create Space.

Region: Same as your Droplet (Frankfurt)
Name: martreza-storage
Enable CDN: Yes

This replaces local file storage. All uploaded product images, vendor files, etc. will move here.

DAY 2–3 — Configure the New Server
SSH into your new DigitalOcean server as root and run these commands in order. These are production-ready and specific to your stack.
Install all required software:
bash# Update system
apt update && apt upgrade -y

# Install Apache, PHP 8.2, and all required extensions
apt install -y apache2 php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
  php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-redis \
  libapache2-mod-fcgid

# Install MariaDB
apt install -y mariadb-server

# Install Redis
apt install -y redis-server

# Install Supervisor
apt install -y supervisor

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Certbot for SSL
apt install -y certbot python3-certbot-apache

# Install Git
apt install -y git

# Enable Apache modules
a2enmod rewrite proxy_fcgi setenvif deflate expires headers ssl
a2enconf php8.2-fpm
systemctl restart apache2
Secure MariaDB:
bashmysql_secure_installation
# Answer: set root password, remove anonymous users, 
# disable remote root login, remove test database — all Yes
Create dedicated database user and database:
bashmysql -u root -p
sqlCREATE DATABASE shopmartreza CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'martreza_user'@'localhost' IDENTIFIED BY 'CHOOSE_A_STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON shopmartreza.* TO 'martreza_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
Configure Redis:
bash# Set Redis to use a password and persist data
nano /etc/redis/redis.conf

# Find and change these lines:
# requirepass yourredispassword
# maxmemory 512mb
# maxmemory-policy allkeys-lru

systemctl restart redis-server
redis-cli -a yourredispassword ping
# Should return: PONG
Set up firewall:
bashufw allow OpenSSH
ufw allow 'Apache Full'
ufw enable
ufw status

DAY 3–4 — Transfer the Application
Step 1: Create a GitHub private repository
On GitHub, create a new private repository called martreza-platform.
On the current server:
bashcd /var/www/html/martreza

# Initialize git (ignoring storage, vendor, and .env)
git init
git add .
git commit -m "Initial production snapshot - pre-migration"

# Connect to GitHub and push
git remote add origin git@github.com:YOUR_USERNAME/martreza-platform.git
git branch -M main
git push -u origin main
Step 2: Deploy code on new server
bash# On new DigitalOcean server
cd /var/www/html

# Clone the repository
git clone git@github.com:YOUR_USERNAME/martreza-platform.git martreza
cd martreza

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Set correct permissions
chown -R www-data:www-data /var/www/html/martreza
chmod -R 755 /var/www/html/martreza
chmod -R 775 /var/www/html/martreza/storage
chmod -R 775 /var/www/html/martreza/bootstrap/cache
Step 3: Configure the .env on new server
bashcp /var/www/html/martreza/.env.example /var/www/html/martreza/.env
nano /var/www/html/martreza/.env
Use the production .env from the old server as your base, but update these values:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://shopmartreza.com

DB_HOST=localhost
DB_DATABASE=shopmartreza
DB_USERNAME=martreza_user
DB_PASSWORD=YOUR_NEW_DB_PASSWORD

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=yourredispassword
REDIS_PORT=6379

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_spaces_key
AWS_SECRET_ACCESS_KEY=your_spaces_secret
AWS_DEFAULT_REGION=fra1
AWS_BUCKET=martreza-storage
AWS_ENDPOINT=https://fra1.digitaloceanspaces.com

# Keep your existing keys:
APP_KEY=base64:x7Xg4thz3WXYq2s/ys5RTzbpbn/CvKMJjDjk/JFd6kE=
GEEZ_SMS_KEY=V2WU7JFfjyVdeu4vfH2wRC9hGbhqT3Ha
# Plus Brevo SMTP settings from earlier
Step 4: Migrate the database
On the old server:
bashmysqldump -u root -p shopmartreza > /root/shopmartreza_final.sql
# Transfer to new server:
scp /root/shopmartreza_final.sql root@NEW_SERVER_IP:/root/
On the new server:
bashmysql -u root -p shopmartreza < /root/shopmartreza_final.sql
# Verify
mysql -u martreza_user -p -e "USE shopmartreza; SELECT COUNT(*) FROM users;"
Step 5: Transfer uploaded files (storage folder)
bash# On the new server, run this rsync command pointing to old server
rsync -avz --progress root@OLD_SERVER_IP:/var/www/html/martreza/storage/app/ \
  /var/www/html/martreza/storage/app/

# Fix permissions after transfer
chown -R www-data:www-data /var/www/html/martreza/storage

DAY 4–5 — Configure Services on New Server
Apache Virtual Host:
bashnano /etc/apache2/sites-available/martreza.conf
apache<VirtualHost *:80>
    ServerName shopmartreza.com
    ServerAlias www.shopmartreza.com
    DocumentRoot /var/www/html/martreza/public

    <Directory /var/www/html/martreza/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.2-fpm.sock|fcgi://localhost/"
    </FilesMatch>

    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/json
    </IfModule>

    ErrorLog ${APACHE_LOG_DIR}/martreza_error.log
    CustomLog ${APACHE_LOG_DIR}/martreza_access.log combined
</VirtualHost>
basha2ensite martreza.conf
a2dissite 000-default.conf
systemctl reload apache2
SSL Certificate:
bashcertbot --apache -d shopmartreza.com -d www.shopmartreza.com
# Follow the prompts — Let's Encrypt issues the cert automatically
Supervisor Queue Worker:
bashnano /etc/supervisor/conf.d/martreza-queue.conf
ini[program:martreza-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/martreza/artisan queue:work redis --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/martreza/storage/logs/queue-worker.log
stopwaitsecs=3600
bashsupervisorctl reread
supervisorctl update
supervisorctl status
Laravel Cron Job:
bashcrontab -u www-data -e
# Add:
* * * * * cd /var/www/html/martreza && php artisan schedule:run >> /dev/null 2>&1
Optimize Laravel for production:
bashcd /var/www/html/martreza
php artisan key:generate --force  # Only if needed
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

DAY 5–6 — Testing on New Server (Before DNS Switch)
Test the new server by adding a temporary hosts file entry on your local machine, pointing the domain to the new server IP without changing DNS. On your Mac or Windows machine:
# Mac/Linux: sudo nano /etc/hosts
# Windows: C:\Windows\System32\drivers\etc\hosts

# Add this line:
NEW_SERVER_IP shopmartreza.com www.shopmartreza.com
Now your browser will hit the new server when you visit shopmartreza.com. Test everything:
□ Homepage loads correctly
□ Admin panel accessible and functional
□ Vendor registration flow works
□ Product listing pages load
□ Images display correctly
□ User login/registration works
□ Cart and checkout flow works
□ Email sends (test password reset)
□ Queue worker processes test job
□ Cron jobs fire (check logs after 1 minute)
□ Mobile API responds (test with Postman)
□ SSL certificate shows valid padlock
Remove the hosts file entry after testing.

DAY 6–7 — DNS Switch (Go-Live)
Step 1: Lower DNS TTL (do this 24 hours before switching)
In Namecheap, go to DNS settings for shopmartreza.com. Change the TTL on the A record from its current value to 300 (5 minutes). This ensures DNS changes propagate quickly when you switch.
Step 2: Take a final database snapshot from old server
bashmysqldump -u root -p shopmartreza > /root/shopmartreza_prefinal.sql
scp /root/shopmartreza_prefinal.sql root@NEW_SERVER_IP:/root/
Import on new server:
bashmysql -u martreza_user -p shopmartreza < /root/shopmartreza_prefinal.sql
Step 3: Switch DNS
In Namecheap, change the A record for shopmartreza.com to point to the new DigitalOcean server IP.
Step 4: Monitor for 2–3 hours
bash# Watch Apache error log on new server
tail -f /var/log/apache2/martreza_error.log

# Watch Laravel application log
tail -f /var/www/html/martreza/storage/logs/laravel.log

# Watch queue worker log
tail -f /var/www/html/martreza/storage/logs/queue-worker.log
Step 5: Rollback plan if something is wrong
Switch the DNS A record back to the old YegaraHost server IP in Namecheap. Because TTL is 300 seconds, this propagates in under 5 minutes. The old server remains untouched until after you confirm the new server is fully stable — minimum 2 weeks after successful migration.

📋 Your Immediate Action List (Priority Order)
TODAY:
□ 1. Add the missing cron job to current server (5 minutes)
□ 2. Take database backup and download to local machine (15 minutes)
□ 3. Sign up for Brevo and update MAIL settings in current .env (20 minutes)
□ 4. Sign up for DigitalOcean (check for $200 credit first) (10 minutes)

THIS WEEK:
□ 5. Create Droplet and Space on DigitalOcean
□ 6. Run server setup commands (Day 2-3 above)
□ 7. Transfer code, database, and storage files
□ 8. Configure all services on new server
□ 9. Full test suite before DNS switch
□ 10. DNS switch → go live