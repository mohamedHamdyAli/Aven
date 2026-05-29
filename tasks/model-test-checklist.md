# Model Test Checklist

> **163 models** across 50 packages. Each checkbox represents one test case.
> Legend: `[ ]` = not tested · `[x]` = tested · `[-]` = N/A (no factory, skip for now)

---

## Progress: 163 / 163 tested

---

## Aven Custom Packages

---

### Package: AbandonedCart

#### AbandonedCartNotification
| Feature | Test | Status |
|---------|------|--------|
| Fillable: cart_id, channel, attempt_number, status, sent_at, opened_at, clicked_at, error_message | `allows mass assignment of fillable fields` | [x] |
| Cast: sent_at → datetime | `casts sent_at to datetime` | [x] |
| Cast: opened_at → datetime | `casts opened_at to datetime` | [x] |
| Cast: clicked_at → datetime | `casts clicked_at to datetime` | [x] |
| Cast: created_at → datetime | `casts created_at to datetime` | [x] |
| Relationship: cart() BelongsTo CartProxy | `cart() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

---

### Package: Affiliate

#### Affiliate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, name, email, code, status, commission_rate, total_earned, total_paid, notes | `allows mass assignment of fillable fields` | [x] |
| Cast: commission_rate → decimal:2 | `casts commission_rate to float` | [x] |
| Cast: total_earned → decimal:4 | `casts total_earned to float` | [x] |
| Cast: total_paid → decimal:4 | `casts total_paid to float` | [x] |
| Relationship: commissions() HasMany AffiliateCommission | `commissions() returns HasMany instance` | [x] |
| Relationship: clicks() HasMany AffiliateClick | `clicks() returns HasMany instance` | [x] |
| Method: generateCode() returns unique 8-char code | `generateCode() generates a unique code` | [x] |
| Method: pendingBalance() calculates pending commissions | `pendingBalance() returns correct pending balance` | [x] |

**Factory:** ✅ (created)

#### AffiliateClick
| Feature | Test | Status |
|---------|------|--------|
| Fillable: affiliate_id, ip, url | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅ (created)

#### AffiliateCommission
| Feature | Test | Status |
|---------|------|--------|
| Fillable: affiliate_id, order_id, order_total, commission, status | `allows mass assignment of fillable fields` | [x] |
| Cast: order_total → decimal:4 | `casts order_total to float` | [x] |
| Cast: commission → decimal:4 | `casts commission to float` | [x] |
| Relationship: affiliate() BelongsTo Affiliate | `affiliate() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

---

### Package: AiSupport

#### AiConversation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: channel, channel_identifier, customer_id, status, assigned_admin_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: messages() HasMany AiMessage (ordered) | `messages() returns HasMany instance` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |
| Relationship: assignedAdmin() BelongsTo Admin | `assignedAdmin() returns BelongsTo instance` | [x] |
| Method: isOpen() returns true when status = 'open' | `isOpen() returns true for open conversations` | [x] |
| Method: isOpen() returns false otherwise | `isOpen() returns false for non-open conversations` | [x] |
| Method: isHandedOff() returns true when status = 'human_handoff' | `isHandedOff() returns true on human handoff` | [x] |

**Factory:** ✅ (created)

#### AiKnowledgeBase
| Feature | Test | Status |
|---------|------|--------|
| Fillable: question, answer, is_active, sort_order | `allows mass assignment of fillable fields` | [x] |
| Cast: is_active → boolean | `casts is_active to boolean` | [x] |

**Factory:** ✅ (created)

#### AiMessage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: conversation_id, role, content, ai_draft, status, sent_at | `allows mass assignment of fillable fields` | [x] |
| Cast: sent_at → datetime | `casts sent_at to datetime` | [x] |
| Relationship: conversation() BelongsTo AiConversation | `conversation() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

---

### Package: Blog

#### BlogPost
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, slug, content, excerpt, featured_image, category, tags, status, meta_title, meta_description, author_id, published_at | `allows mass assignment of fillable fields` | [x] |
| Cast: tags → array | `casts tags to array` | [x] |
| Cast: published_at → datetime | `casts published_at to datetime` | [x] |
| Scope: published() → status=published AND published_at <= now | `scopePublished returns only published posts` | [x] |
| Method: generateSlug() creates unique slug from title | `generateSlug() produces a slug from title` | [x] |
| Accessor: reading_time returns word-count / 200 | `getReadingTimeAttribute() returns integer minutes` | [x] |

**Factory:** ✅ (created)

---

### Package: BulkDeal

#### BulkDeal
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, status, paid_quantity, deal_quantity, deal_price, starts_from, ends_till, sort_order | `allows mass assignment of fillable fields` | [x] |
| Cast: status → boolean | `casts status to boolean` | [x] |
| Cast: deal_price → float | `casts deal_price to float` | [x] |
| Cast: starts_from → datetime | `casts starts_from to datetime` | [x] |
| Cast: ends_till → datetime | `casts ends_till to datetime` | [x] |

**Factory:** ✅ (created)

---

### Package: CostManagement

#### FinancialTransaction
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, amount, description, platform, reference_id, reference_type, transaction_date | `allows mass assignment of fillable fields` | [x] |
| Cast: amount → float | `casts amount to float` | [x] |
| Cast: transaction_date → date | `casts transaction_date to date` | [x] |
| Method: typeLabels() returns label map | `typeLabels() returns array with sale, refund, expense, ad_spend keys` | [x] |
| Method: platforms() returns platform list | `platforms() returns non-empty array of platforms` | [x] |

**Factory:** ✅ (created)

#### GeneralExpense
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, category, amount, expense_date, is_recurring, frequency, notes | `allows mass assignment of fillable fields` | [x] |
| Cast: expense_date → date | `casts expense_date to date` | [x] |
| Cast: is_recurring → boolean | `casts is_recurring to boolean` | [x] |
| Cast: amount → float | `casts amount to float` | [x] |
| Method: categories() returns label map | `categories() returns non-empty array of categories` | [x] |

**Factory:** ✅ (created)

#### ProductCost
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, cost_price, manufacturing_fee, shipping_cost_per_unit, other_costs, notes | `allows mass assignment of fillable fields` | [x] |
| Cast: cost_price → float | `casts cost_price to float` | [x] |
| Cast: manufacturing_fee → float | `casts manufacturing_fee to float` | [x] |
| Cast: shipping_cost_per_unit → float | `casts shipping_cost_per_unit to float` | [x] |
| Cast: other_costs → float | `casts other_costs to float` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Accessor: total_cost = sum of all cost components | `getTotalCostAttribute() sums all costs correctly` | [x] |

**Factory:** ✅ (created)

#### ProfitDistribution
| Feature | Test | Status |
|---------|------|--------|
| Fillable: period_from, period_to, net_profit, total_distributed, notes | `allows mass assignment of fillable fields` | [x] |
| Cast: period_from → date | `casts period_from to date` | [x] |
| Cast: period_to → date | `casts period_to to date` | [x] |
| Cast: net_profit → float | `casts net_profit to float` | [x] |
| Cast: total_distributed → float | `casts total_distributed to float` | [x] |
| Relationship: items() HasMany ProfitDistributionItem | `items() returns HasMany instance` | [x] |

**Factory:** ✅ (created)

#### ProfitDistributionItem
| Feature | Test | Status |
|---------|------|--------|
| Fillable: distribution_id, shareholder_id, percentage, amount | `allows mass assignment of fillable fields` | [x] |
| Cast: percentage → float | `casts percentage to float` | [x] |
| Cast: amount → float | `casts amount to float` | [x] |
| Relationship: shareholder() BelongsTo Shareholder | `shareholder() returns BelongsTo instance` | [x] |
| Relationship: distribution() BelongsTo ProfitDistribution | `distribution() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

