# Model Test Checklist

> **163 models** across 50 packages. Each checkbox represents one test case.
> Legend: `[ ]` = not tested · `[x]` = tested · `[-]` = N/A (no factory, skip for now)

---

## Progress: 0 / 163 tested

---

## Aven Custom Packages

---

### Package: AbandonedCart

#### AbandonedCartNotification
| Feature | Test | Status |
|---------|------|--------|
| Fillable: cart_id, channel, attempt_number, status, sent_at, opened_at, clicked_at, error_message | `allows mass assignment of fillable fields` | [ ] |
| Cast: sent_at → datetime | `casts sent_at to datetime` | [ ] |
| Cast: opened_at → datetime | `casts opened_at to datetime` | [ ] |
| Cast: clicked_at → datetime | `casts clicked_at to datetime` | [ ] |
| Cast: created_at → datetime | `casts created_at to datetime` | [ ] |
| Relationship: cart() BelongsTo CartProxy | `cart() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: Affiliate

#### Affiliate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, name, email, code, status, commission_rate, total_earned, total_paid, notes | `allows mass assignment of fillable fields` | [ ] |
| Cast: commission_rate → decimal:2 | `casts commission_rate to float` | [ ] |
| Cast: total_earned → decimal:4 | `casts total_earned to float` | [ ] |
| Cast: total_paid → decimal:4 | `casts total_paid to float` | [ ] |
| Relationship: commissions() HasMany AffiliateCommission | `commissions() returns HasMany instance` | [ ] |
| Relationship: clicks() HasMany AffiliateClick | `clicks() returns HasMany instance` | [ ] |
| Method: generateCode() returns unique 8-char code | `generateCode() generates a unique code` | [ ] |
| Method: pendingBalance() calculates pending commissions | `pendingBalance() returns correct pending balance` | [ ] |

**Factory:** ❌ (needs creation)

#### AffiliateClick
| Feature | Test | Status |
|---------|------|--------|
| Fillable: affiliate_id, ip, url | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌ (needs creation)

#### AffiliateCommission
| Feature | Test | Status |
|---------|------|--------|
| Fillable: affiliate_id, order_id, order_total, commission, status | `allows mass assignment of fillable fields` | [ ] |
| Cast: order_total → decimal:4 | `casts order_total to float` | [ ] |
| Cast: commission → decimal:4 | `casts commission to float` | [ ] |
| Relationship: affiliate() BelongsTo Affiliate | `affiliate() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: AiSupport

#### AiConversation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: channel, channel_identifier, customer_id, status, assigned_admin_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: messages() HasMany AiMessage (ordered) | `messages() returns HasMany instance` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |
| Relationship: assignedAdmin() BelongsTo Admin | `assignedAdmin() returns BelongsTo instance` | [ ] |
| Method: isOpen() returns true when status = 'open' | `isOpen() returns true for open conversations` | [ ] |
| Method: isOpen() returns false otherwise | `isOpen() returns false for non-open conversations` | [ ] |
| Method: isHandedOff() returns true when status = 'human_handoff' | `isHandedOff() returns true on human handoff` | [ ] |

**Factory:** ❌ (needs creation)

#### AiKnowledgeBase
| Feature | Test | Status |
|---------|------|--------|
| Fillable: question, answer, is_active, sort_order | `allows mass assignment of fillable fields` | [ ] |
| Cast: is_active → boolean | `casts is_active to boolean` | [ ] |

**Factory:** ❌ (needs creation)

#### AiMessage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: conversation_id, role, content, ai_draft, status, sent_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: sent_at → datetime | `casts sent_at to datetime` | [ ] |
| Relationship: conversation() BelongsTo AiConversation | `conversation() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: Blog

#### BlogPost
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, slug, content, excerpt, featured_image, category, tags, status, meta_title, meta_description, author_id, published_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: tags → array | `casts tags to array` | [ ] |
| Cast: published_at → datetime | `casts published_at to datetime` | [ ] |
| Scope: published() → status=published AND published_at <= now | `scopePublished returns only published posts` | [ ] |
| Method: generateSlug() creates unique slug from title | `generateSlug() produces a slug from title` | [ ] |
| Accessor: reading_time returns word-count / 200 | `getReadingTimeAttribute() returns integer minutes` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: BulkDeal

#### BulkDeal
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, status, paid_quantity, deal_quantity, deal_price, starts_from, ends_till, sort_order | `allows mass assignment of fillable fields` | [ ] |
| Cast: status → boolean | `casts status to boolean` | [ ] |
| Cast: deal_price → float | `casts deal_price to float` | [ ] |
| Cast: starts_from → datetime | `casts starts_from to datetime` | [ ] |
| Cast: ends_till → datetime | `casts ends_till to datetime` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: CostManagement

#### FinancialTransaction
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, amount, description, platform, reference_id, reference_type, transaction_date | `allows mass assignment of fillable fields` | [ ] |
| Cast: amount → float | `casts amount to float` | [ ] |
| Cast: transaction_date → date | `casts transaction_date to date` | [ ] |
| Method: typeLabels() returns label map | `typeLabels() returns array with sale, refund, expense, ad_spend keys` | [ ] |
| Method: platforms() returns platform list | `platforms() returns non-empty array of platforms` | [ ] |

**Factory:** ❌ (needs creation)

#### GeneralExpense
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, category, amount, expense_date, is_recurring, frequency, notes | `allows mass assignment of fillable fields` | [ ] |
| Cast: expense_date → date | `casts expense_date to date` | [ ] |
| Cast: is_recurring → boolean | `casts is_recurring to boolean` | [ ] |
| Cast: amount → float | `casts amount to float` | [ ] |
| Method: categories() returns label map | `categories() returns non-empty array of categories` | [ ] |

**Factory:** ❌ (needs creation)

#### ProductCost
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, cost_price, manufacturing_fee, shipping_cost_per_unit, other_costs, notes | `allows mass assignment of fillable fields` | [ ] |
| Cast: cost_price → float | `casts cost_price to float` | [ ] |
| Cast: manufacturing_fee → float | `casts manufacturing_fee to float` | [ ] |
| Cast: shipping_cost_per_unit → float | `casts shipping_cost_per_unit to float` | [ ] |
| Cast: other_costs → float | `casts other_costs to float` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Accessor: total_cost = sum of all cost components | `getTotalCostAttribute() sums all costs correctly` | [ ] |

**Factory:** ❌ (needs creation)

#### ProfitDistribution
| Feature | Test | Status |
|---------|------|--------|
| Fillable: period_from, period_to, net_profit, total_distributed, notes | `allows mass assignment of fillable fields` | [ ] |
| Cast: period_from → date | `casts period_from to date` | [ ] |
| Cast: period_to → date | `casts period_to to date` | [ ] |
| Cast: net_profit → float | `casts net_profit to float` | [ ] |
| Cast: total_distributed → float | `casts total_distributed to float` | [ ] |
| Relationship: items() HasMany ProfitDistributionItem | `items() returns HasMany instance` | [ ] |

**Factory:** ❌ (needs creation)

#### ProfitDistributionItem
| Feature | Test | Status |
|---------|------|--------|
| Fillable: distribution_id, shareholder_id, percentage, amount | `allows mass assignment of fillable fields` | [ ] |
| Cast: percentage → float | `casts percentage to float` | [ ] |
| Cast: amount → float | `casts amount to float` | [ ] |
| Relationship: shareholder() BelongsTo Shareholder | `shareholder() returns BelongsTo instance` | [ ] |
| Relationship: distribution() BelongsTo ProfitDistribution | `distribution() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

