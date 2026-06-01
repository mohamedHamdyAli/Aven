# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## 2026-05-29

### Improvement
- Changed shop fonts from Orbitron/Montserrat to Raleway (headings/nav) + Open Sans (body) for a cleaner modern look.

### Fix
- **Quick Add to Cart — "Options are missing for this product." error** — the card component was fetching the configurable variant index from the API but discarding it. `addToCartWithVariant()` now resolves the selected `super_attribute` values to the matching `selected_configurable_option` (variant product ID) using that index before posting to the cart API. Without this field the backend always rejected the request.

### Feature
- **Add item to existing order** — admin can add any product (simple or configurable with size/color selection) to a pending or processing order. Opens a search drawer: type product name, pick the variant attributes, set qty, and confirm. Order totals are updated immediately.
- **Order item editing in admin** — added per-item "Update Qty" and "Remove Item" controls on the order view page. Admins can reduce an item's quantity (cancels the difference, updates order totals) or remove it entirely (cancels all remaining qty, restores inventory). Only shown for items that still have cancellable qty (not yet invoiced/shipped). Picking list now also excludes cancelled items correctly.
- **Quick Add to Cart from product listing** — clicking "Add to Cart" on a configurable product card now opens a bottom-sheet modal (mobile) / dialog (desktop) with color and size pickers. Once all attributes are selected the item is added to the cart without navigating away. Simple products are added directly as before.

### Fix
- **BulkDeal discount applying incorrectly** — deal was triggering with fewer items than required (paid_qty + deal_qty) because the discount was persisted on cart items via `+=` and never reset when cart conditions changed. Redesigned to compute discount on `checkout.cart.collect.totals.after` and apply at cart level instead of item level, eliminating stale data and CartRule conflicts. Also fixed multiple cycles (e.g. 6 items with "buy 2 get 1" now correctly applies 2 discount cycles).
- **Checkout cart +/- quantity buttons not working** — `AbandonedCart` listener `CartActivityTracker::onCartActivity` declared `CartContract $cart` but the `checkout.cart.update.after` event dispatches a `CartItem`. The resulting `TypeError` was silently swallowed by the controller's try/catch, returning HTTP 200 with no update. Fixed listener to accept both `Cart` and `CartItem` and resolve the cart accordingly.

## 2026-05-28

### Feature
- **Complete model unit test suite (Phase 1 + 2 + 3)** — 434 Pest 3 unit tests across all 163 Eloquent models in the codebase (both Aven custom packages and core Bagisto packages). Each model has tests covering fillable mass assignment, casts, relationships (BelongsTo/HasMany/BelongsToMany type verification), scopes, and factory states. Created 52 factories, 36 TestCase classes, 90+ test files. All 434 tests pass (1 pre-existing CoreTest failure unrelated to this work). Infrastructure: registered 36 new test namespaces in `composer.json`, 36 `uses()` bindings in `tests/Pest.php`, and 36 `<testsuite>` entries in `phpunit.xml`.

## 2026-05-27

### Improvement
- **Checkout "Proceed" button transforms to "Place Order"** — after the customer selects both shipping and payment methods (`canPlaceOrder == true`), the address section's "Proceed" button label changes to "Place Order" and clicking it triggers `placeOrder()` directly. Uses Vue prop/emit chain: `v-checkout` → `v-checkout-address-customer` → `@place-order`.

### Fix
- **"Credentials not found" on login** — No customer accounts existed; the DB was empty. Root cause identified. Also fixed two related bugs: (1) `SessionController::store()` now uses `redirect()->intended()` so users land back on checkout after login instead of the home page. (2) `CustomerSocialAccountRepository::findOrCreateCustomer()` now sets `channel_id` on newly created customers so social-login accounts can also authenticate with email/password later.



### Improvement
- **"Complete The Look" moved to right column** — the Shop The Look section now appears inline in the product page right column, directly below the Buy Now button, instead of as a full-width section below the product block. Cards resized (130px) to fit the narrower column.

### Fix
- **"As low as" label renamed to "Price"** on configurable product price display (`shop::app.products.prices.configurable.as-low-as` translation key in `en/app.php`).
- **Recently Viewed showing E£0.00** — the price stored in `aven_rv` localStorage was the full `getPriceHtml()` HTML rendered inside a `<p v-html>` container; nested `<p>` tags cause the browser to close the outer element early, breaking display. Fixed by storing just the plain formatted price string (`core()->currency(getMinimalPrice())`) instead of full HTML.

### Fix
- **EGP currency symbol rendering as `EÃ‚Â£` instead of `E£`** — The Egyptian Pound symbol was stored in the `currencies` table with triple UTF-8 encoding (hex `45c383e2809ac382c2a3` instead of `45c2a3`). Fixed by updating the `symbol` column directly to `E£` via the Currency model. Root cause: the symbol was originally saved through a form with a charset mismatch.

### Fix
- **Blank product page — `TypeError: Cannot read properties of undefined (reading 'id')` in Vue root render** — The `ProductQA` widget uses Alpine.js directives (`x-for="q in questions" :key="q.id"`, `:disabled="submitting"`, `@submit.prevent`) inside `#app`. Because Vue uses the entire `#app` innerHTML as its root component template, Vue's compiler parsed these as Vue bindings. `q` and `submitting` are not defined in Vue's root data, so `q.id` threw a TypeError that crashed the entire page render, producing a blank page. Fixed by adding `v-pre` to the ProductQA widget's root div (`packages/Webkul/ProductQA/src/Resources/views/shop/widget.blade.php`), which tells Vue to skip compilation of that subtree. Alpine.js is unaffected since it reads the DOM directly.

## 2026-05-26