#### Shareholder
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, email, phone, percentage, active, notes, joined_at | `allows mass assignment of fillable fields` | [x] |
| Cast: active → boolean | `casts active to boolean` | [x] |
| Cast: joined_at → date | `casts joined_at to date` | [x] |
| Cast: percentage → float | `casts percentage to float` | [x] |
| Relationship: distributionItems() HasMany ProfitDistributionItem | `distributionItems() returns HasMany instance` | [x] |
| Method: totalEarned() sums all distribution items | `totalEarned() returns correct cumulative amount` | [x] |

**Factory:** ✅ (created)

---

### Package: EgyptShipping

#### EgyptGovernorate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name_ar, name_en, rate, is_active | `allows mass assignment of fillable fields` | [x] |
| Cast: rate → decimal:2 | `casts rate to float` | [x] |
| Cast: is_active → boolean | `casts is_active to boolean` | [x] |

**Factory:** ✅ (created)

---

### Package: FlashSale

#### FlashSale
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, discount_percent, starts_at, ends_at, active | `allows mass assignment of fillable fields` | [x] |
| Cast: starts_at → datetime | `casts starts_at to datetime` | [x] |
| Cast: ends_at → datetime | `casts ends_at to datetime` | [x] |
| Cast: active → boolean | `casts active to boolean` | [x] |
| Cast: discount_percent → float | `casts discount_percent to float` | [x] |
| Relationship: products() BelongsToMany Product | `products() returns BelongsToMany instance` | [x] |
| Method: isRunning() returns true when active & within date range | `isRunning() returns true for active running sale` | [x] |
| Method: isRunning() returns false when outside date range | `isRunning() returns false when sale has ended` | [x] |
| Method: isRunning() returns false when not active | `isRunning() returns false when sale is inactive` | [x] |

**Factory:** ✅ (created)

---

### Package: GiftCard

#### GiftCard
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, initial_balance, used_amount, is_active, recipient_email, recipient_name, expires_at, message | `allows mass assignment of fillable fields` | [x] |
| Cast: initial_balance → float | `casts initial_balance to float` | [x] |
| Cast: used_amount → float | `casts used_amount to float` | [x] |
| Cast: is_active → boolean | `casts is_active to boolean` | [x] |
| Cast: expires_at → date | `casts expires_at to date` | [x] |
| Accessor: remaining_balance = max(0, initial - used) | `getRemainingBalanceAttribute() returns correct balance` | [x] |
| Accessor: remaining_balance never goes below 0 | `getRemainingBalanceAttribute() floors at zero` | [x] |
| Method: isUsable() returns true when active, has balance, not expired | `isUsable() returns true for valid gift card` | [x] |
| Method: isUsable() returns false when expired | `isUsable() returns false for expired gift card` | [x] |
| Method: isUsable() returns false when balance is 0 | `isUsable() returns false when balance depleted` | [x] |
| Method: generateCode() returns XXX-XXXX-XXXX format | `generateCode() generates code in correct format` | [x] |
| Method: generateCode() returns unique codes | `generateCode() generates unique codes` | [x] |

**Factory:** ✅ (created)

---

### Package: Loyalty

#### CustomerLoyaltyPoints
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, balance | `allows mass assignment of fillable fields` | [x] |
| Relationship: transactions() HasMany CustomerLoyaltyTransaction | `transactions() returns HasMany instance` | [x] |

**Factory:** ✅ (created)

#### CustomerLoyaltyTransaction
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, order_id, type, points, balance_after, description | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅ (created)

---

### Package: ProductQA

#### ProductQuestion
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, customer_id, customer_name, customer_email, question, answer, status, is_published | `allows mass assignment of fillable fields` | [x] |
| Cast: is_published → boolean | `casts is_published to boolean` | [x] |
| Scope: published() → status=approved AND is_published=true | `scopePublished returns only approved published questions` | [x] |
| Scope: published() excludes unapproved | `scopePublished excludes unapproved questions` | [x] |

**Factory:** ✅ (created)

---

### Package: PushNotification

#### PushSubscription
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, endpoint, p256dh, auth | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅ (created)

#### PushCampaign
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, body, icon, url, sent_count | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅ (created)

---

### Package: Referral

#### CustomerReferral
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, referral_code, times_used, total_earned | `allows mass assignment of fillable fields` | [x] |
| Cast: total_earned → decimal:4 | `casts total_earned to float` | [x] |
| Cast: times_used → integer | `casts times_used to integer` | [x] |

**Factory:** ✅ (created)

#### ReferralConversion
| Feature | Test | Status |
|---------|------|--------|
| Fillable: referral_code, referrer_customer_id, referred_customer_id, referred_email, order_id, status, rewarded_at | `allows mass assignment of fillable fields` | [x] |
| Cast: rewarded_at → datetime | `casts rewarded_at to datetime` | [x] |

**Factory:** ✅ (created)

---

### Package: ShopTheLook

#### ProductLookItem
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, look_product_id, sort_order | `allows mass assignment of fillable fields` | [x] |
| Relationship: lookProduct() BelongsTo Product | `lookProduct() returns BelongsTo instance` | [x] |
| No timestamps (timestamps = false) | `model has no timestamps` | [x] |

**Factory:** ✅ (created)

---

### Package: SizeGuide

#### SizeChart
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, gender, type, image, image_overlays, column_headers | `allows mass assignment of fillable fields` | [x] |
| Cast: image_overlays → array | `casts image_overlays to array` | [x] |
| Cast: column_headers → array | `casts column_headers to array` | [x] |
| Relationship: rows() HasMany SizeChartRow (ordered by sort_order) | `rows() returns HasMany instance` | [x] |
| Relationship: products() BelongsToMany Product | `products() returns BelongsToMany instance` | [x] |

**Factory:** ✅ (created)

#### SizeChartRow
| Feature | Test | Status |
|---------|------|--------|
| Fillable: size_chart_id, label, sort_order, eu_size, uk_size, us_size, chest/waist/hips/height ranges, product measurements | `allows mass assignment of fillable fields` | [x] |
| Casts: all measurement fields → float | `casts measurement fields to float` | [x] |
| Method: range($field) returns "min-max" or single value | `range() returns formatted range string` | [x] |
| Method: range($field) returns null when both values null | `range() returns null when values are null` | [x] |
| Static: toIn($cm) converts cm to inches (÷2.54) | `toIn() converts centimeters to inches correctly` | [x] |

