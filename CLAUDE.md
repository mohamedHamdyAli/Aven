# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Bagisto 2.4.x - open-source Laravel 12 e-commerce platform. PHP 8.3+, Vue.js 3, Tailwind CSS 3, Vite 5.

Custom fashion store named **Aven**, running locally via **Laragon** on `http://aven.test` (Apache vhost, not `php artisan serve`).

## Common Commands

### Development
```bash
composer install                # Install PHP dependencies
php artisan bagisto:install     # Full installation (migrations, seeders, assets)
php artisan optimize:clear      # Clear all caches (run after config/code changes)
php artisan view:cache          # Pre-compile Blade templates (required on Windows/Apache)
composer dump-autoload -o       # Regenerate autoloader after adding new classes
```

After any code change that touches config, routes, or new classes, always run:
```bash
php artisan optimize:clear && php artisan view:cache
```

After `composer dump-autoload`, **restart Apache** to clear OPcache — otherwise the old class map stays in memory.

### Restart Apache (Laragon, Windows)
```powershell
taskkill /IM httpd.exe /F
Start-Process "C:\laragon\bin\apache\httpd-2.4.66-260223-Win64-VS18\bin\httpd.exe" -WindowStyle Hidden
```

### Testing
```bash
vendor/bin/pest                                         # Run all tests
vendor/bin/pest --testsuite="Admin Feature Test"        # Run a specific test suite
vendor/bin/pest packages/Webkul/Admin/tests/Feature     # Run tests in a directory
vendor/bin/pest --filter="test name"                    # Run a single test by name
```

Test suites defined in `phpunit.xml`: Admin Feature, Core Unit, Customer Unit, DataGrid Unit, Installer Feature, PayU Unit/Feature, Razorpay Unit/Feature, Shop Feature, Stripe Unit/Feature.

Tests use **Pest 3** with package-specific TestCase classes bound in `tests/Pest.php`. Each package's tests live in `packages/Webkul/<Package>/tests/`. All TestCase classes use the `DatabaseTransactions` trait — each test rolls back automatically.

### E2E Tests (Playwright)
```bash
cd packages/Webkul/Admin   # or Shop
npm install
npx playwright install --with-deps chromium
npx playwright test --config=tests/e2e-pw/playwright.config.ts
```
Tests require a running Laravel server and seeded database. Set `BASE_URL` env var if not using default.

### Code Style
```bash
vendor/bin/pint             # Fix PHP code style (Laravel Pint)
vendor/bin/pint --test      # Check style without fixing
```

### Translations
When adding new translation keys, provide translations for **all locales** in the package's `Resources/lang/` directory. Verify with:
```bash
php artisan bagisto:translations:check
```

## Architecture

### Modular Package System

All functionality lives in **`packages/Webkul/`** (64 packages). Each package is a self-contained Laravel package with its own models, controllers, routes, views, migrations, and service providers.

**Dual registration**: Packages register in two places:
1. **`bootstrap/providers.php`** — Main ServiceProvider (routes, views, events, config)
2. **`config/concord.php`** — ModuleServiceProvider (Konekt Concord model registration) — only packages with models that need proxy substitution

### Key Design Patterns

**Repository Pattern**: All database access goes through repositories (`Prettus L5 Repository`). Interfaces in `Contracts/`, implementations in `Repositories/`. Never query models directly in controllers.

**Proxy Pattern**: Models have Proxy classes (e.g., `ProductProxy`) enabling substitution without modifying core. Reference proxies when type-hinting across packages.

**Event-Driven**: Core fires events at lifecycle points (`checkout.order.save.after`, etc.). Extend via listeners, never modify core packages.

### Package Anatomy

```
packages/Webkul/<Package>/src/
├── Config/           # system.php (admin settings), admin-menu.php, acl.php
├── Database/         # Migrations/, Seeders/, Factories/
├── Http/Controllers/ # Admin/ and Shop/ subdirectories
├── Models/           # Eloquent models + Proxy classes
├── Repositories/     # Data access layer
├── Contracts/        # Interfaces for models and repositories
├── Resources/
│   ├── views/        # Blade templates (admin/, shop/)
│   ├── lang/         # Localization files
│   └── assets/       # CSS/JS source files
├── Routes/           # admin-routes.php, shop-routes.php, api.php
├── Providers/        # ServiceProvider + ModuleServiceProvider
└── Listeners/        # Event listeners
```

### Frontend Assets

Admin, Shop, and Installer each have independent Vite builds. Run from within the package directory:
- **Admin**: `packages/Webkul/Admin/` → `public/themes/admin/default/build/`
- **Shop**: `packages/Webkul/Shop/` → `public/themes/shop/default/build/`

Vue 3 components are used within Blade templates via `@pushOnce('scripts')` / Blade component slots.

### Adding a New Package

1. Create `packages/Webkul/<Name>/src/` with the standard structure
2. Add PSR-4 entry to root `composer.json` autoload.psr-4
3. Register ServiceProvider in `bootstrap/providers.php`
4. If package has models: register ModuleServiceProvider in `config/concord.php`
5. Run `composer dump-autoload -o`, restart Apache, then `php artisan migrate && php artisan optimize:clear && php artisan view:cache`

## Windows / Laragon Gotchas

- **`customers.id` is `int unsigned`** (not `bigint unsigned`). Foreign keys referencing `customers.id` must use `unsignedInteger()`, not `unsignedBigInteger()`.
- **Storage permissions**: Apache user needs write access to `storage/` and `bootstrap/cache/`. Fix with:
  ```powershell
  icacls "D:\aven\storage" /grant "Everyone:(OI)(CI)F" /T /Q
  icacls "D:\aven\bootstrap\cache" /grant "Everyone:(OI)(CI)F" /T /Q
  ```