### Fix
- **Vue TypeError `Cannot read properties of undefined (reading 'id')` on product page** — Two root causes fixed: (1) `v-tabs` template accessed `tab.$attrs.id` where `tab` is a component instance stored in a reactive array; Vue 3's reactive proxy layer can make `$attrs` inaccessible in this context. Fixed by capturing `tabId` as a plain `data` property in `v-tab-item.mounted()` and using `tab.tabId` in the v-tabs template instead. (2) `$product->base_image->small_image_url` in the JS localStorage save script caused a PHP `E_WARNING: Attempt to read property on null` (base_image has no model accessor), which with `display_errors=On` polluted the `<script>` tag and produced a JS syntax error. Fixed by using the already-computed `$productBaseImage` array from `product_image()->getProductBaseImage()`.

### Fix
- **Vue TypeError on product/category pages** — Added optional chaining (`?.`) to `product.base_image` accesses in `card.blade.php` to prevent `Cannot read properties of undefined` crashes when a product has no images. Hardened `v-recently-viewed` localStorage filter to skip null/malformed entries from stale browser storage.

### Fix
- **Category page stuck on skeleton loaders (500 error)** — `ProductResource` called `$this->totalQuantity()` which in `Configurable::totalQuantity()` was treating the return value of `$variant->totalQuantity()` (an int) as an object with a `qty` property, throwing `Attempt to read property "qty" on int`. Fixed by removing the intermediate variable and summing the int return directly.
- **Vue `queryParams` watcher not immediate** — `getProducts()` was never called on initial page mount (only on filter changes). Fixed with `immediate: true` on the watcher.
- **N+1 flash sale queries in ProductResource** — `FlashSaleService::getActiveForProduct()` was called per-product during API serialization. Fixed with a request-scoped static cache that bulk-loads all active flash sales with their products in a single query.

### Fix
- **Product save 500 error after validation fixes** — `LowStockAlertListener::onProductUpdated()` called `$product->product_flat->first()` without a null guard, throwing `Call to a member function first() on null` for products where `product_flat` is not loaded. Fixed with PHP 8 null-safe operator: `$product->product_flat?->first()?->name`.
- **Configurable product save validation failures (three separate issues)**:
  - `variants.*.price required` failing for existing variants: `saveValues()` used `empty($data[$attribute->code])` which returns `true` for price=0, storing NULL in `float_value`. Fixed to check `=== null || === ''` only.
  - `variants.*.weight required` failing: the "Clothing" attribute family does not include the `weight` attribute, so it is always null for those variants. Changed rule to `nullable`.
  - `url_key required` failing for products with no existing `url_key` locale record: added `prepareForValidation()` to auto-generate `url_key` from SKU via `Str::slug()` when the field is empty.
- **Product save not persisting data** — `simple` product type was removed from `product_types.php` in a prior refactor. Since all configurable product variants have `type = 'simple'`, any call to `Product::getTypeInstance()` on a variant threw an exception, silently aborting the entire save. Restored the `simple` type entry (class `Webkul\Product\Type\Simple`) so variant lookups succeed.
- **`v-dropdown` Select Action unresponsive (Vue 3 compatibility)** — Three bugs prevented the "Select Action" dropdown from opening on the configurable product edit page: (1) `beforeDestroy` lifecycle hook (Vue 2) was not called in Vue 3 (`beforeUnmount` required), causing window click listeners to accumulate and throw on every click. (2) `this.$el.children[1]` in `handleFocusOut` was `undefined` because Vue 3's `<transition>` does not eagerly render its child when `v-show="false"` on initial mount — fixed by adding `ref="dropdownContent"` and accessing it via `this.$refs.dropdownContent` with a null guard. (3) `v-for` and `v-if` on the same `<li>` element caused a Vue 3 priority error (`v-if` evaluates before `v-for`, so `type` was undefined) — fixed by wrapping with `<template v-for>` and putting `v-if` on the child element.

## 2026-05-23

### Fix
- **Product invisible after creation** — `AbstractType::create()` never saved default attribute values (including `visible_individually = 1`), so newly created products were absent from the admin product list. Fixed by saving all attributes with non-null defaults immediately after product creation, guarded by `!isset($data['parent_id'])` to avoid double-saving on variants.
- **Configurable variant duplicate-key error** — The default-value save above ran for variant rows too, colliding with `createVariant()`'s own `saveValues()` call. Fixed with the `parent_id` guard and a `$product->load('attribute_values')` reload so the relation cache reflects the already-saved rows.
- **`v-dropdown` Select Action unresponsive** — Missing commas between adjacent template literals in the `positionStyles` computed property (`top-left`, `top-right`, `default` cases) caused a JS parse error that silently broke every `v-dropdown` instance. Fixed by adding the missing trailing commas.

### Refactor
- **Removed unused product types** — `product_types.php` now only registers `configurable` and `grouped`; Simple, Bundle, Downloadable, Virtual, and Booking entries removed.

## 2026-05-22

### Fix
- **MagicAI image generation 403 PERMISSION_DENIED** — When no model was selected in the AI image modal, `prepareProvider(null)` returned null without injecting the Gemini API key stored in admin settings. Fixed by loading the stored API key for `config('ai.default_for_images')` as a fallback in `generateImage()`.

## 2026-05-20

### Fix
- **Admin 500 error** — Blog package `admin-menu.php` had `route: null` on the `content` parent menu item, causing `route('')` exception in `Menu::prepareMenuItems()`. Fixed by setting the parent's route to `admin.blog.index` (same as its first child), matching Bagisto's menu convention.

## 2026-05-20 (Feature Expansion — Fourth Wave)