#### Shareholder
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, email, phone, percentage, active, notes, joined_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: active → boolean | `casts active to boolean` | [ ] |
| Cast: joined_at → date | `casts joined_at to date` | [ ] |
| Cast: percentage → float | `casts percentage to float` | [ ] |
| Relationship: distributionItems() HasMany ProfitDistributionItem | `distributionItems() returns HasMany instance` | [ ] |
| Method: totalEarned() sums all distribution items | `totalEarned() returns correct cumulative amount` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: EgyptShipping

#### EgyptGovernorate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name_ar, name_en, rate, is_active | `allows mass assignment of fillable fields` | [ ] |
| Cast: rate → decimal:2 | `casts rate to float` | [ ] |
| Cast: is_active → boolean | `casts is_active to boolean` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: FlashSale

#### FlashSale
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, discount_percent, starts_at, ends_at, active | `allows mass assignment of fillable fields` | [ ] |
| Cast: starts_at → datetime | `casts starts_at to datetime` | [ ] |
| Cast: ends_at → datetime | `casts ends_at to datetime` | [ ] |
| Cast: active → boolean | `casts active to boolean` | [ ] |
| Cast: discount_percent → float | `casts discount_percent to float` | [ ] |
| Relationship: products() BelongsToMany Product | `products() returns BelongsToMany instance` | [ ] |
| Method: isRunning() returns true when active & within date range | `isRunning() returns true for active running sale` | [ ] |
| Method: isRunning() returns false when outside date range | `isRunning() returns false when sale has ended` | [ ] |
| Method: isRunning() returns false when not active | `isRunning() returns false when sale is inactive` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: GiftCard

#### GiftCard
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, initial_balance, used_amount, is_active, recipient_email, recipient_name, expires_at, message | `allows mass assignment of fillable fields` | [ ] |
| Cast: initial_balance → float | `casts initial_balance to float` | [ ] |
| Cast: used_amount → float | `casts used_amount to float` | [ ] |
| Cast: is_active → boolean | `casts is_active to boolean` | [ ] |
| Cast: expires_at → date | `casts expires_at to date` | [ ] |
| Accessor: remaining_balance = max(0, initial - used) | `getRemainingBalanceAttribute() returns correct balance` | [ ] |
| Accessor: remaining_balance never goes below 0 | `getRemainingBalanceAttribute() floors at zero` | [ ] |
| Method: isUsable() returns true when active, has balance, not expired | `isUsable() returns true for valid gift card` | [ ] |
| Method: isUsable() returns false when expired | `isUsable() returns false for expired gift card` | [ ] |
| Method: isUsable() returns false when balance is 0 | `isUsable() returns false when balance depleted` | [ ] |
| Method: generateCode() returns XXX-XXXX-XXXX format | `generateCode() generates code in correct format` | [ ] |
| Method: generateCode() returns unique codes | `generateCode() generates unique codes` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: Loyalty

#### CustomerLoyaltyPoints
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, balance | `allows mass assignment of fillable fields` | [ ] |
| Relationship: transactions() HasMany CustomerLoyaltyTransaction | `transactions() returns HasMany instance` | [ ] |

**Factory:** ❌ (needs creation)

#### CustomerLoyaltyTransaction
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, order_id, type, points, balance_after, description | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: ProductQA

#### ProductQuestion
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, customer_id, customer_name, customer_email, question, answer, status, is_published | `allows mass assignment of fillable fields` | [ ] |
| Cast: is_published → boolean | `casts is_published to boolean` | [ ] |
| Scope: published() → status=approved AND is_published=true | `scopePublished returns only approved published questions` | [ ] |
| Scope: published() excludes unapproved | `scopePublished excludes unapproved questions` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: PushNotification

#### PushSubscription
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, endpoint, p256dh, auth | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌ (needs creation)

#### PushCampaign
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, body, icon, url, sent_count | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: Referral

#### CustomerReferral
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, referral_code, times_used, total_earned | `allows mass assignment of fillable fields` | [ ] |
| Cast: total_earned → decimal:4 | `casts total_earned to float` | [ ] |
| Cast: times_used → integer | `casts times_used to integer` | [ ] |

**Factory:** ❌ (needs creation)

#### ReferralConversion
| Feature | Test | Status |
|---------|------|--------|
| Fillable: referral_code, referrer_customer_id, referred_customer_id, referred_email, order_id, status, rewarded_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: rewarded_at → datetime | `casts rewarded_at to datetime` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: ShopTheLook

#### ProductLookItem
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, look_product_id, sort_order | `allows mass assignment of fillable fields` | [ ] |
| Relationship: lookProduct() BelongsTo Product | `lookProduct() returns BelongsTo instance` | [ ] |
| No timestamps (timestamps = false) | `model has no timestamps` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: SizeGuide

#### SizeChart
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, gender, type, image, image_overlays, column_headers | `allows mass assignment of fillable fields` | [ ] |
| Cast: image_overlays → array | `casts image_overlays to array` | [ ] |
| Cast: column_headers → array | `casts column_headers to array` | [ ] |
| Relationship: rows() HasMany SizeChartRow (ordered by sort_order) | `rows() returns HasMany instance` | [ ] |
| Relationship: products() BelongsToMany Product | `products() returns BelongsToMany instance` | [ ] |

**Factory:** ❌ (needs creation)

#### SizeChartRow
| Feature | Test | Status |
|---------|------|--------|
| Fillable: size_chart_id, label, sort_order, eu_size, uk_size, us_size, chest/waist/hips/height ranges, product measurements | `allows mass assignment of fillable fields` | [ ] |
| Casts: all measurement fields → float | `casts measurement fields to float` | [ ] |
| Method: range($field) returns "min-max" or single value | `range() returns formatted range string` | [ ] |
| Method: range($field) returns null when both values null | `range() returns null when values are null` | [ ] |
| Static: toIn($cm) converts cm to inches (÷2.54) | `toIn() converts centimeters to inches correctly` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: SocialCommerce

#### SocialChannelPlatform
| Feature | Test | Status |
|---------|------|--------|
| Fillable: channel_id, platform, is_active, page_url, page_id, pixel_id, app_id, app_secret, access_token, catalog_id, phone_number_id, last_synced_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: is_active → boolean | `casts is_active to boolean` | [ ] |
| Cast: last_synced_at → datetime | `casts last_synced_at to datetime` | [ ] |
| Cast: app_id → encrypted | `casts app_id to encrypted` | [ ] |
| Cast: app_secret → encrypted | `casts app_secret to encrypted` | [ ] |
| Cast: access_token → encrypted | `casts access_token to encrypted` | [ ] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [ ] |
| Relationship: productSyncs() HasMany SocialProductSync | `productSyncs() returns HasMany instance` | [ ] |
| Relationship: orders() HasMany SocialOrder | `orders() returns HasMany instance` | [ ] |

