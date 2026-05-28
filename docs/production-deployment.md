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

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync

CACHE_STORE=file
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# اضبطها true في البرودكشن عشان الـ Full Page Cache يشتغل
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

# مفتاح Groq للـ AI Support — نفس المفتاح أو مفتاح بروودكشن جديد
GROQ_API_KEY=your-groq-api-key-here

# مفاتيح VAPID للـ Push Notifications — لازم تكون ثابتة (نفس القيم أو تنشئ جديدة مرة واحدة بس)
VAPID_PUBLIC_KEY=BD5JZ0BFl_VEC948Jkf5pSLwLahsS0xxqUMXEflLVqHUBdwSXFndKPSIFAAdynh7yHsFg9NSz7gwG8pNk_aHTjs
VAPID_PRIVATE_KEY=e2gDdvYRBxf1ewRKgQxgYgqierThmHXnexc58akhPT4
```

### القيم اللي لازم تتغير للبرودكشن

| المتغير | القيمة المحلية | ملاحظة |
|---------|--------------|--------|
| `APP_ENV` | `local` | غيّره إلى `production` |
| `APP_URL` | `http://aven.test` | دومين البرودكشن |
| `DB_DATABASE` | `aven` | اسم قاعدة البيانات على السيرفر |
| `DB_USERNAME` | `root` | يوزر DB على السيرفر |
| `DB_PASSWORD` | *(فاضي)* | باسورد DB على السيرفر |
| `MAIL_HOST` | `127.0.0.1` | SMTP server حقيقي |
| `MAIL_PORT` | `2525` | عادةً `587` أو `465` |
| `MAIL_USERNAME` | `null` | إيميل SMTP |
| `MAIL_PASSWORD` | `null` | باسورد SMTP |
| `MAIL_FROM_ADDRESS` | `shop@example.com` | إيميل المتجر الحقيقي |
| `ADMIN_MAIL_ADDRESS` | `admin@example.com` | إيميل الأدمن |
| `CONTACT_MAIL_ADDRESS` | `contact@example.com` | إيميل التواصل |

---

## 2. ملفات الـ bootstrap/cache

دي ملفات بتتولد أوتوماتيك — مش محتاج تنسخها. بعد الـ deploy شغّل:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 3. ملف الـ storage (media uploads)

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
1. ارفع محتوى `storage/app/public/` إلى مكانه على السيرفر.
2. شغّل: `php artisan storage:link` عشان تنشئ الـ symlink.

---

## 4. أوامر الـ Deploy الكاملة (ترتيب)

```bash
# 1. pull الكود
git pull origin 2.4

# 2. dependencies
composer install --no-dev --optimize-autoloader

# 3. migrations
php artisan migrate --force

# 4. assets (لو الـ build مش موجود)
cd packages/Webkul/Admin && npm install && npm run build && cd ../../..
cd packages/Webkul/Shop  && npm install && npm run build && cd ../../..

# 5. clear + cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. storage symlink (مرة واحدة بس)
php artisan storage:link

# 7. permissions
chmod -R 775 storage bootstrap/cache
```

---

## 5. ملف الـ Admin node_modules

`packages/Webkul/Admin/node_modules/` و `package-lock.json` — متأثرين بالـ ignore.

على السيرفر: شغّل `npm install` جوه الـ package directory لو هتعمل build هناك.
البديل الأسهل: ابني الـ assets محلياً وارفع الـ `build/` folder مباشرةً.

---

## 6. ملاحظات مهمة

- **VAPID Keys**: لو غيّرت المفاتيح في البرودكشن، المستخدمين المشتركين في الـ Push Notifications محتاجين يشتركوا تاني.
- **GROQ_API_KEY**: المفتاح الحالي للـ AI Support — احتفظ بيه أو اعمل مفتاح جديد من [console.groq.com](https://console.groq.com).
- **APP_KEY**: ثبّته ومتغيّروش على السيرفر — لو اتغيّر، كل الـ sessions والـ encrypted data هتبطل.
- **RESPONSE_CACHE_ENABLED**: خليه `true` في البرودكشن للـ Full Page Cache، `false` بس لو بتعمل debug.