### Feature
- **TikTok Pixel** — config under Admin → General → Content → TikTok Pixel. Injects TikTok pixel SDK on all shop pages. Enabled/disabled per channel. Config keys: `general.content.tiktok_pixel.enabled`, `.pixel_id`.
- **Tawk.to Live Chat** — config under Admin → General → Content → Tawk.to Live Chat. Embeds Tawk.to widget script on all shop pages using Property ID and Widget ID. Config keys: `general.content.tawk_chat.*`.
- **Facebook Conversions API (Server-Side)** — extends existing Facebook Pixel config with a `Conversions API Token` field. On every successful order (`checkout.order.save.after`), fires a `Purchase` event to the Facebook Graph API v18.0 server-side endpoint. Hashes customer email/phone (SHA-256) per GDPR. Wired via `ShopEventServiceProvider → FacebookConversionsListener`.
- **Aramex Shipping Carrier** — new `Webkul/Aramex` package. Two-zone pricing (Cairo 40 EGP default / Outside Cairo 65 EGP default). Full Aramex ShippingAPI.V2 integration with `ClientInfo` auth. Auto-creates waybill on order save when `auto_create_shipment = true` and shipping method is `aramex_aramex`. Admin panel at `admin/aramex` shows waybill numbers linked to aramex.com tracker. Migration: `aramex_deliveries`.
- **Fawry Payment Gateway** — new `Webkul/Fawry` package. Supports both PAYATFAWRY (reference number shown at Fawry branch) and CARD (hosted Fawry page). SHA-256 signature verification on callback. Config: merchant_code, security_key, sandbox toggle, payment method selector. Migration: `fawry_transactions`.
- **SMS Notifications** — new `Webkul/SmsNotification` package. Sends SMS via Vonage (Nexmo) or Twilio on order placed, shipped, and delivered. Normalizes Egyptian phone numbers (+20 prefix). Per-event toggles in admin config. Config under Admin → Sales → SMS Notifications.
- **Loyalty Points** — new `Webkul/Loyalty` package. Customers earn points per EGP spent (configurable earn_rate). Points redeemable at checkout via Alpine.js widget in order summary (minimum redeem threshold enforced). Server-side atomic debit/credit with transaction log. Admin can manually adjust balances at Marketing → Loyalty Points. Migrations: `customer_loyalty_points`, `customer_loyalty_transactions`, + `loyalty_points_applied` on cart and orders.
- **Referral Program** — new `Webkul/Referral` package. Each customer gets a unique 8-char code. `/ref/{code}` stores code in session. On new customer registration, creates a pending conversion. On referee's first order, rewards referrer via wallet credit (configurable amount). Customer dashboard at `/referral/dashboard`. Admin overview at Marketing → Referral Program. Migrations: `customer_referrals`, `referral_conversions`.
- **Valu BNPL Payment** — new `Webkul/Valu` package. Egyptian Buy-Now-Pay-Later (تقسيط) via Valu hosted checkout. Payment only shown for orders >= configurable minimum (default 1000 EGP). Sandbox/production toggle. Migration: `valu_transactions`.
- **Product Comparison** — session-based, up to 4 products. Compare page at `/comparison` shows responsive table (image, name, price, description, view-product). Floating bottom bar shows selection count with "Compare Now" link. Global `addToCompareSession()` JS function for use in product cards.
- **Recently Viewed Products** — session-tracked on every product detail page visit (stores last 8, displays 6 excluding current). Rendered as a 6-column grid below the product detail page.
- **Wishlist Sharing** — customers can generate a shareable public URL from their wishlist. `share_token` added to customers table. Public view shows read-only wishlist. "Share Wishlist" button on wishlist page copies URL to clipboard.

## 2026-05-20 (UX Features: Product Comparison, Recently Viewed, Wishlist Sharing)

### Feature
- **Session-based Product Comparison** — `ComparisonController` with add/remove/clear/index actions. Session stores up to 4 product IDs. New `GET /comparison` page shows a responsive comparison table (image, name, price, short description, view-product link). `POST /comparison/add|remove|clear` routes handle mutations. Floating comparison bar in layout (fixed bottom) shows count and link once at least one product is added; JS function `addToCompareSession()` available globally.
- **Recently Viewed Products** — Tracked in session on every product detail page visit (up to 8 stored, top 6 excluding current displayed). Section rendered below main product content as a 6-column responsive grid with image, name, and price.
- **Wishlist Sharing** — Migration adds `share_token` (64-char, nullable, unique) to `customers` table. `WishlistShareController::generate()` creates a token on demand and returns a shareable URL; `view()` renders a public page of the customer's wishlist items. "Share Wishlist" button added to wishlist index page; clicking copies the URL to clipboard. Routes: `POST /customer/account/wishlist/share` (auth-guarded) and `GET /wishlist/shared/{token}` (public).

## 2026-05-20 (Valu BNPL Payment)

### Feature
- **Valu BNPL package** — new `Webkul/Valu` package integrating Valu Buy Now Pay Later for Egypt. Uses Valu's hosted checkout redirect approach (sandbox + production URLs configurable). `isAvailable()` enforces a configurable minimum order amount (default 1000 EGP) so the payment method only appears when the cart total qualifies. Transaction records persisted to `valu_transactions` table (order_id, merchant_ref, valu_ref, status, amount). Callback handles SUCCESS/failure redirect. Admin config at Sales → Payment Methods → Valu (merchant_id, sandbox toggle, min_amount, title). Registered in `bootstrap/providers.php` and `composer.json` autoload.

## 2026-05-20 (Loyalty Points)

### Feature
- **Loyalty Points package** — new `Webkul/Loyalty` package. Customers earn points on every order (configurable earn rate: points per currency unit). Points can be redeemed at checkout for a discount (configurable redeem rate and minimum threshold). Two new tables: `customer_loyalty_points` (balance ledger) and `customer_loyalty_transactions` (full audit trail). New columns `loyalty_points_applied` added to `cart` and `orders`. Shop checkout summary widget lets logged-in customers input points to apply or remove. Admin panel at Marketing → Loyalty Points lists all customer balances (paginated, sorted by balance) and allows admin to manually credit or debit points with a note. Listens to `checkout.order.save.after` to auto-earn points and process redemptions post-order. Registered in `bootstrap/providers.php` and `composer.json` autoload. Uses existing `general.loyalty.settings.*` config keys.

## 2026-05-20 (Referral Program)