**Factory:** ✅ (created)

---

### Package: SocialCommerce

#### SocialChannelPlatform
| Feature | Test | Status |
|---------|------|--------|
| Fillable: channel_id, platform, is_active, page_url, page_id, pixel_id, app_id, app_secret, access_token, catalog_id, phone_number_id, last_synced_at | `allows mass assignment of fillable fields` | [x] |
| Cast: is_active → boolean | `casts is_active to boolean` | [x] |
| Cast: last_synced_at → datetime | `casts last_synced_at to datetime` | [x] |
| Cast: app_id → encrypted | `casts app_id to encrypted` | [x] |
| Cast: app_secret → encrypted | `casts app_secret to encrypted` | [x] |
| Cast: access_token → encrypted | `casts access_token to encrypted` | [x] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [x] |
| Relationship: productSyncs() HasMany SocialProductSync | `productSyncs() returns HasMany instance` | [x] |
| Relationship: orders() HasMany SocialOrder | `orders() returns HasMany instance` | [x] |

**Factory:** ✅ (created)

#### SocialOrder
| Feature | Test | Status |
|---------|------|--------|
| Fillable: social_channel_platform_id, order_id, external_order_id, platform_data, sync_status, error_message | `allows mass assignment of fillable fields` | [x] |
| Cast: platform_data → array | `casts platform_data to array` | [x] |
| Relationship: platform() BelongsTo SocialChannelPlatform | `platform() returns BelongsTo instance` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

#### SocialProductSync
| Feature | Test | Status |
|---------|------|--------|
| Fillable: social_channel_platform_id, product_id, external_product_id, sync_status, error_message, synced_at | `allows mass assignment of fillable fields` | [x] |
| Cast: synced_at → datetime | `casts synced_at to datetime` | [x] |
| Relationship: platform() BelongsTo SocialChannelPlatform | `platform() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

---

### Package: StoreLocator

#### StoreLocator
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, address, latitude, longitude, phone, working_hours, image, status | `allows mass assignment of fillable fields` | [x] |
| Cast: working_hours → array | `casts working_hours to array` | [x] |
| Cast: status → boolean | `casts status to boolean` | [x] |
| Cast: latitude → float | `casts latitude to float` | [x] |
| Cast: longitude → float | `casts longitude to float` | [x] |

**Factory:** ✅ (created)

---

### Package: Wallet

#### CustomerWallet
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, balance | `allows mass assignment of fillable fields` | [x] |
| Cast: balance → decimal:4 | `casts balance to float` | [x] |
| Relationship: transactions() HasMany CustomerWalletTransaction | `transactions() returns HasMany instance` | [x] |

**Factory:** ✅ (created)

#### CustomerWalletTransaction
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, order_id, type, amount, balance_after, note | `allows mass assignment of fillable fields` | [x] |
| Cast: amount → decimal:4 | `casts amount to float` | [x] |
| Cast: balance_after → decimal:4 | `casts balance_after to float` | [x] |

**Factory:** ✅ (created)

---

## Core Bagisto Packages

---

### Package: Admin

#### ChannelAdSpend
| Feature | Test | Status |
|---------|------|--------|
| Fillable: channel_id, amount, start_date, end_date, source, notes | `allows mass assignment of fillable fields` | [x] |
| Cast: amount → decimal:4 | `casts amount to float` | [x] |
| Cast: start_date → date | `casts start_date to date` | [x] |
| Cast: end_date → date | `casts end_date to date` | [x] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [x] |

**Factory:** ✅ (created)

---

### Package: Attribute

#### Attribute
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, admin_name, type, enable_wysiwyg, position, is_required, is_unique, validation, regex, value_per_locale, value_per_channel, default_value, is_filterable, is_configurable, is_visible_on_front, is_user_defined, swatch_type, is_comparable | `allows mass assignment of fillable fields` | [x] |
| Relationship: options() HasMany AttributeOption | `options() returns HasMany instance` | [x] |
| Scope: filterableAttributes() → is_filterable=1, swatch_type≠image, ordered by position | `scopeFilterableAttributes returns only filterable attributes` | [x] |
| Accessor: column_name → derived from attribute type | `getColumnNameAttribute() returns correct column name for type` | [x] |
| Accessor: validations → rules based on attribute type | `getValidationsAttribute() returns validation rules` | [x] |
| Property: $attributeTypeFields maps types to columns | `attributeTypeFields contains expected type mappings` | [x] |

**Factory:** ✅

#### AttributeFamily
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: attribute_groups() HasMany AttributeGroup (ordered by position) | `attribute_groups() returns HasMany instance` | [x] |
| Relationship: products() HasMany Product | `products() returns HasMany instance` | [x] |
| Accessor: custom_attributes → custom attributes collection | `getCustomAttributesAttribute() returns collection` | [x] |
| Accessor: configurable_attributes → configurable select attributes | `getConfigurableAttributesAttribute() returns only configurable` | [x] |
| Method: getComparableAttributesBelongsToFamily() | `getComparableAttributesBelongsToFamily() returns comparable attributes` | [x] |

**Factory:** ✅

#### AttributeGroup
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, column, position, is_user_defined | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: custom_attributes() BelongsToMany Attribute (via attribute_group_mappings, pivot: position) | `custom_attributes() returns BelongsToMany instance` | [x] |

**Factory:** ❌

#### AttributeOption
| Feature | Test | Status |
|---------|------|--------|
| Fillable: admin_name, swatch_value, sort_order, attribute_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: attribute() BelongsTo Attribute | `attribute() returns BelongsTo instance` | [x] |
| Accessor: swatch_value_url → URL when swatch is image, null otherwise | `getSwatchValueUrlAttribute() returns URL for image swatch` | [x] |
| Translated: label | `label attribute is translatable` | [x] |

**Factory:** ✅

#### AttributeOptionTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: label | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

#### AttributeTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

---

### Package: BookingProduct

#### Booking
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, from, to, allow_cancellation, order_item_id, booking_product_event_ticket_id, product_id, order_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: order_item() BelongsTo OrderItem | `order_item() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### BookingProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: location, show_location, type, qty, available_every_week, available_from, available_to, allow_cancellation, product_id | `allows mass assignment of fillable fields` | [x] |
| Cast: available_from → datetime | `casts available_from to datetime` | [x] |
| Cast: available_to → datetime | `casts available_to to datetime` | [x] |
| Relationship: default_slot() HasOne BookingProductDefaultSlot | `default_slot() returns HasOne instance` | [x] |
| Relationship: appointment_slot() HasOne BookingProductAppointmentSlot | `appointment_slot() returns HasOne instance` | [x] |
| Relationship: event_tickets() HasMany BookingProductEventTicket | `event_tickets() returns HasMany instance` | [x] |
| Relationship: rental_slot() HasOne BookingProductRentalSlot | `rental_slot() returns HasOne instance` | [x] |
| Relationship: table_slot() HasOne BookingProductTableSlot | `table_slot() returns HasOne instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Eager loads: default_slot, appointment_slot, event_tickets, rental_slot, table_slot | `model eager loads all slot relations` | [x] |

**Factory:** ❌

#### BookingProductAppointmentSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: duration, break_time, same_slot_all_days, slots, allow_slot_overlap, booking_product_id | `allows mass assignment of fillable fields` | [x] |
| Cast: slots → array | `casts slots to array` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

#### BookingProductDefaultSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: booking_type, duration, break_time, slots, allow_slot_overlap, booking_product_id | `allows mass assignment of fillable fields` | [x] |
| Cast: slots → array | `casts slots to array` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: booking_product() BelongsTo BookingProduct | `booking_product() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### BookingProductEventTicket
| Feature | Test | Status |
|---------|------|--------|
| Fillable: price, qty, special_price, special_price_from, special_price_to, booking_product_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Translated: name, description | `name and description are translatable` | [x] |