**Factory:** ❌ (needs creation)

#### SocialOrder
| Feature | Test | Status |
|---------|------|--------|
| Fillable: social_channel_platform_id, order_id, external_order_id, platform_data, sync_status, error_message | `allows mass assignment of fillable fields` | [ ] |
| Cast: platform_data → array | `casts platform_data to array` | [ ] |
| Relationship: platform() BelongsTo SocialChannelPlatform | `platform() returns BelongsTo instance` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

#### SocialProductSync
| Feature | Test | Status |
|---------|------|--------|
| Fillable: social_channel_platform_id, product_id, external_product_id, sync_status, error_message, synced_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: synced_at → datetime | `casts synced_at to datetime` | [ ] |
| Relationship: platform() BelongsTo SocialChannelPlatform | `platform() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: StoreLocator

#### StoreLocator
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, address, latitude, longitude, phone, working_hours, image, status | `allows mass assignment of fillable fields` | [ ] |
| Cast: working_hours → array | `casts working_hours to array` | [ ] |
| Cast: status → boolean | `casts status to boolean` | [ ] |
| Cast: latitude → float | `casts latitude to float` | [ ] |
| Cast: longitude → float | `casts longitude to float` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: Wallet

#### CustomerWallet
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, balance | `allows mass assignment of fillable fields` | [ ] |
| Cast: balance → decimal:4 | `casts balance to float` | [ ] |
| Relationship: transactions() HasMany CustomerWalletTransaction | `transactions() returns HasMany instance` | [ ] |

**Factory:** ❌ (needs creation)

#### CustomerWalletTransaction
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, order_id, type, amount, balance_after, note | `allows mass assignment of fillable fields` | [ ] |
| Cast: amount → decimal:4 | `casts amount to float` | [ ] |
| Cast: balance_after → decimal:4 | `casts balance_after to float` | [ ] |

**Factory:** ❌ (needs creation)

---

## Core Bagisto Packages

---

### Package: Admin

#### ChannelAdSpend
| Feature | Test | Status |
|---------|------|--------|
| Fillable: channel_id, amount, start_date, end_date, source, notes | `allows mass assignment of fillable fields` | [ ] |
| Cast: amount → decimal:4 | `casts amount to float` | [ ] |
| Cast: start_date → date | `casts start_date to date` | [ ] |
| Cast: end_date → date | `casts end_date to date` | [ ] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [ ] |

**Factory:** ❌ (needs creation)

---

### Package: Attribute

#### Attribute
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, admin_name, type, enable_wysiwyg, position, is_required, is_unique, validation, regex, value_per_locale, value_per_channel, default_value, is_filterable, is_configurable, is_visible_on_front, is_user_defined, swatch_type, is_comparable | `allows mass assignment of fillable fields` | [ ] |
| Relationship: options() HasMany AttributeOption | `options() returns HasMany instance` | [ ] |
| Scope: filterableAttributes() → is_filterable=1, swatch_type≠image, ordered by position | `scopeFilterableAttributes returns only filterable attributes` | [ ] |
| Accessor: column_name → derived from attribute type | `getColumnNameAttribute() returns correct column name for type` | [ ] |
| Accessor: validations → rules based on attribute type | `getValidationsAttribute() returns validation rules` | [ ] |
| Property: $attributeTypeFields maps types to columns | `attributeTypeFields contains expected type mappings` | [ ] |

**Factory:** ✅

#### AttributeFamily
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: attribute_groups() HasMany AttributeGroup (ordered by position) | `attribute_groups() returns HasMany instance` | [ ] |
| Relationship: products() HasMany Product | `products() returns HasMany instance` | [ ] |
| Accessor: custom_attributes → custom attributes collection | `getCustomAttributesAttribute() returns collection` | [ ] |
| Accessor: configurable_attributes → configurable select attributes | `getConfigurableAttributesAttribute() returns only configurable` | [ ] |
| Method: getComparableAttributesBelongsToFamily() | `getComparableAttributesBelongsToFamily() returns comparable attributes` | [ ] |

**Factory:** ✅

#### AttributeGroup
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, column, position, is_user_defined | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: custom_attributes() BelongsToMany Attribute (via attribute_group_mappings, pivot: position) | `custom_attributes() returns BelongsToMany instance` | [ ] |

**Factory:** ❌

#### AttributeOption
| Feature | Test | Status |
|---------|------|--------|
| Fillable: admin_name, swatch_value, sort_order, attribute_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: attribute() BelongsTo Attribute | `attribute() returns BelongsTo instance` | [ ] |
| Accessor: swatch_value_url → URL when swatch is image, null otherwise | `getSwatchValueUrlAttribute() returns URL for image swatch` | [ ] |
| Translated: label | `label attribute is translatable` | [ ] |

**Factory:** ✅

#### AttributeOptionTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: label | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

#### AttributeTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

---

### Package: BookingProduct

#### Booking
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, from, to, allow_cancellation, order_item_id, booking_product_event_ticket_id, product_id, order_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: order_item() BelongsTo OrderItem | `order_item() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### BookingProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: location, show_location, type, qty, available_every_week, available_from, available_to, allow_cancellation, product_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: available_from → datetime | `casts available_from to datetime` | [ ] |
| Cast: available_to → datetime | `casts available_to to datetime` | [ ] |
| Relationship: default_slot() HasOne BookingProductDefaultSlot | `default_slot() returns HasOne instance` | [ ] |
| Relationship: appointment_slot() HasOne BookingProductAppointmentSlot | `appointment_slot() returns HasOne instance` | [ ] |
| Relationship: event_tickets() HasMany BookingProductEventTicket | `event_tickets() returns HasMany instance` | [ ] |
| Relationship: rental_slot() HasOne BookingProductRentalSlot | `rental_slot() returns HasOne instance` | [ ] |
| Relationship: table_slot() HasOne BookingProductTableSlot | `table_slot() returns HasOne instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Eager loads: default_slot, appointment_slot, event_tickets, rental_slot, table_slot | `model eager loads all slot relations` | [ ] |

**Factory:** ❌

#### BookingProductAppointmentSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: duration, break_time, same_slot_all_days, slots, allow_slot_overlap, booking_product_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: slots → array | `casts slots to array` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

#### BookingProductDefaultSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: booking_type, duration, break_time, slots, allow_slot_overlap, booking_product_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: slots → array | `casts slots to array` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: booking_product() BelongsTo BookingProduct | `booking_product() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### BookingProductEventTicket
| Feature | Test | Status |
|---------|------|--------|
| Fillable: price, qty, special_price, special_price_from, special_price_to, booking_product_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Translated: name, description | `name and description are translatable` | [ ] |