### Feature
- **Referral Program package** — new `Webkul/Referral` package. Customers get a unique 8-character referral code; sharing `/ref/{code}` stores code in session. On new-customer registration the conversion is recorded in `referral_conversions`. On a referred customer's first order the referrer receives a wallet credit (amount and type configurable via existing `general.referral.settings.*` admin config). Two new tables: `customer_referrals` (code, times_used, total_earned) and `referral_conversions` (status: pending/rewarded). Shop dashboard at `/referral/dashboard` shows the shareable link, stats, and conversion history. Admin panel at Marketing → Referral Program lists all referrers with codes and totals. Listens to `checkout.order.save.after`, `customer.registration.after`, and `customer.after.create` events. Registered in `bootstrap/providers.php` and `composer.json` autoload.

## 2026-05-20 (Fawry Payment Gateway)

### Feature
- **Fawry payment package** — new `Webkul/Fawry` package integrating Fawry payment gateway for Egypt. Supports two payment modes: PAYATFAWRY (customer pays at any Fawry branch/kiosk using a printed reference number) and CARD (redirect to Fawry hosted card payment page). Signature generation and callback verification use SHA-256 per Fawry spec. All credentials (merchant_code, security_key, sandbox toggle, payment method) configurable per-channel in Admin → Configuration → Sales → Payment Methods → Fawry. Transactions persisted to `fawry_transactions` table with full API response logging. Registered in `bootstrap/providers.php` and `composer.json` autoload.

## 2026-05-20 (SMS Notifications)

### Feature
- **SMS Notifications package** — new `Webkul/SmsNotification` package: sends SMS to customers on three order events (placed, shipped, delivered) via Vonage (Nexmo) or Twilio. Phone normalisation handles Egyptian numbers (01x prefix → +20). All settings configurable per-channel in Admin → Configuration → Sales → SMS Notifications: enable/disable toggle, provider selector, provider credentials (API key/secret or Account SID/token), sender name, and per-event toggles. Registered in `bootstrap/providers.php` and `composer.json` autoload. No migrations required.

## 2026-05-20 (Aramex Shipping Integration)

### Feature
- **Aramex Shipping Carrier** — new `Webkul/Aramex` package: plugs into Bagisto's `carriers` config. Two-zone rate system: Greater Cairo (Cairo/Giza/Qalyubia) vs. Outside Cairo — defaults 40/65 EGP, configurable from Admin → Configuration → Sales → Shipping Methods → Aramex. API credentials (username, password, account_number, account_pin, account_entity) stored in admin config. `auto_create_shipment` toggle: when enabled, automatically calls Aramex `CreateShipments` API on `checkout.order.save.after`. Delivery records (aramex_id, waybill_number, status, raw response) stored in `aramex_deliveries` table. Admin can view all shipments with waybill tracking links and manually trigger creation at Settings → Aramex Shipments. Migration: `aramex_deliveries`.

## 2026-05-20 (Bosta Shipping Integration)

### Feature
- **Bosta Egyptian Shipping Carrier** — new `Webkul/Bosta` package: plugs into Bagisto's `carriers` config like all other shipping methods. Two-zone rate system: Greater Cairo (Cairo/Giza/Qalyubia) vs. Outside Cairo — both rates configurable from Admin → Configuration → Sales → Shipping Methods → Bosta. API key stored in admin config. `auto_create_shipment` toggle: when on, automatically creates a Bosta delivery via API on `checkout.order.save.after`. Bosta delivery records (ID, tracking number, status, raw response) stored in `bosta_deliveries` table. Admin can view all shipments and manually trigger creation at Settings → Bosta Shipments. Migration: `bosta_deliveries`.

## 2026-05-20 (Feature Expansion — Third Wave)

### Feature
- **Google Shopping Feed** — new `Webkul/GoogleShopping` package: RSS 2.0 XML feed at `/google-shopping.xml` with Google's `g:` product namespace. Queries `product_flat` with product images and category translations; handles special price date ranges; outputs properly formatted prices with currency code (e.g., "100.00 EGP"). 5,000 product limit, `Cache-Control: max-age=3600`.
- **Back in Stock Alerts** — subscribe form on out-of-stock product pages (already had UI + route + model). Added `php artisan stock:notify` command that checks `product_inventory_indices` for products with pending subscriptions, sends email (HTML Mailable) and WhatsApp notifications, then marks them notified. Scheduled every 30 minutes. Admin can view/delete subscriptions at Catalog → Stock Alert Subscriptions.
- **Flash Sales / Countdown Timer** — new `Webkul/FlashSale` package: admin creates time-limited sales (discount %, start datetime, end datetime, product selection). On activation, syncs `special_price` + date fields to `product_flat` so the existing pricing engine handles the discount automatically. `php artisan flash:sync` activates pending and expires finished sales (scheduled every 5 min). Countdown timer on product view page via Alpine.js. Flash Sale badge on product cards. Migration: `flash_sales`, `flash_sale_products`, plus `flash_sale_ends_at` column on `product_flat`.
- **Gift Cards** — new `Webkul/GiftCard` package: admin creates redeemable gift card codes with fixed balance (batch creation supported, up to 100 at once). Codes applied at checkout via a widget in the summary sidebar. `GiftCardOrderListener` deducts used amount from card balance on `checkout.order.save.after`. Migration: `gift_cards` table, plus `gift_card_code` + `gift_card_discount` columns on `cart` and `orders`.
- **Bulk Coupon Assignment by Phone** — confirmed complete (implemented in prior session): admin creates campaigns that assign unique auto-generated coupon codes to a list of phone numbers (CSV upload or manual entry). Each code is phone-locked via `cart_rule_coupon_assignments` — only a customer with the matching phone can redeem it. On submit, admin receives a downloadable CSV (phone|coupon) and WhatsApp template messages are auto-sent to each phone. Migrations: `cart_rule_coupon_assignments`, `coupon_assignment_campaigns`.

## 2026-05-20 (Feature Expansion — Second Wave)