**Factory:** ❌

#### BookingProductEventTicketTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

#### BookingProductRentalSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: renting_type, daily_price, hourly_price, same_slot_all_days, slots, booking_product_id | `allows mass assignment of fillable fields` | [x] |
| Cast: slots → array | `casts slots to array` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

#### BookingProductTableSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: price_type, guest_limit, duration, break_time, prevent_scheduling_before, same_slot_all_days, slots, allow_slot_overlap, booking_product_id | `allows mass assignment of fillable fields` | [x] |
| Cast: slots → array | `casts slots to array` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

---

### Package: CartRule

#### CartRule
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, starts_from, ends_till, status, coupon_type, use_auto_generation, usage_per_customer, uses_per_coupon, times_used, condition_type, conditions, actions, end_other_rules, uses_attribute_conditions, action_type, discount_amount, discount_quantity, discount_step, apply_to_shipping, free_shipping, sort_order | `allows mass assignment of fillable fields` | [x] |
| Cast: conditions → array | `casts conditions to array` | [x] |
| Relationship: cart_rule_channels() BelongsToMany Channel | `cart_rule_channels() returns BelongsToMany instance` | [x] |
| Relationship: cart_rule_customer_groups() BelongsToMany CustomerGroup | `cart_rule_customer_groups() returns BelongsToMany instance` | [x] |
| Relationship: cart_rule_coupon() HasOne CartRuleCoupon | `cart_rule_coupon() returns HasOne instance` | [x] |
| Relationship: coupon_code() HasOne CartRuleCoupon (is_primary=1) | `coupon_code() returns only primary coupon` | [x] |
| Accessor: coupon_code → primary coupon code string | `getCouponCodeAttribute() returns primary coupon code` | [x] |

**Factory:** ✅

#### CartRuleCoupon
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, usage_limit, usage_per_customer, times_used, type, cart_rule_id, expired_at, is_primary | `allows mass assignment of fillable fields` | [x] |
| Relationship: cart_rule() BelongsTo CartRule | `cart_rule() returns BelongsTo instance` | [x] |
| Relationship: coupon_usage() HasMany CartRuleCouponUsage | `coupon_usage() returns HasMany instance` | [x] |

**Factory:** ✅