**Factory:** ❌

#### BookingProductEventTicketTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

#### BookingProductRentalSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: renting_type, daily_price, hourly_price, same_slot_all_days, slots, booking_product_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: slots → array | `casts slots to array` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

#### BookingProductTableSlot
| Feature | Test | Status |
|---------|------|--------|
| Fillable: price_type, guest_limit, duration, break_time, prevent_scheduling_before, same_slot_all_days, slots, allow_slot_overlap, booking_product_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: slots → array | `casts slots to array` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

---

### Package: CartRule

#### CartRule
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, starts_from, ends_till, status, coupon_type, use_auto_generation, usage_per_customer, uses_per_coupon, times_used, condition_type, conditions, actions, end_other_rules, uses_attribute_conditions, action_type, discount_amount, discount_quantity, discount_step, apply_to_shipping, free_shipping, sort_order | `allows mass assignment of fillable fields` | [ ] |
| Cast: conditions → array | `casts conditions to array` | [ ] |
| Relationship: cart_rule_channels() BelongsToMany Channel | `cart_rule_channels() returns BelongsToMany instance` | [ ] |
| Relationship: cart_rule_customer_groups() BelongsToMany CustomerGroup | `cart_rule_customer_groups() returns BelongsToMany instance` | [ ] |
| Relationship: cart_rule_coupon() HasOne CartRuleCoupon | `cart_rule_coupon() returns HasOne instance` | [ ] |
| Relationship: coupon_code() HasOne CartRuleCoupon (is_primary=1) | `coupon_code() returns only primary coupon` | [ ] |
| Accessor: coupon_code → primary coupon code string | `getCouponCodeAttribute() returns primary coupon code` | [ ] |

**Factory:** ✅

#### CartRuleCoupon
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, usage_limit, usage_per_customer, times_used, type, cart_rule_id, expired_at, is_primary | `allows mass assignment of fillable fields` | [ ] |
| Relationship: cart_rule() BelongsTo CartRule | `cart_rule() returns BelongsTo instance` | [ ] |
| Relationship: coupon_usage() HasMany CartRuleCouponUsage | `coupon_usage() returns HasMany instance` | [ ] |

**Factory:** ✅

#### CartRuleCouponAssignment
| Feature | Test | Status |
|---------|------|--------|
| Fillable: cart_rule_coupon_id, phone | `allows mass assignment of fillable fields` | [ ] |
| Relationship: coupon() BelongsTo CartRuleCoupon | `coupon() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### CartRuleCouponUsage
| Feature | Test | Status |
|---------|------|--------|
| No timestamps | `model has no timestamps` | [ ] |
| Guarded (all except timestamps) | `model uses guarded instead of fillable` | [ ] |

**Factory:** ❌

#### CartRuleCustomer
| Feature | Test | Status |
|---------|------|--------|
| Fillable: times_used, cart_rule_id, customer_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

#### CartRuleTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: label | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

---

### Package: CatalogRule

#### CatalogRule
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, starts_from, ends_till, status, condition_type, conditions, end_other_rules, action_type, discount_amount, sort_order | `allows mass assignment of fillable fields` | [ ] |
| Cast: conditions → array | `casts conditions to array` | [ ] |
| Relationship: channels() BelongsToMany Channel | `channels() returns BelongsToMany instance` | [ ] |
| Relationship: customer_groups() BelongsToMany CustomerGroup | `customer_groups() returns BelongsToMany instance` | [ ] |
| Relationship: catalog_rule_products() HasMany CatalogRuleProduct | `catalog_rule_products() returns HasMany instance` | [ ] |
| Relationship: catalog_rule_product_prices() HasMany CatalogRuleProductPrice | `catalog_rule_product_prices() returns HasMany instance` | [ ] |

**Factory:** ✅

#### CatalogRuleProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: starts_from, ends_till, discount_amount, action_type, end_other_rules, sort_order, catalog_rule_id, channel_id, customer_group_id, product_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: catalog_rule() BelongsTo CatalogRule | `catalog_rule() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [ ] |
| Relationship: customer_group() BelongsTo CustomerGroup | `customer_group() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### CatalogRuleProductPrice
| Feature | Test | Status |
|---------|------|--------|
| Fillable: price, rule_date, starts_from, ends_till, catalog_rule_id, channel_id, customer_group_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ❌

---

### Package: Category

#### Category
| Feature | Test | Status |
|---------|------|--------|
| Fillable: position, status, display_mode, parent_id, additional | `allows mass assignment of fillable fields` | [ ] |
| Relationship: products() BelongsToMany Product | `products() returns BelongsToMany instance` | [ ] |
| Relationship: filterableAttributes() BelongsToMany Attribute | `filterableAttributes() returns BelongsToMany instance` | [ ] |
| Accessor: url → localized category URL | `getUrlAttribute() returns valid URL string` | [ ] |
| Accessor: logo_url → Storage URL for logo | `getLogoUrlAttribute() returns URL or null` | [ ] |
| Accessor: banner_url → Storage URL for banner | `getBannerUrlAttribute() returns URL or null` | [ ] |
| Translated: name, description, slug, meta_title, meta_description, meta_keywords | `name is translatable` | [ ] |
| Uses NodeTrait for nested set | `parent_id relationship works correctly` | [ ] |

**Factory:** ✅

#### CategoryTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, slug, meta_title, meta_description, meta_keywords, locale_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ✅

---

### Package: Checkout

#### Cart
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → json | `casts additional to array` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [ ] |
| Relationship: items() HasMany CartItem (parent_id null) | `items() returns HasMany instance` | [ ] |
| Relationship: all_items() HasMany CartItem | `all_items() returns HasMany instance` | [ ] |
| Relationship: billing_address() HasOne CartAddress (billing) | `billing_address() returns billing address` | [ ] |
| Relationship: shipping_address() HasOne CartAddress (shipping) | `shipping_address() returns shipping address` | [ ] |
| Relationship: payment() HasOne CartPayment | `payment() returns HasOne instance` | [ ] |
| Method: haveStockableItems() | `haveStockableItems() returns true when cart has stockable items` | [ ] |
| Method: hasOnlyStockableItems() | `hasOnlyStockableItems() returns correct boolean` | [ ] |
| Method: hasDownloadableItems() | `hasDownloadableItems() returns correct boolean` | [ ] |

**Factory:** ✅

#### CartAddress
| Feature | Test | Status |
|---------|------|--------|
| Constant: ADDRESS_TYPE_SHIPPING = 'cart_shipping' | `ADDRESS_TYPE_SHIPPING constant is defined` | [ ] |
| Constant: ADDRESS_TYPE_BILLING = 'cart_billing' | `ADDRESS_TYPE_BILLING constant is defined` | [ ] |
| Relationship: shipping_rates() HasMany CartShippingRate | `shipping_rates() returns HasMany instance` | [ ] |
| Relationship: cart() BelongsTo Cart | `cart() returns BelongsTo instance` | [ ] |
| Global scope filters by address_type | `default scope limits to correct address_type` | [ ] |

**Factory:** ✅

#### CartItem
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [ ] |
| Relationship: product() HasOne Product | `product() returns HasOne instance` | [ ] |
| Relationship: parent() BelongsTo CartItem | `parent() returns BelongsTo instance` | [ ] |
| Relationship: children() HasMany CartItem | `children() returns HasMany instance` | [ ] |

**Factory:** ✅

#### CartPayment
| Feature | Test | Status |
|---------|------|--------|
| Custom table: cart_payment | `model uses correct table name` | [ ] |

**Factory:** ✅

#### CartShippingRate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: carrier, carrier_title, method, method_title, method_description, price, base_price, discount_amount, base_discount_amount, tax_percent, tax_amount, base_tax_amount | `allows mass assignment of fillable fields` | [ ] |
| Relationship: shipping_address() BelongsTo CartAddress | `shipping_address() returns BelongsTo instance` | [ ] |

**Factory:** ✅

---

### Package: CMS

#### Page
| Feature | Test | Status |
|---------|------|--------|
| Fillable: layout | `allows mass assignment of fillable fields` | [ ] |
| Relationship: channels() BelongsToMany Channel | `channels() returns BelongsToMany instance` | [ ] |
| Translated: content, meta_description, meta_title, page_title, meta_keywords, html_content, url_key | `page_title is translatable` | [ ] |

**Factory:** ✅

#### PageTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: page_title, url_key, html_content, meta_title, meta_description, meta_keywords, locale, cms_page_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |

**Factory:** ✅

---

### Package: Core

#### Address
| Feature | Test | Status |
|---------|------|--------|
| Guarded: id, created_at, updated_at | `mass assignment respects guarded fields` | [ ] |
| Cast: use_for_shipping → boolean | `casts use_for_shipping to boolean` | [ ] |
| Cast: default_address → boolean | `casts default_address to boolean` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |
| Accessor: name → first_name + last_name | `getNameAttribute() returns full name` | [ ] |

**Factory:** ❌

#### Channel
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, description, theme, hostname, default_locale_id, base_currency_id, root_category_id, home_seo, is_maintenance_on, maintenance_mode_text, allowed_ips | `allows mass assignment of fillable fields` | [ ] |
| Cast: home_seo → array | `casts home_seo to array` | [ ] |
| Relationship: locales() BelongsToMany Locale | `locales() returns BelongsToMany instance` | [ ] |
| Relationship: default_locale() BelongsTo Locale | `default_locale() returns BelongsTo instance` | [ ] |
| Relationship: currencies() BelongsToMany Currency | `currencies() returns BelongsToMany instance` | [ ] |
| Relationship: base_currency() BelongsTo Currency | `base_currency() returns BelongsTo instance` | [ ] |
| Relationship: root_category() BelongsTo Category | `root_category() returns BelongsTo instance` | [ ] |
| Accessor: logo_url → Storage URL | `getLogoUrlAttribute() returns URL or null` | [ ] |
| Accessor: favicon_url → Storage URL | `getFaviconUrlAttribute() returns URL or null` | [ ] |

**Factory:** ✅

#### CoreConfig
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, value, channel_code, locale_code | `allows mass assignment of fillable fields` | [ ] |
| Custom table: core_config | `model uses correct table name` | [ ] |
| Hidden: token | `token field is hidden` | [ ] |

**Factory:** ✅

#### Country
| Feature | Test | Status |
|---------|------|--------|
| No timestamps | `model has no timestamps` | [ ] |
| Translated: name | `name is translatable` | [ ] |
| Relationship: states() HasMany CountryState | `states() returns HasMany instance` | [ ] |

**Factory:** ❌

#### Currency
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, symbol, decimal, group_separator, decimal_separator, currency_position | `allows mass assignment of fillable fields` | [ ] |
| Relationship: exchange_rate() HasOne CurrencyExchangeRate | `exchange_rate() returns HasOne instance` | [ ] |
| Mutator: setCodeAttribute() converts code to uppercase | `setCodeAttribute() stores code in uppercase` | [ ] |

**Factory:** ✅

#### CurrencyExchangeRate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: target_currency, rate | `allows mass assignment of fillable fields` | [ ] |
| Relationship: currency() BelongsTo Currency | `currency() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### Locale
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, direction | `allows mass assignment of fillable fields` | [ ] |
| Accessor: logo_url → Storage URL | `getLogoUrlAttribute() returns URL or null` | [ ] |