### Feature
- **Blog / Articles System** — new `Webkul/Blog` package: full CRUD admin panel, published/draft status, category filtering, tags (JSON), featured images, SEO meta fields, reading time attribute. Shop front shows a responsive grid listing with category filter chips and a full article page with related posts. Migration: `blog_posts` table.
- **Store Credit / Wallet** — new `Webkul/Wallet` package: customer wallet balances stored in `customer_wallets`; every debit/credit logged in `customer_wallet_transactions` with balance snapshots. Admin can issue or revoke credit per customer. Wallet widget in checkout summary lets customers apply available credit to orders; deducted on `checkout.order.save.after`. Migrations: `customer_wallets`, `customer_wallet_transactions`, plus `store_credit_applied` column on `cart` and `orders`.
- **Browser Push Notifications** — new `Webkul/PushNotification` package: VAPID key generation via `php artisan push:generate-keys` (uses `phpseclib3` for Windows compatibility — works without native EC openssl support). Service worker at `/public/sw.js` handles push and notification-click events. Subscribers stored in `push_subscriptions`. Admin can send campaigns to all subscribers from Marketing panel. Migration: `push_subscriptions`, `push_campaigns`.
- **Paymob Egyptian Payment Gateway** — new `Webkul/Paymob` package: three-step Paymob Acceptance API flow (auth token → create order → get payment key → redirect to iframe). HMAC-SHA512 callback verification for webhook security. Configured from Admin → Configuration → Sales → Payment Methods → Paymob (API Key, Integration ID, iFrame ID, HMAC Secret). Creates order + invoice + transaction record on successful payment.
- **Affiliate / Influencer Program** — new `Webkul/Affiliate` package: affiliates register via `/affiliate/register`, admin approves/rejects applications. Each affiliate gets a unique tracking code; `/ref/{code}` records a click and sets session + cookie. Commission calculated on `checkout.order.save.after` using the affiliate's configurable rate. Admin can approve individual commissions and record payouts. Migrations: `affiliates`, `affiliate_clicks`, `affiliate_commissions`.
- **WhatsApp Chat Floating Button** — new config under Settings → General → Content → WhatsApp Chat. Configurable phone, pre-filled message, and position (left/right). Renders as a fixed floating button in the shop layout.

## 2026-05-20 (Full Feature Rollout)

### Feature
- **WhatsApp Order Notifications** — new `Webkul/OrderNotification` package: auto-send WhatsApp template messages on order placed (`checkout.order.save.after`), order shipped (`sales.shipment.save.after`), and order delivered (`sales.order.update-status.after → completed`). Template names configurable from Admin → Settings → Sales → Order Notifications.
- **Google Analytics GA4 + Google Tag Manager** — new config fields under Settings → General → Content. GA4 fires via `gtag.js`; GTM fires with noscript fallback. If GTM is enabled, GA4 is suppressed to avoid double-tracking.
- **COD Handling Fee** — new `extra_charge` field on the Cash on Delivery payment method config. Automatically added to cart grand total via `checkout.cart.collect.totals.after` listener and propagated to the order. Displayed as a separate line in checkout summary.
- **Public Order Tracking Page** — `/track-order` (no login required): customer enters order increment ID + email, sees order status with a visual progress bar (Placed → Processing → Delivered), shipment tracking number, and itemized order summary.
- **Low Stock Alerts** — fires on `catalog.product.update.after` (after every shipment): if stock drops to or below the configured threshold, sends an email to admin and a WhatsApp template message to configured admin phone. 6-hour cache prevents duplicate alerts per quantity level.
- **Product Q&A Section** — new `Webkul/ProductQA` package: customers submit questions on product pages (Alpine.js widget, no reload). Admin answers and publishes from Catalog → Product Q&A. Published Q&As render publicly on the product page below reviews. Migration: `product_questions` table.

## 2026-05-20 (Finance Module)

### Feature
- **Cost Management Module** — new `Webkul/CostManagement` package:
  - **Product Costs**: enter purchase price, manufacturing fee, shipping cost/unit, and other costs per product. Admin DataGrid shows selling price, total cost, profit/unit, and margin % color-coded (green ≥30%, yellow ≥10%, red <10%). Edit page has a live calculator that updates margin in real-time as you type.
  - **General Expenses**: CRUD for overhead expenses (rent, salaries, marketing, utilities, etc.) with one-time or recurring (weekly/monthly/yearly) frequency tracking.
  - **P&L Dashboard**: date-range report showing Revenue, COGS, Gross Profit, General Expenses, and Net Profit with a visual cost/profit bar. Includes a 12-month Chart.js bar+line chart and a Top 10 Products by Profit table. Quick range buttons: This Month / Last Month / This Year.
  - New tables: `product_costs`, `general_expenses`. Admin menu: Finance → P&L Dashboard / Product Costs / General Expenses.

## 2026-05-20 (Auto Financial Transactions)

### Feature
- **Auto-record financial transactions** in Cost Management:
  - Sales auto-recorded as `sale` transactions when `checkout.order.save.after` fires (deduplication by reference_id prevents double entries).
  - Refunds auto-recorded as `refund` transactions (negative amount) when `sales.refund.save.after` fires.
  - Ad Spend manually logged via the P&L Dashboard modal (POST `/admin/cost-management/ad-spend`) with platform, description, amount, and date.
  - P&L Dashboard now shows 5 KPI cards (Revenue, COGS, Ad Spend, General Expenses, Net Profit), recent transaction feed with type badges, and an Ad Spend quick-entry modal.
  - New table: `financial_transactions`. New routes: POST/DELETE `/admin/cost-management/ad-spend`.

## 2026-05-20 (continued)

### Feature
- Product card badges extended: added **Low Stock** (orange, ≤5 units) and **Popular** (green, ≥10 ratings) badges alongside the existing Sale/New badges. All four stack vertically on the card image. `stock_qty` added to `ProductResource`.

## 2026-05-20