#### CartRuleCouponAssignment
| Feature | Test | Status |
|---------|------|--------|
| Fillable: cart_rule_coupon_id, phone | `allows mass assignment of fillable fields` | [x] |
| Relationship: coupon() BelongsTo CartRuleCoupon | `coupon() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### CartRuleCouponUsage
| Feature | Test | Status |
|---------|------|--------|
| No timestamps | `model has no timestamps` | [x] |
| Guarded (all except timestamps) | `model uses guarded instead of fillable` | [x] |

**Factory:** ❌

#### CartRuleCustomer
| Feature | Test | Status |
|---------|------|--------|
| Fillable: times_used, cart_rule_id, customer_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

#### CartRuleTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: label | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

---

### Package: CatalogRule

#### CatalogRule
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, starts_from, ends_till, status, condition_type, conditions, end_other_rules, action_type, discount_amount, sort_order | `allows mass assignment of fillable fields` | [x] |
| Cast: conditions → array | `casts conditions to array` | [x] |
| Relationship: channels() BelongsToMany Channel | `channels() returns BelongsToMany instance` | [x] |
| Relationship: customer_groups() BelongsToMany CustomerGroup | `customer_groups() returns BelongsToMany instance` | [x] |
| Relationship: catalog_rule_products() HasMany CatalogRuleProduct | `catalog_rule_products() returns HasMany instance` | [x] |
| Relationship: catalog_rule_product_prices() HasMany CatalogRuleProductPrice | `catalog_rule_product_prices() returns HasMany instance` | [x] |

**Factory:** ✅

#### CatalogRuleProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: starts_from, ends_till, discount_amount, action_type, end_other_rules, sort_order, catalog_rule_id, channel_id, customer_group_id, product_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: catalog_rule() BelongsTo CatalogRule | `catalog_rule() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [x] |
| Relationship: customer_group() BelongsTo CustomerGroup | `customer_group() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### CatalogRuleProductPrice
| Feature | Test | Status |
|---------|------|--------|
| Fillable: price, rule_date, starts_from, ends_till, catalog_rule_id, channel_id, customer_group_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ❌

---

### Package: Category

#### Category
| Feature | Test | Status |
|---------|------|--------|
| Fillable: position, status, display_mode, parent_id, additional | `allows mass assignment of fillable fields` | [x] |
| Relationship: products() BelongsToMany Product | `products() returns BelongsToMany instance` | [x] |
| Relationship: filterableAttributes() BelongsToMany Attribute | `filterableAttributes() returns BelongsToMany instance` | [x] |
| Accessor: url → localized category URL | `getUrlAttribute() returns valid URL string` | [x] |
| Accessor: logo_url → Storage URL for logo | `getLogoUrlAttribute() returns URL or null` | [x] |
| Accessor: banner_url → Storage URL for banner | `getBannerUrlAttribute() returns URL or null` | [x] |
| Translated: name, description, slug, meta_title, meta_description, meta_keywords | `name is translatable` | [x] |
| Uses NodeTrait for nested set | `parent_id relationship works correctly` | [x] |

**Factory:** ✅

#### CategoryTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, slug, meta_title, meta_description, meta_keywords, locale_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ✅

---

### Package: Checkout

#### Cart
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → json | `casts additional to array` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [x] |
| Relationship: items() HasMany CartItem (parent_id null) | `items() returns HasMany instance` | [x] |
| Relationship: all_items() HasMany CartItem | `all_items() returns HasMany instance` | [x] |
| Relationship: billing_address() HasOne CartAddress (billing) | `billing_address() returns billing address` | [x] |
| Relationship: shipping_address() HasOne CartAddress (shipping) | `shipping_address() returns shipping address` | [x] |
| Relationship: payment() HasOne CartPayment | `payment() returns HasOne instance` | [x] |
| Method: haveStockableItems() | `haveStockableItems() returns true when cart has stockable items` | [x] |
| Method: hasOnlyStockableItems() | `hasOnlyStockableItems() returns correct boolean` | [x] |
| Method: hasDownloadableItems() | `hasDownloadableItems() returns correct boolean` | [x] |

**Factory:** ✅

#### CartAddress
| Feature | Test | Status |
|---------|------|--------|
| Constant: ADDRESS_TYPE_SHIPPING = 'cart_shipping' | `ADDRESS_TYPE_SHIPPING constant is defined` | [x] |
| Constant: ADDRESS_TYPE_BILLING = 'cart_billing' | `ADDRESS_TYPE_BILLING constant is defined` | [x] |
| Relationship: shipping_rates() HasMany CartShippingRate | `shipping_rates() returns HasMany instance` | [x] |
| Relationship: cart() BelongsTo Cart | `cart() returns BelongsTo instance` | [x] |
| Global scope filters by address_type | `default scope limits to correct address_type` | [x] |

**Factory:** ✅

#### CartItem
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [x] |
| Relationship: product() HasOne Product | `product() returns HasOne instance` | [x] |
| Relationship: parent() BelongsTo CartItem | `parent() returns BelongsTo instance` | [x] |
| Relationship: children() HasMany CartItem | `children() returns HasMany instance` | [x] |

**Factory:** ✅

#### CartPayment
| Feature | Test | Status |
|---------|------|--------|
| Custom table: cart_payment | `model uses correct table name` | [x] |

**Factory:** ✅

#### CartShippingRate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: carrier, carrier_title, method, method_title, method_description, price, base_price, discount_amount, base_discount_amount, tax_percent, tax_amount, base_tax_amount | `allows mass assignment of fillable fields` | [x] |
| Relationship: shipping_address() BelongsTo CartAddress | `shipping_address() returns BelongsTo instance` | [x] |

**Factory:** ✅

---

### Package: CMS

#### Page
| Feature | Test | Status |
|---------|------|--------|
| Fillable: layout | `allows mass assignment of fillable fields` | [x] |
| Relationship: channels() BelongsToMany Channel | `channels() returns BelongsToMany instance` | [x] |
| Translated: content, meta_description, meta_title, page_title, meta_keywords, html_content, url_key | `page_title is translatable` | [x] |

**Factory:** ✅

#### PageTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: page_title, url_key, html_content, meta_title, meta_description, meta_keywords, locale, cms_page_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |

**Factory:** ✅

---

### Package: Core

#### Address
| Feature | Test | Status |
|---------|------|--------|
| Guarded: id, created_at, updated_at | `mass assignment respects guarded fields` | [x] |
| Cast: use_for_shipping → boolean | `casts use_for_shipping to boolean` | [x] |
| Cast: default_address → boolean | `casts default_address to boolean` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |
| Accessor: name → first_name + last_name | `getNameAttribute() returns full name` | [x] |

**Factory:** ❌

#### Channel
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, description, theme, hostname, default_locale_id, base_currency_id, root_category_id, home_seo, is_maintenance_on, maintenance_mode_text, allowed_ips | `allows mass assignment of fillable fields` | [x] |
| Cast: home_seo → array | `casts home_seo to array` | [x] |
| Relationship: locales() BelongsToMany Locale | `locales() returns BelongsToMany instance` | [x] |
| Relationship: default_locale() BelongsTo Locale | `default_locale() returns BelongsTo instance` | [x] |
| Relationship: currencies() BelongsToMany Currency | `currencies() returns BelongsToMany instance` | [x] |
| Relationship: base_currency() BelongsTo Currency | `base_currency() returns BelongsTo instance` | [x] |
| Relationship: root_category() BelongsTo Category | `root_category() returns BelongsTo instance` | [x] |
| Accessor: logo_url → Storage URL | `getLogoUrlAttribute() returns URL or null` | [x] |
| Accessor: favicon_url → Storage URL | `getFaviconUrlAttribute() returns URL or null` | [x] |

**Factory:** ✅

#### CoreConfig
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, value, channel_code, locale_code | `allows mass assignment of fillable fields` | [x] |
| Custom table: core_config | `model uses correct table name` | [x] |
| Hidden: token | `token field is hidden` | [x] |

**Factory:** ✅

#### Country
| Feature | Test | Status |
|---------|------|--------|
| No timestamps | `model has no timestamps` | [x] |
| Translated: name | `name is translatable` | [x] |
| Relationship: states() HasMany CountryState | `states() returns HasMany instance` | [x] |

**Factory:** ❌

#### Currency
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, symbol, decimal, group_separator, decimal_separator, currency_position | `allows mass assignment of fillable fields` | [x] |
| Relationship: exchange_rate() HasOne CurrencyExchangeRate | `exchange_rate() returns HasOne instance` | [x] |
| Mutator: setCodeAttribute() converts code to uppercase | `setCodeAttribute() stores code in uppercase` | [x] |

**Factory:** ✅

#### CurrencyExchangeRate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: target_currency, rate | `allows mass assignment of fillable fields` | [x] |
| Relationship: currency() BelongsTo Currency | `currency() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### Locale
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, direction | `allows mass assignment of fillable fields` | [x] |
| Accessor: logo_url → Storage URL | `getLogoUrlAttribute() returns URL or null` | [x] |

**Factory:** ✅

#### SubscribersList
| Feature | Test | Status |
|---------|------|--------|
| Fillable: email, is_subscribed, token, customer_id, channel_id | `allows mass assignment of fillable fields` | [x] |
| Hidden: token | `token field is hidden from serialization` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ✅

---

### Package: Customer

#### Customer
| Feature | Test | Status |
|---------|------|--------|
| Fillable: first_name, last_name, gender, date_of_birth, email, phone, password, api_token, token, customer_group_id, channel_id, subscribed_to_news_letter, status, is_verified, is_suspended | `allows mass assignment of fillable fields` | [x] |
| Cast: subscribed_to_news_letter → boolean | `casts subscribed_to_news_letter to boolean` | [x] |
| Hidden: password, api_token, remember_token | `sensitive fields are hidden` | [x] |
| Relationship: group() BelongsTo CustomerGroup | `group() returns BelongsTo instance` | [x] |
| Relationship: addresses() HasMany CustomerAddress | `addresses() returns HasMany instance` | [x] |
| Relationship: default_address() HasOne CustomerAddress (default) | `default_address() returns only default address` | [x] |
| Relationship: orders() HasMany Order | `orders() returns HasMany instance` | [x] |
| Relationship: invoices() HasManyThrough Invoice through Order | `invoices() returns HasManyThrough instance` | [x] |
| Relationship: wishlist_items() HasMany Wishlist | `wishlist_items() returns HasMany instance` | [x] |
| Relationship: reviews() HasMany ProductReview | `reviews() returns HasMany instance` | [x] |
| Accessor: name → first_name + last_name | `getNameAttribute() returns full name` | [x] |
| Accessor: image_url → Storage URL | `getImageUrlAttribute() returns URL or null` | [x] |
| Method: emailExists($email) checks if email is taken | `emailExists() returns true for existing email` | [x] |
| Method: emailExists($email) returns false for new email | `emailExists() returns false for unused email` | [x] |
| Method: isWishlistShared() checks shared wishlist items | `isWishlistShared() returns boolean` | [x] |

**Factory:** ✅