**Factory:** ✅

#### SubscribersList
| Feature | Test | Status |
|---------|------|--------|
| Fillable: email, is_subscribed, token, customer_id, channel_id | `allows mass assignment of fillable fields` | [ ] |
| Hidden: token | `token field is hidden from serialization` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ✅

---

### Package: Customer

#### Customer
| Feature | Test | Status |
|---------|------|--------|
| Fillable: first_name, last_name, gender, date_of_birth, email, phone, password, api_token, token, customer_group_id, channel_id, subscribed_to_news_letter, status, is_verified, is_suspended | `allows mass assignment of fillable fields` | [ ] |
| Cast: subscribed_to_news_letter → boolean | `casts subscribed_to_news_letter to boolean` | [ ] |
| Hidden: password, api_token, remember_token | `sensitive fields are hidden` | [ ] |
| Relationship: group() BelongsTo CustomerGroup | `group() returns BelongsTo instance` | [ ] |
| Relationship: addresses() HasMany CustomerAddress | `addresses() returns HasMany instance` | [ ] |
| Relationship: default_address() HasOne CustomerAddress (default) | `default_address() returns only default address` | [ ] |
| Relationship: orders() HasMany Order | `orders() returns HasMany instance` | [ ] |
| Relationship: invoices() HasManyThrough Invoice through Order | `invoices() returns HasManyThrough instance` | [ ] |
| Relationship: wishlist_items() HasMany Wishlist | `wishlist_items() returns HasMany instance` | [ ] |
| Relationship: reviews() HasMany ProductReview | `reviews() returns HasMany instance` | [ ] |
| Accessor: name → first_name + last_name | `getNameAttribute() returns full name` | [ ] |
| Accessor: image_url → Storage URL | `getImageUrlAttribute() returns URL or null` | [ ] |
| Method: emailExists($email) checks if email is taken | `emailExists() returns true for existing email` | [ ] |
| Method: emailExists($email) returns false for new email | `emailExists() returns false for unused email` | [ ] |
| Method: isWishlistShared() checks shared wishlist items | `isWishlistShared() returns boolean` | [ ] |

**Factory:** ✅

#### CustomerAddress
| Feature | Test | Status |
|---------|------|--------|
| Constant: ADDRESS_TYPE = 'customer' | `ADDRESS_TYPE constant is defined` | [ ] |
| Global scope filters by address_type = 'customer' | `global scope limits to customer addresses` | [ ] |

**Factory:** ✅

#### CustomerGroup
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, code, is_user_defined | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customers() HasMany Customer | `customers() returns HasMany instance` | [ ] |

