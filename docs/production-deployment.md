# Production Deployment Reference

ملف مرجعي للملفات المتأثرة بالـ `.gitignore` وما يحتاج يتعمل على سيرفر البرودكشن.

---

## 1. ملف `.env`

انسخ الـ `.env` وعدّل القيم دي:

```env
APP_NAME=Aven
APP_ENV=production
APP_KEY=base64:7qq6kxXHgRx5cq3H6YU0OcnOM0KvkOnUVokCqZjhj14=
APP_DEBUG=false
APP_DEBUG_ALLOWED_IPS=
APP_URL=https://YOUR_PRODUCTION_DOMAIN
APP_ADMIN_URL=admin
APP_TIMEZONE=africa/cairo

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_CURRENCY=EGP

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD
DB_PREFIX=

# ✅ Redis للـ Cache والـ Session (أسرع من File)
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=redis
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

# استخدم predis (لا يحتاج PHP extension)
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Full Page Cache — شغّله في البرودكشن
RESPONSE_CACHE_ENABLED=true

MAIL_MAILER=bagisto-dynamic-smtp
MAIL_SCHEME=null
MAIL_HOST=YOUR_MAIL_HOST
MAIL_PORT=587
MAIL_USERNAME=YOUR_MAIL_USERNAME
MAIL_PASSWORD=YOUR_MAIL_PASSWORD

MAIL_FROM_ADDRESS=shop@yourdomain.com
MAIL_FROM_NAME=Aven
ADMIN_MAIL_ADDRESS=admin@yourdomain.com
ADMIN_MAIL_NAME=Admin
CONTACT_MAIL_ADDRESS=contact@yourdomain.com
CONTACT_MAIL_NAME=Contact

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME=Aven
VITE_HOST=localhost
VITE_PORT=

GROQ_API_KEY=your-groq-api-key-here

VAPID_PUBLIC_KEY=BD5JZ0BFl_VEC948Jkf5pSLwLahsS0xxqUMXEflLVqHUBdwSXFndKPSIFAAdynh7yHsFg9NSz7gwG8pNk_aHTjs
VAPID_PRIVATE_KEY=e2gDdvYRBxf1ewRKgQxgYgqierThmHXnexc58akhPT4
```

### القيم اللي لازم تتغير للبرودكشن

| المتغير | القيمة المحلية | البرودكشن |
|---------|--------------|-----------|
| `APP_ENV` | `local` | `production` |
| `APP_URL` | `http://aven.test` | دومين البرودكشن |
| `DB_DATABASE` | `aven` | اسم DB على السيرفر |
| `DB_USERNAME` | `root` | يوزر DB على السيرفر |
| `DB_PASSWORD` | *(فاضي)* | باسورد DB على السيرفر |
| `CACHE_STORE` | `redis` | `redis` (لو Redis متاح) أو `file` |
| `SESSION_DRIVER` | `redis` | `redis` (لو Redis متاح) أو `file` |
| `REDIS_CLIENT` | `predis` | `predis` |
| `QUEUE_CONNECTION` | `sync` | `database` (أو `redis`) |
| `MAIL_HOST` | `127.0.0.1` | SMTP server حقيقي |
| `MAIL_PORT` | `2525` | `587` أو `465` |
| `MAIL_USERNAME` | `null` | إيميل SMTP |
| `MAIL_PASSWORD` | `null` | باسورد SMTP |
| `MAIL_FROM_ADDRESS` | `shop@example.com` | إيميل المتجر الحقيقي |

---

## 2. أوامر الـ Deploy الكاملة (بالترتيب)

```bash
# 1. Pull الكود
git pull origin 2.4

# 2. PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Migrations (مفيش migrations جديدة في هذا الـ release لكن شغّلها احتياطاً)
php artisan migrate --force

# 4. Clear + Cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Storage symlink (مرة واحدة بس على سيرفر جديد)
php artisan storage:link

# 6. Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 3. Redis — مطلوب على السيرفر ⚠️

الـ cache والـ session دلوقتي بيستخدموا Redis. لازم Redis يكون شغال على السيرفر.

### تثبيت Redis على Ubuntu/Debian
```bash
sudo apt update
sudo apt install redis-server -y
sudo systemctl enable redis-server
sudo systemctl start redis-server

# تأكد إنه شغال
redis-cli ping   # المفروض يرجع PONG
```

### تثبيت Redis على CentOS/AlmaLinux
```bash
sudo dnf install redis -y
sudo systemctl enable redis
sudo systemctl start redis
redis-cli ping
```

### لو السيرفر مش بيدعم Redis
غيّر في الـ `.env`:
```env
CACHE_STORE=file
SESSION_DRIVER=file
```

---

## 4. Queue Worker — مطلوب لو غيّرت QUEUE_CONNECTION لـ database

```bash
# مرة واحدة — إنشاء جدول الـ queue
php artisan queue:table
php artisan migrate

# تشغيل الـ worker (استخدم supervisor في البرودكشن)
php artisan queue:work --sleep=3 --tries=3 --daemon
```

### Supervisor config (مثال)
```ini
[program:aven-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/aven/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/aven/storage/logs/worker.log
```

---

## 5. ملفات الـ Storage (media uploads)

الصور والملفات المرفوعة في:
- `storage/app/public/product/`
- `storage/app/public/category/`
- `storage/app/public/channel/`
- `storage/app/public/configuration/`
- `storage/app/public/theme/`
- `storage/app/public/admins/`
- `storage/app/public/tinymce/`
- `public/storage/` ← symlink

**على السيرفر:**
1. ارفع محتوى `storage/app/public/` إلى مكانه على السيرفر
2. شغّل: `php artisan storage:link`

---

## 6. ما يحتاج تشغله بعد كل Pull

```bash
git pull origin 2.4
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7. Features جديدة — إعدادات مطلوبة في Admin

### Coming Soon Page
- **Admin → Configuration → General → Content → Coming Soon Page**
- فعّل الـ toggle لو عاوز تشغّل صفحة الـ launch
- اضبط: Heading, Sub Text, Background Video URL (mp4 أو YouTube), Logo URL

### Egypt Shipping — Governorate Rates
- **Admin → Configuration → Egypt Shipping → Governorate Rates**
- تأكد إن الـ governorates مفعّلة وليها rates
- الـ API endpoint الجديد: `GET /api/egypt-shipping/rate/{code}`

---

## 8. ملاحظات مهمة

- **APP_KEY**: ثبّته ومتغيّروش — لو اتغيّر، كل الـ sessions والـ encrypted data هتبطل.
- **VAPID Keys**: لو غيّرتهم، المستخدمين المشتركين في Push Notifications لازم يشتركوا تاني.
- **GROQ_API_KEY**: احتفظ بيه أو اعمل مفتاح جديد من [console.groq.com](https://console.groq.com).
- **RESPONSE_CACHE_ENABLED**: خليه `true` في البرودكشن للـ Full Page Cache.
- **predis**: مش محتاج PHP extension — بيشتغل out of the box مع `composer install`.