#### CustomerAddress
| Feature | Test | Status |
|---------|------|--------|
| Constant: ADDRESS_TYPE = 'customer' | `ADDRESS_TYPE constant is defined` | [x] |
| Global scope filters by address_type = 'customer' | `global scope limits to customer addresses` | [x] |

**Factory:** ✅

#### CustomerGroup
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, code, is_user_defined | `allows mass assignment of fillable fields` | [x] |
| Relationship: customers() HasMany Customer | `customers() returns HasMany instance` | [x] |

**Factory:** ✅

#### CompareItem
| Feature | Test | Status |
|---------|------|--------|
| Guarded: [] (all fillable) | `model allows all field assignment` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### Wishlist
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### CustomerNote
| Feature | Test | Status |
|---------|------|--------|
| Fillable: note, customer_id, customer_notified | `allows mass assignment of fillable fields` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### CustomerLoyaltyPoint (Customer package)
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, balance | `allows mass assignment of fillable fields` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |
| Relationship: transactions() HasMany CustomerLoyaltyTransaction | `transactions() returns HasMany instance` | [x] |

**Factory:** ❌

#### CustomerLoyaltyTransaction (Customer package)
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, points, type, description, order_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### CustomerReferral (Customer package)
| Feature | Test | Status |
|---------|------|--------|
| Fillable: referrer_id, code, referred_customer_id, order_placed, reward_issued | `allows mass assignment of fillable fields` | [x] |
| Relationship: referrer() BelongsTo Customer (referrer_id) | `referrer() returns BelongsTo instance` | [x] |
| Relationship: referredCustomer() BelongsTo Customer (referred_customer_id) | `referredCustomer() returns BelongsTo instance` | [x] |

**Factory:** ❌

---

### Package: DataGrid

#### SavedFilter
| Feature | Test | Status |
|---------|------|--------|
| Fillable: user_id, src, name, applied | `allows mass assignment of fillable fields` | [x] |
| Cast: applied → json | `casts applied to array` | [x] |

**Factory:** ❌

---

### Package: DataTransfer

#### Import
| Feature | Test | Status |
|---------|------|--------|
| Fillable: state, process_in_queue, type, action, validation_strategy, allowed_errors, processed_rows_count, invalid_rows_count, errors_count, errors, field_separator, file_path, images_directory_path, error_file_path, summary, started_at, completed_at | `allows mass assignment of fillable fields` | [x] |
| Cast: summary → array | `casts summary to array` | [x] |
| Cast: errors → array | `casts errors to array` | [x] |
| Cast: started_at → datetime | `casts started_at to datetime` | [x] |
| Cast: completed_at → datetime | `casts completed_at to datetime` | [x] |
| Relationship: batches() HasMany ImportBatch | `batches() returns HasMany instance` | [x] |

**Factory:** ❌

#### ImportBatch
| Feature | Test | Status |
|---------|------|--------|
| Fillable: state, data, summary, import_id | `allows mass assignment of fillable fields` | [x] |
| Cast: summary → array | `casts summary to array` | [x] |
| Cast: data → array | `casts data to array` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: import() BelongsTo Import | `import() returns BelongsTo instance` | [x] |

**Factory:** ❌

---

### Package: GDPR

#### GDPRDataRequest
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, email, status, type, message, revoked_at | `allows mass assignment of fillable fields` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ❌

---

### Package: Inventory

#### InventorySource
| Feature | Test | Status |
|---------|------|--------|
| Guarded: _token | `mass assignment excludes _token` | [x] |

**Factory:** ✅

---

### Package: Marketing

#### Campaign
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, subject, status, channel_id, customer_group_id, marketing_template_id, spooling, marketing_event_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: event() BelongsTo Event | `event() returns BelongsTo instance` | [x] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [x] |
| Relationship: customer_group() BelongsTo CustomerGroup | `customer_group() returns BelongsTo instance` | [x] |
| Relationship: email_template() BelongsTo Template | `email_template() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### Event
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, date | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅

#### SearchSynonym
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, terms | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅

#### SearchTerm
| Feature | Test | Status |
|---------|------|--------|
| Fillable: term, results, uses, redirect_url, display_in_suggested_terms, locale, channel_id | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅

#### Template
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, status, content | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅

#### URLRewrite
| Feature | Test | Status |
|---------|------|--------|
| Fillable: entity_type, request_path, target_path, redirect_type, locale | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅

---

### Package: Notification

#### Notification
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, read, order_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |

**Factory:** ❌

---

### Package: Product

#### Product
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, attribute_family_id, sku, parent_id | `allows mass assignment of fillable fields` | [x] |
| Cast: additional → array | `casts additional to array` | [x] |
| Relationship: attribute_family() BelongsTo AttributeFamily | `attribute_family() returns BelongsTo instance` | [x] |
| Relationship: parent() BelongsTo Product | `parent() returns BelongsTo instance` | [x] |
| Relationship: variants() HasMany Product | `variants() returns HasMany instance` | [x] |
| Relationship: categories() BelongsToMany Category | `categories() returns BelongsToMany instance` | [x] |
| Relationship: images() HasMany ProductImage | `images() returns HasMany instance` | [x] |
| Relationship: reviews() HasMany ProductReview | `reviews() returns HasMany instance` | [x] |
| Relationship: approvedReviews() HasMany ProductReview (filtered) | `approvedReviews() returns only approved reviews` | [x] |
| Relationship: inventories() HasMany ProductInventory | `inventories() returns HasMany instance` | [x] |
| Relationship: inventory_sources() BelongsToMany InventorySource | `inventory_sources() returns BelongsToMany instance` | [x] |
| Relationship: super_attributes() BelongsToMany Attribute | `super_attributes() returns BelongsToMany instance` | [x] |
| Relationship: related_products() BelongsToMany Product | `related_products() returns BelongsToMany instance` | [x] |
| Relationship: up_sells() BelongsToMany Product | `up_sells() returns BelongsToMany instance` | [x] |
| Relationship: cross_sells() BelongsToMany Product | `cross_sells() returns BelongsToMany instance` | [x] |
| Method: isSaleable() | `isSaleable() returns boolean` | [x] |
| Method: isStockable() | `isStockable() returns boolean` | [x] |
| Accessor: base_image_url → first product image URL | `getBaseImageUrlAttribute() returns URL or null` | [x] |

**Factory:** ✅

#### ProductAttributeValue
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, attribute_id, locale, channel, unique_id, text_value, boolean_value, integer_value, float_value, datetime_value, date_value, json_value | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: attribute() BelongsTo Attribute | `attribute() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Property: $attributeTypeFields maps types to value columns | `attributeTypeFields contains all expected type mappings` | [x] |

**Factory:** ✅

#### ProductImage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, path, product_id, position | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Accessor: url → full image URL | `getUrlAttribute() returns URL string` | [x] |

**Factory:** ❌