**Factory:** ✅

#### CompareItem
| Feature | Test | Status |
|---------|------|--------|
| Guarded: [] (all fillable) | `model allows all field assignment` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### Wishlist
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### CustomerNote
| Feature | Test | Status |
|---------|------|--------|
| Fillable: note, customer_id, customer_notified | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### CustomerLoyaltyPoint (Customer package)
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, balance | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |
| Relationship: transactions() HasMany CustomerLoyaltyTransaction | `transactions() returns HasMany instance` | [ ] |

**Factory:** ❌

#### CustomerLoyaltyTransaction (Customer package)
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, points, type, description, order_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### CustomerReferral (Customer package)
| Feature | Test | Status |
|---------|------|--------|
| Fillable: referrer_id, code, referred_customer_id, order_placed, reward_issued | `allows mass assignment of fillable fields` | [ ] |
| Relationship: referrer() BelongsTo Customer (referrer_id) | `referrer() returns BelongsTo instance` | [ ] |
| Relationship: referredCustomer() BelongsTo Customer (referred_customer_id) | `referredCustomer() returns BelongsTo instance` | [ ] |

**Factory:** ❌

---

### Package: DataGrid

#### SavedFilter
| Feature | Test | Status |
|---------|------|--------|
| Fillable: user_id, src, name, applied | `allows mass assignment of fillable fields` | [ ] |
| Cast: applied → json | `casts applied to array` | [ ] |

**Factory:** ❌

---

### Package: DataTransfer

#### Import
| Feature | Test | Status |
|---------|------|--------|
| Fillable: state, process_in_queue, type, action, validation_strategy, allowed_errors, processed_rows_count, invalid_rows_count, errors_count, errors, field_separator, file_path, images_directory_path, error_file_path, summary, started_at, completed_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: summary → array | `casts summary to array` | [ ] |
| Cast: errors → array | `casts errors to array` | [ ] |
| Cast: started_at → datetime | `casts started_at to datetime` | [ ] |
| Cast: completed_at → datetime | `casts completed_at to datetime` | [ ] |
| Relationship: batches() HasMany ImportBatch | `batches() returns HasMany instance` | [ ] |

**Factory:** ❌

#### ImportBatch
| Feature | Test | Status |
|---------|------|--------|
| Fillable: state, data, summary, import_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: summary → array | `casts summary to array` | [ ] |
| Cast: data → array | `casts data to array` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: import() BelongsTo Import | `import() returns BelongsTo instance` | [ ] |

**Factory:** ❌

---

### Package: GDPR

#### GDPRDataRequest
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, email, status, type, message, revoked_at | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ❌

---

### Package: Inventory

#### InventorySource
| Feature | Test | Status |
|---------|------|--------|
| Guarded: _token | `mass assignment excludes _token` | [ ] |

**Factory:** ✅

---

### Package: Marketing

#### Campaign
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, subject, status, channel_id, customer_group_id, marketing_template_id, spooling, marketing_event_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: event() BelongsTo Event | `event() returns BelongsTo instance` | [ ] |
| Relationship: channel() BelongsTo Channel | `channel() returns BelongsTo instance` | [ ] |
| Relationship: customer_group() BelongsTo CustomerGroup | `customer_group() returns BelongsTo instance` | [ ] |
| Relationship: email_template() BelongsTo Template | `email_template() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### Event
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, date | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ✅

#### SearchSynonym
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, terms | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ✅

#### SearchTerm
| Feature | Test | Status |
|---------|------|--------|
| Fillable: term, results, uses, redirect_url, display_in_suggested_terms, locale, channel_id | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ✅

#### Template
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, status, content | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ✅

#### URLRewrite
| Feature | Test | Status |
|---------|------|--------|
| Fillable: entity_type, request_path, target_path, redirect_type, locale | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ✅

---

### Package: Notification

#### Notification
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, read, order_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |

**Factory:** ❌

---

### Package: Product

#### Product
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, attribute_family_id, sku, parent_id | `allows mass assignment of fillable fields` | [ ] |
| Cast: additional → array | `casts additional to array` | [ ] |
| Relationship: attribute_family() BelongsTo AttributeFamily | `attribute_family() returns BelongsTo instance` | [ ] |
| Relationship: parent() BelongsTo Product | `parent() returns BelongsTo instance` | [ ] |
| Relationship: variants() HasMany Product | `variants() returns HasMany instance` | [ ] |
| Relationship: categories() BelongsToMany Category | `categories() returns BelongsToMany instance` | [ ] |
| Relationship: images() HasMany ProductImage | `images() returns HasMany instance` | [ ] |
| Relationship: reviews() HasMany ProductReview | `reviews() returns HasMany instance` | [ ] |
| Relationship: approvedReviews() HasMany ProductReview (filtered) | `approvedReviews() returns only approved reviews` | [ ] |
| Relationship: inventories() HasMany ProductInventory | `inventories() returns HasMany instance` | [ ] |
| Relationship: inventory_sources() BelongsToMany InventorySource | `inventory_sources() returns BelongsToMany instance` | [ ] |
| Relationship: super_attributes() BelongsToMany Attribute | `super_attributes() returns BelongsToMany instance` | [ ] |
| Relationship: related_products() BelongsToMany Product | `related_products() returns BelongsToMany instance` | [ ] |
| Relationship: up_sells() BelongsToMany Product | `up_sells() returns BelongsToMany instance` | [ ] |
| Relationship: cross_sells() BelongsToMany Product | `cross_sells() returns BelongsToMany instance` | [ ] |
| Method: isSaleable() | `isSaleable() returns boolean` | [ ] |
| Method: isStockable() | `isStockable() returns boolean` | [ ] |
| Accessor: base_image_url → first product image URL | `getBaseImageUrlAttribute() returns URL or null` | [ ] |

**Factory:** ✅

#### ProductAttributeValue
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, attribute_id, locale, channel, unique_id, text_value, boolean_value, integer_value, float_value, datetime_value, date_value, json_value | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: attribute() BelongsTo Attribute | `attribute() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Property: $attributeTypeFields maps types to value columns | `attributeTypeFields contains all expected type mappings` | [ ] |

**Factory:** ✅

#### ProductImage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, path, product_id, position | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Accessor: url → full image URL | `getUrlAttribute() returns URL string` | [ ] |

**Factory:** ❌