### Feature
- Conversion optimization — high impact missing features:
  - **WhatsApp Floating Button**: configurable phone number + pre-filled message; appears on every shop page. Toggle + config from Admin → Configure → General → Design → WhatsApp Button.
  - **Newsletter Popup**: timed email capture popup (configurable delay, title, subtitle, button text) with sessionStorage de-dup and localStorage "already subscribed" guard. Stores to existing `subscribers_list` table via `shop.subscription.store`.
  - **Back in Stock Notification**: when a product's stock is zero, a "Notify Me" form replaces the Add to Cart area. Email stored in new `stock_notifications` table; ready for WhatsApp/email dispatch when restocked.
  - **Checkout Trust Badges**: 4 SVG badges (Secure Checkout, Easy Returns, Quality Guaranteed, Fast Delivery) injected into the checkout order summary sidebar.
  - **Buy Now button** enabled by default (was config-gated with null = off; changed to null = on).
  - **Schema.org / Rich Snippets** enabled by default for all product pages (product JSON-LD for Google search enrichment).
  - **CSRF meta tag** added to shop layout `<head>` for all AJAX requests.

### Feature
- Conversion optimization — medium impact features:
  - **Quick View**: hover button on product cards opens a modal with image, name, price, and Add to Cart / View Full Details without leaving the listing page.
  - **Urgency in Cart**: each cart item now shows a low-stock badge ("Only X left in stock" orange, "Only X left — order soon!" red for ≤3 units). Stock quantity added to `CartItemResource`.
  - **Post-Purchase Upsell**: order success page shows a "You May Also Like" grid of up to 4 upsell/related products from the purchased items, encouraging repeat purchases immediately after checkout.
  - **Cross-Sell Carousel** enabled by default in cart (was config-gated null=off; changed to null=on).
  - **GA4 Ecommerce Events**: `view_item`, `add_to_cart` (fetch interceptor), `begin_checkout`, `purchase` events wired to gtag.js — compatible with the existing GA4 measurement ID config.
  - **Loyalty Points System**: customers earn points on every order (configurable rate). Points are redeemable in the cart via a widget that generates a one-use coupon code. Full account dashboard at `/customer/account/loyalty`. Points and transactions tracked in 3 new DB tables (`customer_loyalty_points`, `customer_loyalty_transactions`, `loyalty_redemptions`). Admin config under General → Loyalty Points.
  - **Referral Program**: each customer gets a unique referral code/link. Referred customer's first order triggers an automatic coupon reward for the referrer. Account dashboard at `/customer/account/referral` with clipboard copy and WhatsApp/Facebook share. New `customer_referrals` DB table. Admin config under General → Referral Program.
  - **Phone-Restricted Coupon Assignments**: new admin feature (Marketing → Promotions → Coupon Assignments) allowing bulk assignment of unique coupons to phone numbers via CSV upload or manual entry. Assigned coupons can only be redeemed by customers with the matching phone number.

## 2026-05-16

### Feature
- Added `packages/Webkul/AiSupport` — full AI customer service module using Claude (Anthropic API). Supports Web Chat widget, WhatsApp, Messenger, and Email channels. Admins can configure system prompt, manage a Knowledge Base, review/edit AI replies before sending, and hand off conversations to human agents. Includes 3 new DB tables (`ai_support_conversations`, `ai_support_messages`, `ai_support_knowledge_base`), DataGrids for admin panel, floating chat bubble injected into shop layout, and webhook endpoints for Meta platforms.

## 2026-05-14

### Fix
- AbandonedCart admin view: replaced legacy `@extends('admin::layouts.master')` with the Bagisto 2.x component-based layout `<x-admin::layouts>` to fix 500 error on `/admin/abandoned-carts`.

## 2026-05-13

### Feature
- Shop The Look: added `Webkul\ShopTheLook` package. Admin can assign "look products" (e.g. matching outfit items) to any product from the product edit page sidebar. Shop product page shows a "Complete The Look" section with item images, prices, and checkboxes; clicking "Add Selected to Cart" adds all checked items in one shot. Routes, migration (`product_look_items`), model, service provider, admin AJAX panel, and shop section all included.

### Feature
- Ads & Tracking Health: added automatic health-check dashboard under Marketing → Ads & Tracking Health. Checks 18 items across Tracking Pixels, Meta Tags, Structured Data, and Technical SEO. Shows overall score, per-category breakdown, priority badges, and direct fix links.


### Feature
- Size Guide: admins can now rename every column header in the size chart table. Added `column_headers` JSON column to `size_charts`; admin form renders each `<th>` as an editable input; shop size-guide page uses stored headers with sensible defaults.

## 2026-05-09

### Improvement
- Size Guide modal: added three missing translation keys (`disclaimer`, `body-charts`, `product-chart`) to English and Arabic lang files. Fixed `sg-content` flex layout — removed Tailwind `hidden` class and replaced classList toggling with `style.display='flex'/'none'` so the flexbox column layout renders correctly when chart data loads.

### Feature
- Added `Webkul\SizeGuide` package: complete size guide system. Admin dashboard (Catalog → Size Guide) to create/edit/delete size charts with per-size measurement rows (EU/UK/US labels, chest/waist/hips/height min-max ranges, product garment measurements). Product edit page sidebar shows a "Size Guide" panel to assign a chart to any product. Shop-side: a "Size Guide" link appears on product detail pages that have an assigned chart; clicking it opens a modal with a Size Guide tab (filterable by EU/UK/US system, CM/IN toggle, full measurements table) and a "Find My Size" tab (customer enters chest/waist/hips, system recommends best-fit size by closest-midpoint scoring).

### Fix
- Fixed "Select All" checkbox and all interactive controls on Egypt Shipping admin page not responding. Root cause: Bagisto's admin layout calls `app.mount('#app')` on the same `load` event as the pushed scripts, so Vue's mount replaced the DOM nodes after event listeners were bound. Rewrote all JS to use event delegation on `document` so listeners survive Vue's mount.

## 2026-05-08

### Feature
- Added new `Webkul\EgyptShipping` package providing Egypt-specific shipping by governorate. Includes: `egypt_shipping_governorates` table seeded with all 27 Egyptian governorates, an `Egypt` shipping carrier that reads `state` from the cart address and returns the configured rate (or `false` when governorate is inactive or has no rate), an Admin dashboard under Settings → Egypt Shipping with per-governorate rate inputs and active toggles saved via AJAX, and a public customer order-tracking page at `/track-order` (no login required) where customers enter order number + email to see a status badge, 4-step timeline, order items, shipping info, and order totals.