#### ProductInventory
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, product_id, inventory_source_id, vendor_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: inventory_source() BelongsTo InventorySource | `inventory_source() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### ProductReview
| Feature | Test | Status |
|---------|------|--------|
| Fillable: comment, title, rating, status, product_id, customer_id, name | `allows mass assignment of fillable fields` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Relationship: images() HasMany ProductReviewAttachment | `images() returns HasMany instance` | [x] |

**Factory:** ✅

#### ProductReviewAttachment
| Feature | Test | Status |
|---------|------|--------|
| Fillable: path, review_id, type, mime_type | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: review() BelongsTo ProductReview | `review() returns BelongsTo instance` | [x] |
| Accessor: url → full attachment URL | `getUrlAttribute() returns URL string` | [x] |

**Factory:** ✅

#### ProductDownloadableLink
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, price, url, file, file_name, type, sample_url, sample_file, sample_file_name, sample_type, sort_order, product_id, downloads | `allows mass assignment of fillable fields` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Accessor: file_url → full file URL | `getFileUrlAttribute() returns URL string` | [x] |
| Accessor: sample_file_url → full sample URL | `getSampleFileUrlAttribute() returns URL string` | [x] |

**Factory:** ✅

#### ProductGroupedProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, sort_order, product_id, associated_product_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Relationship: associated_product() BelongsTo Product | `associated_product() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### ProductCustomerGroupPrice
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, value_type, value, product_id, customer_group_id, unique_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Relationship: customer_group() BelongsTo CustomerGroup | `customer_group() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### StockNotification
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, email, phone, channel_id, notified | `allows mass assignment of fillable fields` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### ProductBundleOption
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, is_required, sort_order, product_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Translated: label | `label is translatable` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |
| Relationship: bundle_option_products() HasMany ProductBundleOptionProduct | `bundle_option_products() returns HasMany instance` | [x] |

**Factory:** ✅

#### ProductBundleOptionProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, is_user_defined, sort_order, is_default, product_bundle_option_id, product_id | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Relationship: bundle_option() BelongsTo ProductBundleOption | `bundle_option() returns BelongsTo instance` | [x] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### ProductVideo
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, path, product_id, position | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Accessor: url → full video URL | `getUrlAttribute() returns URL string` | [x] |

**Factory:** ❌

---

### Package: RMA

#### RMA
| Feature | Test | Status |
|---------|------|--------|
| Fillable: information, rma_status_id, order_id, status, package_condition | `allows mass assignment of fillable fields` | [x] |
| Relationship: status() BelongsTo RMAStatus | `status() returns BelongsTo instance` | [x] |
| Relationship: images() HasMany RMAImage | `images() returns HasMany instance` | [x] |
| Relationship: item() HasOne RMAItem | `item() returns HasOne instance` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: messages() HasMany RMAMessage | `messages() returns HasMany instance` | [x] |
| Relationship: additionalFields() HasMany RMAAdditionalField | `additionalFields() returns HasMany instance` | [x] |

**Factory:** ❌

#### RMACustomField
| Feature | Test | Status |
|---------|------|--------|
| Fillable: status, code, label, type, is_required, position, input_validation | `allows mass assignment of fillable fields` | [x] |
| Relationship: options() HasMany RMACustomFieldOption | `options() returns HasMany instance` | [x] |

**Factory:** ❌

#### RMACustomFieldOption
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_custom_field_id, name, value | `allows mass assignment of fillable fields` | [x] |
| Relationship: rmaCustomField() BelongsTo RMACustomField | `rmaCustomField() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### RMAItem
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_id, quantity, order_item_id, resolution, rma_reason_id, variant_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: rma() BelongsTo RMA | `rma() returns BelongsTo instance` | [x] |
| Relationship: orderItem() BelongsTo OrderItem | `orderItem() returns BelongsTo instance` | [x] |
| Relationship: product() HasOneThrough Product via OrderItem | `product() returns HasOneThrough instance` | [x] |

**Factory:** ❌

#### RMAMessage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: message, rma_id, is_admin, attachment_path, attachment | `allows mass assignment of fillable fields` | [x] |
| Relationship: rma() BelongsTo RMA | `rma() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### RMAReason
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, status, position | `allows mass assignment of fillable fields` | [x] |
| Relationship: reasonResolutions() HasMany RMAReasonResolution | `reasonResolutions() returns HasMany instance` | [x] |

**Factory:** ❌

#### RMAReasonResolution
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_reason_id, resolution_type | `allows mass assignment of fillable fields` | [x] |

**Factory:** ❌

#### RMARule
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, status, return_period, default | `allows mass assignment of fillable fields` | [x] |

**Factory:** ❌

#### RMAStatus
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, status, color | `allows mass assignment of fillable fields` | [x] |

**Factory:** ❌

#### RMAAdditionalField
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_id, rma_custom_field_id, value | `allows mass assignment of fillable fields` | [x] |
| Relationship: customField() BelongsTo RMACustomField | `customField() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### RMAImage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_id, path | `allows mass assignment of fillable fields` | [x] |

**Factory:** ❌

---

### Package: Sales

#### Order
| Feature | Test | Status |
|---------|------|--------|
| Constant: STATUS_PENDING | `STATUS_PENDING constant is defined` | [x] |
| Constant: STATUS_PROCESSING | `STATUS_PROCESSING constant is defined` | [x] |
| Constant: STATUS_COMPLETED | `STATUS_COMPLETED constant is defined` | [x] |
| Constant: STATUS_CANCELED | `STATUS_CANCELED constant is defined` | [x] |
| Constant: STATUS_CLOSED | `STATUS_CLOSED constant is defined` | [x] |
| Constant: STATUS_FRAUD | `STATUS_FRAUD constant is defined` | [x] |
| Relationship: items() HasMany OrderItem (parent_id null) | `items() returns HasMany instance` | [x] |
| Relationship: all_items() HasMany OrderItem | `all_items() returns HasMany instance` | [x] |
| Relationship: shipments() HasMany Shipment | `shipments() returns HasMany instance` | [x] |
| Relationship: invoices() HasMany Invoice | `invoices() returns HasMany instance` | [x] |
| Relationship: refunds() HasMany Refund | `refunds() returns HasMany instance` | [x] |
| Relationship: payment() HasOne OrderPayment | `payment() returns HasOne instance` | [x] |
| Relationship: addresses() HasMany OrderAddress | `addresses() returns HasMany instance` | [x] |
| Accessor: customer_full_name | `getCustomerFullNameAttribute() returns full name` | [x] |
| Accessor: status_label | `getStatusLabelAttribute() returns human-readable status` | [x] |
| Accessor: base_total_due | `getBaseTotalDueAttribute() returns numeric value` | [x] |
| Method: canShip() | `canShip() returns false for canceled order` | [x] |
| Method: canInvoice() | `canInvoice() returns false for completed order` | [x] |
| Method: canCancel() | `canCancel() returns correct boolean` | [x] |
| Method: haveStockableItems() | `haveStockableItems() returns boolean` | [x] |
| Method: hasOpenInvoice() | `hasOpenInvoice() returns boolean` | [x] |

**Factory:** ✅