#### ProductInventory
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, product_id, inventory_source_id, vendor_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: inventory_source() BelongsTo InventorySource | `inventory_source() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### ProductReview
| Feature | Test | Status |
|---------|------|--------|
| Fillable: comment, title, rating, status, product_id, customer_id, name | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Relationship: images() HasMany ProductReviewAttachment | `images() returns HasMany instance` | [ ] |

**Factory:** ✅

#### ProductReviewAttachment
| Feature | Test | Status |
|---------|------|--------|
| Fillable: path, review_id, type, mime_type | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: review() BelongsTo ProductReview | `review() returns BelongsTo instance` | [ ] |
| Accessor: url → full attachment URL | `getUrlAttribute() returns URL string` | [ ] |

**Factory:** ✅

#### ProductDownloadableLink
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, price, url, file, file_name, type, sample_url, sample_file, sample_file_name, sample_type, sort_order, product_id, downloads | `allows mass assignment of fillable fields` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Accessor: file_url → full file URL | `getFileUrlAttribute() returns URL string` | [ ] |
| Accessor: sample_file_url → full sample URL | `getSampleFileUrlAttribute() returns URL string` | [ ] |

**Factory:** ✅

#### ProductGroupedProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, sort_order, product_id, associated_product_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Relationship: associated_product() BelongsTo Product | `associated_product() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### ProductCustomerGroupPrice
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, value_type, value, product_id, customer_group_id, unique_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Relationship: customer_group() BelongsTo CustomerGroup | `customer_group() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### StockNotification
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_id, email, phone, channel_id, notified | `allows mass assignment of fillable fields` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### ProductBundleOption
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, is_required, sort_order, product_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Translated: label | `label is translatable` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |
| Relationship: bundle_option_products() HasMany ProductBundleOptionProduct | `bundle_option_products() returns HasMany instance` | [ ] |

**Factory:** ✅

#### ProductBundleOptionProduct
| Feature | Test | Status |
|---------|------|--------|
| Fillable: qty, is_user_defined, sort_order, is_default, product_bundle_option_id, product_id | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Relationship: bundle_option() BelongsTo ProductBundleOption | `bundle_option() returns BelongsTo instance` | [ ] |
| Relationship: product() BelongsTo Product | `product() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### ProductVideo
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, path, product_id, position | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Accessor: url → full video URL | `getUrlAttribute() returns URL string` | [ ] |

**Factory:** ❌

---

### Package: RMA

#### RMA
| Feature | Test | Status |
|---------|------|--------|
| Fillable: information, rma_status_id, order_id, status, package_condition | `allows mass assignment of fillable fields` | [ ] |
| Relationship: status() BelongsTo RMAStatus | `status() returns BelongsTo instance` | [ ] |
| Relationship: images() HasMany RMAImage | `images() returns HasMany instance` | [ ] |
| Relationship: item() HasOne RMAItem | `item() returns HasOne instance` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: messages() HasMany RMAMessage | `messages() returns HasMany instance` | [ ] |
| Relationship: additionalFields() HasMany RMAAdditionalField | `additionalFields() returns HasMany instance` | [ ] |

**Factory:** ❌

#### RMACustomField
| Feature | Test | Status |
|---------|------|--------|
| Fillable: status, code, label, type, is_required, position, input_validation | `allows mass assignment of fillable fields` | [ ] |
| Relationship: options() HasMany RMACustomFieldOption | `options() returns HasMany instance` | [ ] |

**Factory:** ❌

#### RMACustomFieldOption
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_custom_field_id, name, value | `allows mass assignment of fillable fields` | [ ] |
| Relationship: rmaCustomField() BelongsTo RMACustomField | `rmaCustomField() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### RMAItem
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_id, quantity, order_item_id, resolution, rma_reason_id, variant_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: rma() BelongsTo RMA | `rma() returns BelongsTo instance` | [ ] |
| Relationship: orderItem() BelongsTo OrderItem | `orderItem() returns BelongsTo instance` | [ ] |
| Relationship: product() HasOneThrough Product via OrderItem | `product() returns HasOneThrough instance` | [ ] |

**Factory:** ❌

#### RMAMessage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: message, rma_id, is_admin, attachment_path, attachment | `allows mass assignment of fillable fields` | [ ] |
| Relationship: rma() BelongsTo RMA | `rma() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### RMAReason
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, status, position | `allows mass assignment of fillable fields` | [ ] |
| Relationship: reasonResolutions() HasMany RMAReasonResolution | `reasonResolutions() returns HasMany instance` | [ ] |

**Factory:** ❌

#### RMAReasonResolution
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_reason_id, resolution_type | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌

#### RMARule
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, status, return_period, default | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌

#### RMAStatus
| Feature | Test | Status |
|---------|------|--------|
| Fillable: title, status, color | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌

