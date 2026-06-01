# Aven — Server Deployment Runbook

> ملف تشغيلي جاهز للنسخ والتنفيذ على السيرفر مباشرةً.

---

## ✅ Checklist — أول مرة (Fresh Deploy)

- [ ] تثبيت Redis
- [ ] إعداد `.env`
- [ ] `composer install`
- [ ] `php artisan migrate`
- [ ] `php artisan storage:link`
- [ ] Cache & Optimize
- [ ] Queue Worker (Supervisor)
- [ ] Permissions

---

## ✅ Checklist — كل Update

- [ ] `git pull origin 2.4`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `php artisan migrate --force`
- [ ] `php artisan optimize:clear`
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`

---

## 1. تثبيت Redis (مرة واحدة)

```bash
# Ubuntu / Debian
sudo apt update && sudo apt install redis-server -y
sudo systemctl enable redis-server
sudo systemctl start redis-server
redis-cli ping       # لازم يرجع: PONG
```

```bash
# CentOS / AlmaLinux / Rocky
sudo dnf install redis -y
sudo systemctl enable redis
sudo systemctl start redis
redis-cli ping
```

---

## 2. إعداد `.env` (مرة واحدة)

```env
APP_NAME=Aven
APP_ENV=production
APP_KEY=base64:7qq6kxXHgRx5cq3H6YU0OcnOM0KvkOnUVokCqZjhj14=
APP_DEBUG=false
APP_URL=https://YOUR_DOMAIN

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD

SESSION_DRIVER=redis
CACHE_STORE=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

QUEUE_CONNECTION=database

RESPONSE_CACHE_ENABLED=true

MAIL_MAILER=bagisto-dynamic-smtp
MAIL_HOST=YOUR_SMTP_HOST
MAIL_PORT=587
MAIL_USERNAME=YOUR_SMTP_USER
MAIL_PASSWORD=YOUR_SMTP_PASS
MAIL_FROM_ADDRESS=shop@yourdomain.com
MAIL_FROM_NAME=Aven
ADMIN_MAIL_ADDRESS=admin@yourdomain.com

GROQ_API_KEY=YOUR_GROQ_KEY

VAPID_PUBLIC_KEY=BD5JZ0BFl_VEC948Jkf5pSLwLahsS0xxqUMXEflLVqHUBdwSXFndKPSIFAAdynh7yHsFg9NSz7gwG8pNk_aHTjs
VAPID_PRIVATE_KEY=e2gDdvYRBxf1ewRKgQxgYgqierThmHXnexc58akhPT4
```

---

## 3. Deploy — أول مرة (Fresh)

```bash
# Clone
git clone https://github.com/mohamedHamdyAli/Aven.git /var/www/aven
cd /var/www/aven
git checkout 2.4

# Dependencies
composer install --no-dev --optimize-autoloader

# Database
php artisan migrate --force

# Queue table
php artisan queue:table
php artisan migrate --force

# Storage
php artisan storage:link

# Cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 4. Deploy — Update (كل push جديد)

```bash
cd /var/www/aven

git pull origin 2.4

composer install --no-dev --optimize-autoloader

php artisan migrate --force

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# لو في ملفات storage جديدة (صور/uploads)
# ارفعها يدوياً لـ storage/app/public/
```

---

## 5. Queue Worker — Supervisor (مرة واحدة)

```bash
# تثبيت Supervisor
sudo apt install supervisor -y

# إنشاء config
sudo nano /etc/supervisor/conf.d/aven-queue.conf
```

محتوى الـ config:
```ini
[program:aven-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/aven/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/aven
autostart=true
autorestart=true
numprocs=2
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/aven/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# تفعيل
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start aven-queue:*

# تحقق
sudo supervisorctl status
```

---

## 6. Nginx Config (مثال)

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    root /var/www/aven/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* { deny all; }

    # Static assets cache
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|webp|woff2|woff|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
}
```

---

## 7. SSL — Let's Encrypt (مرة واحدة)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
sudo certbot renew --dry-run   # تأكد إن الـ auto-renew شغال
```

---

## 8. PHP Extensions المطلوبة

```bash
sudo apt install -y \
  php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
  php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath \
  php8.3-intl php8.3-soap php8.3-tokenizer php8.3-fileinfo

# لو عاوز Redis extension بدلاً من predis (اختياري)
sudo apt install php8.3-redis -y
```

---

## 9. تنبيهات مهمة

| ⚠️ | التنبيه |
|----|---------|
| 🔑 | **APP_KEY** — لا تغيّره أبداً بعد ما تحطه — كل الـ sessions والـ encrypted data هتتعطّل |
| 📧 | **VAPID Keys** — لو اتغيّروا، المشتركين في Push Notifications لازم يشتركوا تاني |
| 🗄️ | **Backup** — اعمل backup للـ DB قبل أي migrate |
| 📦 | **predis** — لا يحتاج PHP extension، يشتغل تلقائياً مع composer install |
| 🚀 | **Coming Soon** — تُفعَّل من Admin → Configuration → General → Content → Coming Soon Page |
| 💳 | **Egypt Shipping** — تأكد من إضافة rates للـ governorates من Admin → Configuration |