#### OrderItem
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: child() HasOne OrderItem | `child() returns HasOne instance` | [x] |
| Relationship: parent() BelongsTo OrderItem | `parent() returns BelongsTo instance` | [x] |
| Relationship: children() HasMany OrderItem | `children() returns HasMany instance` | [x] |
| Relationship: invoice_items() HasMany InvoiceItem | `invoice_items() returns HasMany instance` | [x] |
| Relationship: shipment_items() HasMany ShipmentItem | `shipment_items() returns HasMany instance` | [x] |
| Method: canShip() | `canShip() returns correct boolean` | [x] |
| Method: canInvoice() | `canInvoice() returns correct boolean` | [x] |
| Method: isStockable() | `isStockable() returns boolean` | [x] |
| Accessor: qty_to_ship | `getQtyToShipAttribute() returns numeric` | [x] |
| Accessor: qty_to_invoice | `getQtyToInvoiceAttribute() returns numeric` | [x] |

**Factory:** ✅

#### OrderAddress
| Feature | Test | Status |
|---------|------|--------|
| Constant: ADDRESS_TYPE_SHIPPING = 'order_shipping' | `ADDRESS_TYPE_SHIPPING constant is defined` | [x] |
| Constant: ADDRESS_TYPE_BILLING = 'order_billing' | `ADDRESS_TYPE_BILLING constant is defined` | [x] |
| Default attribute: address_type = ADDRESS_TYPE_BILLING | `default address_type is billing` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### OrderComment
| Feature | Test | Status |
|---------|------|--------|
| Fillable: comment, customer_notified, order_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### OrderPayment
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [x] |
| Custom table: order_payment | `model uses correct table name` | [x] |

**Factory:** ✅

#### Invoice
| Feature | Test | Status |
|---------|------|--------|
| Constant: STATUS_PENDING | `STATUS_PENDING constant is defined` | [x] |
| Constant: STATUS_PAID | `STATUS_PAID constant is defined` | [x] |
| Constant: STATUS_REFUNDED | `STATUS_REFUNDED constant is defined` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: items() HasMany InvoiceItem | `items() returns HasMany instance` | [x] |
| Accessor: status_label | `getStatusLabelAttribute() returns human-readable label` | [x] |

**Factory:** ✅

#### Refund
| Feature | Test | Status |
|---------|------|--------|
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: items() HasMany RefundItem | `items() returns HasMany instance` | [x] |
| Accessor: status_label | `getStatusLabelAttribute() returns human-readable label` | [x] |

**Factory:** ✅

#### Shipment
| Feature | Test | Status |
|---------|------|--------|
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: items() HasMany ShipmentItem | `items() returns HasMany instance` | [x] |
| Relationship: inventory_source() BelongsTo InventorySource | `inventory_source() returns BelongsTo instance` | [x] |

**Factory:** ✅

#### DownloadableLinkPurchased
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_name, name, url, file, file_name, type, download_bought, download_used, status, customer_id, order_id, order_item_id, download_canceled | `allows mass assignment of fillable fields` | [x] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ❌

#### OrderTransaction
| Feature | Test | Status |
|---------|------|--------|
| Accessor: payment_title | `getPaymentTitleAttribute() returns string` | [x] |

**Factory:** ✅

---

### Package: Sitemap

#### Sitemap
| Feature | Test | Status |
|---------|------|--------|
| Fillable: additional, file_name, generated_at, path | `allows mass assignment of fillable fields` | [x] |
| Cast: additional → json | `casts additional to array` | [x] |
| Accessor: index_file_name | `getIndexFileNameAttribute() returns string` | [x] |
| Method: deleteFromStorage() removes the sitemap file | `deleteFromStorage() removes file from storage` | [x] |

**Factory:** ✅

---

### Package: SocialLogin

#### CustomerSocialAccount
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, provider_name, provider_id | `allows mass assignment of fillable fields` | [x] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [x] |

**Factory:** ❌

---

### Package: Tax

#### TaxCategory
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, description | `allows mass assignment of fillable fields` | [x] |
| Relationship: tax_rates() BelongsToMany TaxRate | `tax_rates() returns BelongsToMany instance` | [x] |

**Factory:** ✅

#### TaxMap
| Feature | Test | Status |
|---------|------|--------|
| Fillable: tax_category_id, tax_rate_id | `allows mass assignment of fillable fields` | [x] |

**Factory:** ✅

#### TaxRate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: identifier, is_zip, zip_code, zip_from, zip_to, state, country, tax_rate | `allows mass assignment of fillable fields` | [x] |
| Relationship: tax_categories() BelongsToMany TaxCategory | `tax_categories() returns BelongsToMany instance` | [x] |

**Factory:** ✅

---

### Package: Theme

#### ThemeCustomization
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, name, options, sort_order, status, channel_id, theme_code | `allows mass assignment of fillable fields` | [x] |
| Cast: options → array | `casts options to array` | [x] |
| Constants: IMAGE_CAROUSEL, PRODUCT_CAROUSEL, CATEGORY_CAROUSEL, FOOTER_LINKS, STATIC_CONTENT, SERVICES_CONTENT | `all type constants are defined` | [x] |
| Translated: options | `options is translatable` | [x] |

**Factory:** ✅

#### ThemeCustomizationTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, options | `allows mass assignment of fillable fields` | [x] |
| No timestamps | `model has no timestamps` | [x] |
| Cast: options → array | `casts options to array` | [x] |

**Factory:** ✅

---

### Package: User

#### Admin
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, email, password, image, api_token, role_id, status, two_factor_secret, two_factor_enabled, two_factor_backup_codes, two_factor_verified_at | `allows mass assignment of fillable fields` | [x] |
| Cast: two_factor_backup_codes → array | `casts two_factor_backup_codes to array` | [x] |
| Cast: two_factor_verified_at → datetime | `casts two_factor_verified_at to datetime` | [x] |
| Cast: two_factor_enabled → boolean | `casts two_factor_enabled to boolean` | [x] |
| Hidden: password, api_token, remember_token | `sensitive fields are hidden` | [x] |
| Relationship: role() BelongsTo Role | `role() returns BelongsTo instance` | [x] |
| Method: hasPermission($permission) checks ACL | `hasPermission() returns true for allowed permission` | [x] |
| Method: hasPermission() returns false for denied | `hasPermission() returns false for denied permission` | [x] |
| Accessor: image_url → Storage URL | `getImageUrlAttribute() returns URL or null` | [x] |

**Factory:** ✅

#### Role
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, permission_type, permissions | `allows mass assignment of fillable fields` | [x] |
| Cast: permissions → array | `casts permissions to array` | [x] |
| Relationship: admins() HasMany Admin | `admins() returns HasMany instance` | [x] |

**Factory:** ✅

---

## Summary

| Category | Models | Factories | Test Files |
|----------|--------|-----------|------------|
| Aven Custom | ~60 | 0 | 0 |
| Core Bagisto | ~103 | ~64 | partial |
| **Total** | **163** | **~64** | **0 unit** |

### Packages with NO models (service/integration only)
- Fawry, GoogleShopping, OrderNotification, Paymob, SmsNotification, SocialShare, Valu, Payment

### Factory creation needed (Aven custom packages)
All 26 Aven custom packages need factories before unit tests can be written.