- **OPcache**: Enabled in php.ini. After `composer dump-autoload`, restart Apache or new classes won't be found.
- **Blade view cache**: Run `php artisan view:cache` after `optimize:clear` so Apache doesn't fail trying to write compiled views (permission issue on first request).
- **API routes for shop widget/endpoints**: Use `api` middleware (not `web`) to avoid CSRF issues. The shop layout has a `<meta name="csrf-token">` tag, but API endpoints that don't need session state are simpler with `api` middleware.
- **FPC (Full Page Cache)**: Set `RESPONSE_CACHE_ENABLED=false` in `.env` during development or after changes to shop pages won't appear. Cache is flushed automatically on product/category/order/CMS events in production, but manual flush: `php artisan responsecache:clear`.

## Custom Aven Packages (built on top of Bagisto core)

These packages are Aven-specific and live alongside the core Bagisto packages:

| Package | Purpose |
|---------|---------|
| `AiSupport` | AI customer service chat widget (Groq/Llama). Web chat, WhatsApp, Messenger, Email channels. Admin conversation review, Knowledge Base, human handoff. |
| `AbandonedCart` | Abandoned cart recovery via WhatsApp/Messenger/Email |
| `Affiliate` | Affiliate marketing program |
| `BulkDeal` | Quantity-based bulk pricing (tiered discounts applied in cart) |
| `Aramex` | Aramex shipping carrier integration (Cairo/outside-Cairo pricing) |
| `Blog` | Blog/content management with admin CRUD |
| `Bosta` | Bosta delivery integration |
| `CostManagement` | Product cost tracking and margin reporting |
| `EgyptShipping` | Egypt-specific shipping zones/rates |
| `Fawry` | Fawry payment gateway (PAYATFAWRY + CARD) |
| `FPC` | Full Page Cache for shop pages — uses Spatie response-cache. Invalidated automatically via event listeners on product/category/order/CMS changes. Toggled by `RESPONSE_CACHE_ENABLED` in `.env`. |
| `FlashSale` | Time-limited flash sale campaigns |
| `GiftCard` | Gift card generation and redemption |
| `GoogleShopping` | Google Shopping feed/product sync |
| `Loyalty` | Customer loyalty points (earn on order, redeem at checkout) |
| `OrderNotification` | Admin notifications for new orders |
| `Paymob` | Paymob payment gateway |
| `ProductQA` | Product Q&A / customer questions on product pages |
| `PushNotification` | Browser push notifications (VAPID) |
| `Referral` | Customer referral program with reward credits |
| `ShopTheLook` | "Shop the Look" feature for outfit bundles |
| `SizeGuide` | Per-category size guide modal |
| `SmsNotification` | SMS via Vonage/Twilio for order events |
| `SocialCommerce` | WhatsApp/Messenger shop integration |
| `SocialShare` | Social sharing buttons on product pages |
| `StoreLocator` | Physical store locations map |
| `Valu` | Valu BNPL (Egyptian installment payment) |
| `Wallet` | Customer wallet/credit balance |

## AI Support Module

Config: **Admin → Configuration → AI Support**
- API key: stored in `.env` as `GROQ_API_KEY` (fallback) or in admin config
- Model: Llama 3.3 70B via Groq (`https://api.groq.com/openai/v1/chat/completions`)
- Chat widget injected into shop layout at `</body>` via `@include('ai-support::shop.chat-widget')`
- API endpoint: `POST /api/ai-support/chat` (uses `api` middleware — no CSRF)
- Admin conversations: `/admin/ai-support/conversations`
- Knowledge Base: `/admin/ai-support/knowledge-base`

## Changelog Policy (MANDATORY)

`Changelog.md` must exist at the repo root. After **any** task, append under the current date:

```
## YYYY-MM-DD

### Feature | Fix | Refactor | Improvement | Migration
- Concise description of what changed and why.
```

Rules: never delete previous entries, append under existing date if present.

---

## Workflow

### Plan First (MANDATORY — every request)
**Always enter plan mode automatically before any implementation.** No exceptions — even for small tasks.

1. Present the plan clearly: what will be created/changed, which files are touched, what the expected outcome is.
2. Wait for explicit user approval before writing a single line of code.
3. Write the approved plan to `tasks/todo.md` (create `tasks/` if it doesn't exist).

### Models: Unit Tests Required (MANDATORY)
Whenever a **new model is created** or an **existing model is modified** (new fields, relationships, scopes, methods):

1. Write a complete unit test suite covering:
   - Model attributes / fillable / casts
   - All relationships (hasMany, belongsTo, etc.)
   - All custom scopes and methods
   - Factory / seeder correctness
2. Run the tests and confirm they pass before delivering.
3. Report test results (passed / failed) explicitly in the response.

### UI / Shop Changes: Playwright E2E (MANDATORY)
Whenever a feature touches the **shop or admin UI** (new page, form, CRUD flow):

1. Run a full Playwright E2E test covering the **complete CRUD cycle**: Create → Read → Update → Delete (or the applicable subset).
2. Fix any failures before reporting the task done.
3. Report Playwright results explicitly in the response.

### Lessons
After any correction: update `tasks/lessons.md` with the pattern to prevent recurrence.

### Verification
Never mark a task complete without proving it works — test output or Playwright results must be included.

---

## Core Principles

- **Simplicity First**: Impact minimal code. Avoid abstractions beyond the task.
- **No Laziness**: Find root causes. No temporary fixes.
- **Minimal Impact**: Only touch what's necessary.