### Feature
- Added Inventory Adjustments importer in DataTransfer: upload a CSV/XLSX with columns `sku`, `source_code`, `qty` to set or reset product inventory quantities per source. Registers as `inventory_adjustments` importer type, validates SKU existence and source code, dispatches inventory reindex job after import.

### Feature
- Added Sales Channels dashboard section: per-channel orders count, revenue, ad spend, ROAS, and cost-per-order. Includes `channel_ad_spends` table, `ChannelAdSpend` model/repository, CRUD API (`/admin/channel-ad-spends`), and inline modal to add/edit/delete ad spend entries directly from the dashboard.

### Feature
- Added new `Webkul\SocialCommerce` package with full social commerce integration for Facebook, Instagram, TikTok, YouTube, and WhatsApp. Includes: per-channel platform configuration with encrypted API credentials (App ID, App Secret, Access Token), pixel injection via event listeners (TikTok Pixel + Google Analytics 4 / YouTube), product catalog sync to Meta Catalog API (Facebook/Instagram/WhatsApp), TikTok for Business Catalog API, and Google Merchant Center API, queued `SyncProductsToPlatform` and `ProcessIncomingOrder` jobs, webhook endpoints for all 5 platforms with CSRF exemption and verification token support, and an Admin UI under Settings → Social Commerce with DataGrid + create/edit forms.

### Feature
- Added new `Webkul\AbandonedCart` package implementing a complete abandoned cart recovery and fraud prevention system. Includes: real-time cart activity tracking (`last_activity_at`, `notification_token`, `recovery_status`), multi-channel outreach (email, WhatsApp Cloud API, Facebook Messenger), configurable retry logic with per-attempt delays, guest cart session restoration via signed URLs and a long-lived `bagisto_cart_recovery` cookie, heuristic fraud scoring (`FraudScoringService`) that auto-flags high-risk orders to `status=fraud` before save, an Admin DataGrid under Sales → Abandoned Carts, and a `php artisan abandoned-cart:process` command scheduled every 15 minutes. All settings configurable from Admin → Configuration → Sales → Abandoned Cart Recovery.
- Added "Auto Assign by Color" feature to configurable product variants: uploading images with a color name in the filename (e.g. `black.jpg`) automatically distributes them to all variants matching that color across all sizes, without manual variant selection.
- Added new standalone `Webkul\BulkDeal` package implementing a "Paid Qty + Deal Qty for Fixed Price" promotion type. The most expensive `paid_quantity` items in the cart are charged at full price; the cheapest `deal_quantity` items are discounted to a fixed `deal_price` total. Managed via Admin → Marketing → Promotions → Bulk Deals with full CRUD UI.

## **v2.4.4 (5th of May 2026)** - *Release*

* Fixed wrong "From" and "To" dates on the admin Bookings data grid and calendar view caused by the Carbon 3 timezone behavior change in the Laravel 12 upgrade. `Carbon::createFromTimestamp()` now returns UTC by default instead of the app timezone, so the booking timestamps are explicitly converted via `->timezone(config('app.timezone'))` in `BookingDataGrid` and `BookingController`.

* Optimized cart rule evaluation to reduce repeated database lookups during cart total calculation, improving cart and checkout performance.

* Refined the admin cart-rule create/edit pages with a clearer Coupon section, a context-aware Actions card, and a dedicated Generated Coupons datagrid with a modal-based bulk-code generator.

* Refined the storefront cart and onepage checkout summaries with `+` / `−` indicators, a collapsed dual tax-mode display, an expandable Discount breakdown, and a modernized applied-coupon pill.

* #10832 [feature] - Added a "Sales By Coupon" report to the admin sales reporting dashboard, with a coupon-code badge linking to the corresponding cart rule edit page and a drill-down "View Details" listing showing each order that used a coupon (order ID linking to the order detail, coupon code linking to the cart rule).

* #8738 [fixed] - Added column sorting on every reporting list page (Sales / Customers / Products) with sort direction indicators in the column header, fixing the previously non-functional click target.

## **v2.4.3 (24th of April 2026)** - *Release*

* Ported all booking product bug fixes from the 2.3 branch into 2.4. Key highlights:
  - Added admin-side order creation support for booking products across appointment, event, rental, default, and table sub-types.
  - Fixed booking slot overlap detection and corrected the calendar window generation for appointment bookings.
  - Fixed display pricing for rental and event sub-types with a "starting from" price on listings and corrected strike-through pricing.
  - Hardened cart handling for booking items (quantity updates, missing-ticket guards, inverted rental range checks).
  - Fixed booking product import by updating the data-transfer sample files and correcting the importer for booking attributes.

## **v2.4.2 (13th of April 2026)** - *Release*

* Added support for Romanian language.

* Fixed product 404 when locale-specific URL keys differ across locales by adding cross-locale fallback in product slug resolution and locale-aware URL rewrite redirects.

* Upgraded image search to support AI-powered analysis via Laravel AI SDK (MagicAI), with TensorFlow.js as the default fallback. Configurable under Magic AI > Storefront Features > AI Image Search.

* Added Base URL configuration field for Ollama provider in Magic AI settings.

* Fixed RMA rules issues where inactive rules were still selectable on the product create/edit form, and where the "Create" modal would update the last-edited rule after an edit modal had been opened.

* #11220 [fixed] - Fixed SQL injection in DataGrid sort column and unauthenticated path traversal via ImageCache.

* #11212 [fixed] - Fixed TypeError in Carbon when RESPONSE_CACHE_ENABLED is enabled.

* #11013 [fixed] - Fixed an issue where the order date range filter accepted a single date input and returned no results.

## **v2.4.1 (23rd of March 2026)** - **Release**