#### RMAAdditionalField
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_id, rma_custom_field_id, value | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customField() BelongsTo RMACustomField | `customField() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### RMAImage
| Feature | Test | Status |
|---------|------|--------|
| Fillable: rma_id, path | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ❌

---

### Package: Sales

#### Order
| Feature | Test | Status |
|---------|------|--------|
| Constant: STATUS_PENDING | `STATUS_PENDING constant is defined` | [ ] |
| Constant: STATUS_PROCESSING | `STATUS_PROCESSING constant is defined` | [ ] |
| Constant: STATUS_COMPLETED | `STATUS_COMPLETED constant is defined` | [ ] |
| Constant: STATUS_CANCELED | `STATUS_CANCELED constant is defined` | [ ] |
| Constant: STATUS_CLOSED | `STATUS_CLOSED constant is defined` | [ ] |
| Constant: STATUS_FRAUD | `STATUS_FRAUD constant is defined` | [ ] |
| Relationship: items() HasMany OrderItem (parent_id null) | `items() returns HasMany instance` | [ ] |
| Relationship: all_items() HasMany OrderItem | `all_items() returns HasMany instance` | [ ] |
| Relationship: shipments() HasMany Shipment | `shipments() returns HasMany instance` | [ ] |
| Relationship: invoices() HasMany Invoice | `invoices() returns HasMany instance` | [ ] |
| Relationship: refunds() HasMany Refund | `refunds() returns HasMany instance` | [ ] |
| Relationship: payment() HasOne OrderPayment | `payment() returns HasOne instance` | [ ] |
| Relationship: addresses() HasMany OrderAddress | `addresses() returns HasMany instance` | [ ] |
| Accessor: customer_full_name | `getCustomerFullNameAttribute() returns full name` | [ ] |
| Accessor: status_label | `getStatusLabelAttribute() returns human-readable status` | [ ] |
| Accessor: base_total_due | `getBaseTotalDueAttribute() returns numeric value` | [ ] |
| Method: canShip() | `canShip() returns false for canceled order` | [ ] |
| Method: canInvoice() | `canInvoice() returns false for completed order` | [ ] |
| Method: canCancel() | `canCancel() returns correct boolean` | [ ] |
| Method: haveStockableItems() | `haveStockableItems() returns boolean` | [ ] |
| Method: hasOpenInvoice() | `hasOpenInvoice() returns boolean` | [ ] |

**Factory:** ✅

#### OrderItem
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: child() HasOne OrderItem | `child() returns HasOne instance` | [ ] |
| Relationship: parent() BelongsTo OrderItem | `parent() returns BelongsTo instance` | [ ] |
| Relationship: children() HasMany OrderItem | `children() returns HasMany instance` | [ ] |
| Relationship: invoice_items() HasMany InvoiceItem | `invoice_items() returns HasMany instance` | [ ] |
| Relationship: shipment_items() HasMany ShipmentItem | `shipment_items() returns HasMany instance` | [ ] |
| Method: canShip() | `canShip() returns correct boolean` | [ ] |
| Method: canInvoice() | `canInvoice() returns correct boolean` | [ ] |
| Method: isStockable() | `isStockable() returns boolean` | [ ] |
| Accessor: qty_to_ship | `getQtyToShipAttribute() returns numeric` | [ ] |
| Accessor: qty_to_invoice | `getQtyToInvoiceAttribute() returns numeric` | [ ] |

**Factory:** ✅

#### OrderAddress
| Feature | Test | Status |
|---------|------|--------|
| Constant: ADDRESS_TYPE_SHIPPING = 'order_shipping' | `ADDRESS_TYPE_SHIPPING constant is defined` | [ ] |
| Constant: ADDRESS_TYPE_BILLING = 'order_billing' | `ADDRESS_TYPE_BILLING constant is defined` | [ ] |
| Default attribute: address_type = ADDRESS_TYPE_BILLING | `default address_type is billing` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### OrderComment
| Feature | Test | Status |
|---------|------|--------|
| Fillable: comment, customer_notified, order_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### OrderPayment
| Feature | Test | Status |
|---------|------|--------|
| Cast: additional → array | `casts additional to array` | [ ] |
| Custom table: order_payment | `model uses correct table name` | [ ] |

**Factory:** ✅

#### Invoice
| Feature | Test | Status |
|---------|------|--------|
| Constant: STATUS_PENDING | `STATUS_PENDING constant is defined` | [ ] |
| Constant: STATUS_PAID | `STATUS_PAID constant is defined` | [ ] |
| Constant: STATUS_REFUNDED | `STATUS_REFUNDED constant is defined` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: items() HasMany InvoiceItem | `items() returns HasMany instance` | [ ] |
| Accessor: status_label | `getStatusLabelAttribute() returns human-readable label` | [ ] |

**Factory:** ✅

#### Refund
| Feature | Test | Status |
|---------|------|--------|
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: items() HasMany RefundItem | `items() returns HasMany instance` | [ ] |
| Accessor: status_label | `getStatusLabelAttribute() returns human-readable label` | [ ] |

**Factory:** ✅

#### Shipment
| Feature | Test | Status |
|---------|------|--------|
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: items() HasMany ShipmentItem | `items() returns HasMany instance` | [ ] |
| Relationship: inventory_source() BelongsTo InventorySource | `inventory_source() returns BelongsTo instance` | [ ] |

**Factory:** ✅

#### DownloadableLinkPurchased
| Feature | Test | Status |
|---------|------|--------|
| Fillable: product_name, name, url, file, file_name, type, download_bought, download_used, status, customer_id, order_id, order_item_id, download_canceled | `allows mass assignment of fillable fields` | [ ] |
| Relationship: order() BelongsTo Order | `order() returns BelongsTo instance` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ❌

#### OrderTransaction
| Feature | Test | Status |
|---------|------|--------|
| Accessor: payment_title | `getPaymentTitleAttribute() returns string` | [ ] |

**Factory:** ✅

---

### Package: Sitemap

#### Sitemap
| Feature | Test | Status |
|---------|------|--------|
| Fillable: additional, file_name, generated_at, path | `allows mass assignment of fillable fields` | [ ] |
| Cast: additional → json | `casts additional to array` | [ ] |
| Accessor: index_file_name | `getIndexFileNameAttribute() returns string` | [ ] |
| Method: deleteFromStorage() removes the sitemap file | `deleteFromStorage() removes file from storage` | [ ] |

**Factory:** ✅

---

### Package: SocialLogin

#### CustomerSocialAccount
| Feature | Test | Status |
|---------|------|--------|
| Fillable: customer_id, provider_name, provider_id | `allows mass assignment of fillable fields` | [ ] |
| Relationship: customer() BelongsTo Customer | `customer() returns BelongsTo instance` | [ ] |

**Factory:** ❌

---

### Package: Tax

#### TaxCategory
| Feature | Test | Status |
|---------|------|--------|
| Fillable: code, name, description | `allows mass assignment of fillable fields` | [ ] |
| Relationship: tax_rates() BelongsToMany TaxRate | `tax_rates() returns BelongsToMany instance` | [ ] |

**Factory:** ✅

#### TaxMap
| Feature | Test | Status |
|---------|------|--------|
| Fillable: tax_category_id, tax_rate_id | `allows mass assignment of fillable fields` | [ ] |

**Factory:** ✅

#### TaxRate
| Feature | Test | Status |
|---------|------|--------|
| Fillable: identifier, is_zip, zip_code, zip_from, zip_to, state, country, tax_rate | `allows mass assignment of fillable fields` | [ ] |
| Relationship: tax_categories() BelongsToMany TaxCategory | `tax_categories() returns BelongsToMany instance` | [ ] |

**Factory:** ✅

---

### Package: Theme

#### ThemeCustomization
| Feature | Test | Status |
|---------|------|--------|
| Fillable: type, name, options, sort_order, status, channel_id, theme_code | `allows mass assignment of fillable fields` | [ ] |
| Cast: options → array | `casts options to array` | [ ] |
| Constants: IMAGE_CAROUSEL, PRODUCT_CAROUSEL, CATEGORY_CAROUSEL, FOOTER_LINKS, STATIC_CONTENT, SERVICES_CONTENT | `all type constants are defined` | [ ] |
| Translated: options | `options is translatable` | [ ] |

**Factory:** ✅

#### ThemeCustomizationTranslation
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, options | `allows mass assignment of fillable fields` | [ ] |
| No timestamps | `model has no timestamps` | [ ] |
| Cast: options → array | `casts options to array` | [ ] |

**Factory:** ✅

---

### Package: User

#### Admin
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, email, password, image, api_token, role_id, status, two_factor_secret, two_factor_enabled, two_factor_backup_codes, two_factor_verified_at | `allows mass assignment of fillable fields` | [ ] |
| Cast: two_factor_backup_codes → array | `casts two_factor_backup_codes to array` | [ ] |
| Cast: two_factor_verified_at → datetime | `casts two_factor_verified_at to datetime` | [ ] |
| Cast: two_factor_enabled → boolean | `casts two_factor_enabled to boolean` | [ ] |
| Hidden: password, api_token, remember_token | `sensitive fields are hidden` | [ ] |
| Relationship: role() BelongsTo Role | `role() returns BelongsTo instance` | [ ] |
| Method: hasPermission($permission) checks ACL | `hasPermission() returns true for allowed permission` | [ ] |
| Method: hasPermission() returns false for denied | `hasPermission() returns false for denied permission` | [ ] |
| Accessor: image_url → Storage URL | `getImageUrlAttribute() returns URL or null` | [ ] |

**Factory:** ✅

#### Role
| Feature | Test | Status |
|---------|------|--------|
| Fillable: name, description, permission_type, permissions | `allows mass assignment of fillable fields` | [ ] |
| Cast: permissions → array | `casts permissions to array` | [ ] |
| Relationship: admins() HasMany Admin | `admins() returns HasMany instance` | [ ] |

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
