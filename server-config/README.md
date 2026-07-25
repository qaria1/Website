# Server Configuration — shopmartreza.com

**Server:** `91.99.14.192` (YegaraHost VPS, Ubuntu 22.04 LTS)  
**Last updated:** 2026-07-25

These files are **reference copies** of the system-level configuration applied to the production server. They are NOT automatically deployed by `git push` — they document what's on the server so configs can be recreated if the server is ever rebuilt or migrated.

---

## Files

| File | Server Path | Purpose |
|---|---|---|
| `php/php8.2-fpm-pool.conf` | `/etc/php/8.2/fpm/pool.d/www.conf` | PHP-FPM pool — sets `memory_limit=256M` per web request |
| `php/php8.2-cli.ini` | `/etc/php/8.2/cli/php.ini` | PHP CLI — sets `memory_limit=512M` for queue workers/artisan |
| `supervisor/laravel-queue.conf` | `/etc/supervisor/conf.d/laravel-queue.conf` | Supervisor queue worker — `--memory=256 --max-time=3600` guard |
| `redis/redis.conf` | `/etc/redis/redis.conf` | Redis — `maxmemory 512mb`, `allkeys-lru` eviction |
| `logrotate/laravel` | `/etc/logrotate.d/laravel` | Log rotation — daily, 7-day retention, compressed |
| `cron/root-crontab` | `crontab -l` (root) | Laravel scheduler cron — runs every minute |

---

## Key Optimizations Applied (2026-07-25)

### Root Cause of Previous Memory Crash
PHP had `memory_limit = -1` (unlimited) with no swap. A heavy PDF/export queue job consumed all RAM → OOM killer rebooted the server.

### Fixes Applied
1. **PHP memory capped** — 256MB/request (FPM), 512MB (CLI)
2. **2GB swap created** — `/swapfile` permanent via `/etc/fstab`, swappiness=10
3. **Queue worker memory guard** — `--memory=256 --max-time=3600` flags added
4. **Redis installed** — Cache, sessions, queue all switched from file/database to Redis
5. **PHP 8.1 FPM disabled** — Was running alongside 8.2, wasting RAM
6. **Laravel cron scheduler added** — Was never running before
7. **Log rotation configured** — Prevents unbounded log growth

---

## How to Re-Apply on a New Server

```bash
# PHP memory limits
sed -i '/php_admin_value\[memory_limit\]/d' /etc/php/8.2/fpm/pool.d/www.conf
echo "php_admin_value[memory_limit] = 256M" >> /etc/php/8.2/fpm/pool.d/www.conf
sed -i 's/^memory_limit.*/memory_limit = 512M/' /etc/php/8.2/cli/php.ini
systemctl reload php8.2-fpm

# Swap
fallocate -l 2G /swapfile && chmod 600 /swapfile && mkswap /swapfile && swapon /swapfile
echo '/swapfile none swap sw 0 0' >> /etc/fstab
sysctl vm.swappiness=10 && echo "vm.swappiness=10" >> /etc/sysctl.conf

# Redis
apt-get install -y redis-server php8.2-redis
echo "maxmemory 512mb" >> /etc/redis/redis.conf
echo "maxmemory-policy allkeys-lru" >> /etc/redis/redis.conf
systemctl restart redis-server && systemctl reload php8.2-fpm

# Supervisor queue worker
cp server-config/supervisor/laravel-queue.conf /etc/supervisor/conf.d/
supervisorctl reread && supervisorctl update

# Logrotate
cp server-config/logrotate/laravel /etc/logrotate.d/laravel

# Cron (Laravel scheduler)
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www/html/martreza && php artisan schedule:run >> /dev/null 2>&1") | crontab -
```