* Fixed an issue where the price slider was not displaying on the layered navigation.

* Fixed an issue where static content was pointing to demo categories and giving 404 errors when installed without sample products.

* #11207 [fixed] - Performed a major update and cleanup of Polish translations across both Admin and Shop sections.

* #10792 [feature] - Added Cache Management in Admin Configuration panel.

## **v2.4.0 (19th of March 2026)** - **Release**

### New Features

* **[Laravel 12 Upgrade]** Upgraded framework to Laravel 12 with comprehensive modernization:
  - Fixed Carbon date/time type strictness issues (int/float parameters, non-null timezones).
  - Modernized all legacy PHP date functions (`strtotime()`, `date()`, `date_default_timezone_set()`) to Carbon equivalents.
  - Implemented timezone fallback logic using `config('app.timezone')` for channel-based operations.
  - Updated PDF response headers to match Laravel 12 format (Content-Disposition).
  - Enhanced date handling methods in Core helper with proper Carbon integration.

* Implemented two-factor authentication (2FA) for admin users to enhance account security.

* Migrated from Google reCAPTCHA v2 to Google reCAPTCHA Enterprise for enhanced bot protection.

* Added Stripe payment gateway integration with secure checkout session.

* Added Razorpay payment gateway integration with drop-in UI checkout experience.

* Added PayU payment gateway integration with redirect-based checkout flow.

* Upgraded PayPal SDK from abandoned v1 to modern v2 with improved reliability and security. Refactored PayPal integration to use controller-based transaction handling and modernized IPN processing with Laravel HTTP client.

* Added comprehensive Return Merchandise Authorization (RMA) system with complete order return management.

* Integrated Laravel AI SDK for Magic AI, refactoring the provider and model layer into per-provider enums with a unified `AiProvider` entry point and updated AI model configurations.

* Added fresh demo products during the installation process with updated translations.

* Added Pest and Playwright test cases.

* #11126 [feature] - Added SMTP configuration support from the admin panel.

### Changes

* Removed `shetabit/visitor` package and all visitor tracking functionality including dashboard visitors widget, products with most visits reporting, customers traffic reporting, and purchase funnel visitor metrics.

### Bug Fixes

* Included all bug fix updates from version 2.3.

* Optimized RMA-related queries and introduced a return period column in the order items table.

* Fixed issues with language switching in the installation wizard and corrected PHP configuration texts.

* Fixed automatic application URL detection and automatic timezone selection during installation.

* Fixed backend validation and VeeValidate error handling to ensure proper integration with Laravel backend validation in the installer package.

* #11100 [fixed] - Fixed an issue where updating the return window rule affected previously placed orders.

### Documentation

* Updated the upgrade guide (UPGRADE.md) with breaking changes from v2.3 including Laravel 12, reCAPTCHA Enterprise, PayPal SDK upgrade, visitor tracking removal, and Magic AI SDK migration.

## **v2.4.0-beta6 (18th of March 2026)** - **Release**

* Removed `shetabit/visitor` package and all visitor tracking functionality including dashboard visitors widget, products with most visits reporting, customers traffic reporting, and purchase funnel visitor metrics.

* Updated the upgrade guide (UPGRADE.md) with breaking changes from v2.3 including Laravel 12, reCAPTCHA Enterprise, PayPal SDK upgrade, visitor tracking removal, and Magic AI SDK migration.

* Rewrote AGENTS.md with accurate codebase documentation covering architecture, conventions, commands, and development guidelines.

## **v2.4.0-beta5 (18th of March 2026)** - **Release**

* Included bug fix updates from version 2.3.

## **v2.4.0-beta4 (5th of March 2026)** - **Release**

* Enhanced the Laravel AI SDK integration for Magic AI and improved the related configuration sections.

* Updated all outdated AI models and image model configurations.

## **v2.4.0-beta3 (3rd of March 2026)** - **Release**

* Integrated Laravel AI SDK for Magic AI, refactoring the provider and model layer into per-provider enums with a unified `AiProvider` entry point.

* #11126 [feature] - Added SMTP configuration support from the admin panel.

* Merged all bug fixes and improvements from version 2.3.

* Added pest and playwright testcases.

## **v2.4.0-beta2 (17th of February 2026)** - **Release**

* Updated the translations for all the dummy products.

* Optimized RMA-related queries and introduced a return period column in the order items table.

* Fixed issues with language switching in the installation wizard and corrected PHP configuration texts.

* Fixed automatic application URL detection and automatic timezone selection during installation.

* Fixed backend validation and VeeValidate error handling to ensure proper integration with Laravel backend validation in the installer package.

* #11100 [fixed] - Fixed an issue where updating the return window rule affected previously placed orders.

## **v2.4.0-beta1 (9th of February 2026)** - **Release**

* **[Laravel 12 Upgrade]** Upgraded framework to Laravel 12 with comprehensive modernization:
  - Fixed Carbon date/time type strictness issues (int/float parameters, non-null timezones).
  - Modernized all legacy PHP date functions (`strtotime()`, `date()`, `date_default_timezone_set()`) to Carbon equivalents.
  - Implemented timezone fallback logic using `config('app.timezone')` for channel-based operations.
  - Updated PDF response headers to match Laravel 12 format (Content-Disposition).
  - Enhanced date handling methods in Core helper with proper Carbon integration.

* Implemented two-factor authentication (2FA) for admin users to enhance account security.

* Migrated from Google reCAPTCHA v2 to Google reCAPTCHA Enterprise for enhanced bot protection.

* Added Stripe payment gateway integration with secure checkout session.

* Added Razorpay payment gateway integration with drop-in UI checkout experience.

* Added PayU payment gateway integration with redirect-based checkout flow.

* Upgraded PayPal SDK from abandoned v1 to modern v2 with improved reliability and security. Refactored PayPal integration to use controller-based transaction handling and modernized IPN processing with Laravel HTTP client.

* Added comprehensive Return Merchandise Authorization (RMA) system with complete order return management.

* Added fresh demo products during the installation process.
