<?php

use App\Providers\AppServiceProvider;
use Webkul\AiSupport\Providers\AiSupportServiceProvider;
use Webkul\OrderNotification\Providers\OrderNotificationServiceProvider;
use Webkul\ProductQA\Providers\ProductQAServiceProvider;
use Webkul\Blog\Providers\BlogServiceProvider;
use Webkul\Wallet\Providers\WalletServiceProvider;
use Webkul\PushNotification\Providers\PushNotificationServiceProvider;
use Webkul\Fawry\Providers\FawryServiceProvider;
use Webkul\Paymob\Providers\PaymobServiceProvider;
use Webkul\Valu\Providers\ValuServiceProvider;
use Webkul\Affiliate\Providers\AffiliateServiceProvider;
use Webkul\Bosta\Providers\BostaServiceProvider;
use Webkul\Aramex\Providers\AramexServiceProvider;
use Webkul\GiftCard\Providers\GiftCardServiceProvider;
use Webkul\FlashSale\Providers\FlashSaleServiceProvider;
use Webkul\Loyalty\Providers\LoyaltyServiceProvider;
use Webkul\GoogleShopping\Providers\GoogleShoppingServiceProvider;
use Webkul\Referral\Providers\ReferralServiceProvider;
use Webkul\SmsNotification\Providers\SmsNotificationServiceProvider;
use Webkul\AbandonedCart\Providers\AbandonedCartServiceProvider;
use Webkul\CostManagement\Providers\CostManagementServiceProvider;
use Webkul\Admin\Providers\AdminServiceProvider;
use Webkul\Attribute\Providers\AttributeServiceProvider;
use Webkul\BookingProduct\Providers\BookingProductServiceProvider;
use Webkul\BulkDeal\Providers\BulkDealServiceProvider;
use Webkul\CartRule\Providers\CartRuleServiceProvider;
use Webkul\CatalogRule\Providers\CatalogRuleServiceProvider;
use Webkul\Category\Providers\CategoryServiceProvider;
use Webkul\Checkout\Providers\CheckoutServiceProvider;
use Webkul\CMS\Providers\CMSServiceProvider;
use Webkul\Core\Providers\CoreServiceProvider;
use Webkul\Core\Providers\EnvValidatorServiceProvider;
use Webkul\Customer\Providers\CustomerServiceProvider;
use Webkul\DataGrid\Providers\DataGridServiceProvider;
use Webkul\DataTransfer\Providers\DataTransferServiceProvider;
use Webkul\DebugBar\Providers\DebugBarServiceProvider;
use Webkul\FPC\Providers\FPCServiceProvider;
use Webkul\GDPR\Providers\GDPRServiceProvider;
use Webkul\ImageCache\Providers\ImageCacheServiceProvider;
use Webkul\Installer\Providers\InstallerServiceProvider;
use Webkul\Inventory\Providers\InventoryServiceProvider;
use Webkul\MagicAI\Providers\MagicAIServiceProvider;
use Webkul\Marketing\Providers\MarketingServiceProvider;
use Webkul\Notification\Providers\NotificationServiceProvider;
use Webkul\Payment\Providers\PaymentServiceProvider;
use Webkul\Paypal\Providers\PaypalServiceProvider;
use Webkul\PayU\Providers\PayUServiceProvider;
use Webkul\Product\Providers\ProductServiceProvider;
use Webkul\Razorpay\Providers\RazorpayServiceProvider;
use Webkul\RMA\Providers\RMAServiceProvider;
use Webkul\Rule\Providers\RuleServiceProvider;
use Webkul\Sales\Providers\SalesServiceProvider;
use Webkul\Shipping\Providers\ShippingServiceProvider;
use Webkul\Shop\Providers\ShopServiceProvider;
use Webkul\Sitemap\Providers\SitemapServiceProvider;
use Webkul\EgyptShipping\Providers\EgyptShippingServiceProvider;
use Webkul\ShopTheLook\Providers\ShopTheLookServiceProvider;
use Webkul\SizeGuide\Providers\SizeGuideServiceProvider;
use Webkul\SocialCommerce\Providers\SocialCommerceServiceProvider;
use Webkul\SocialLogin\Providers\SocialLoginServiceProvider;
use Webkul\SocialShare\Providers\SocialShareServiceProvider;
use Webkul\Stripe\Providers\StripeServiceProvider;
use Webkul\Tax\Providers\TaxServiceProvider;
use Webkul\Theme\Providers\ThemeServiceProvider;
use Webkul\User\Providers\UserServiceProvider;

return [
    /**
     * Application service providers.
     */
    AppServiceProvider::class,

    /**
     * Webkul's service providers.
     */
    AbandonedCartServiceProvider::class,
    CostManagementServiceProvider::class,
    AdminServiceProvider::class,
    AttributeServiceProvider::class,
    BookingProductServiceProvider::class,
    BulkDealServiceProvider::class,
    CMSServiceProvider::class,
    CartRuleServiceProvider::class,
    CatalogRuleServiceProvider::class,
    CategoryServiceProvider::class,
    CheckoutServiceProvider::class,
    CoreServiceProvider::class,
    EnvValidatorServiceProvider::class,
    CustomerServiceProvider::class,
    DataGridServiceProvider::class,
    DataTransferServiceProvider::class,
    DebugBarServiceProvider::class,
    FPCServiceProvider::class,
    GDPRServiceProvider::class,
    ImageCacheServiceProvider::class,
    InstallerServiceProvider::class,
    InventoryServiceProvider::class,
    MagicAIServiceProvider::class,
    MarketingServiceProvider::class,
    NotificationServiceProvider::class,
    PayUServiceProvider::class,
    PaymentServiceProvider::class,
    PaypalServiceProvider::class,
    ProductServiceProvider::class,
    RMAServiceProvider::class,
    RazorpayServiceProvider::class,
    RuleServiceProvider::class,
    SalesServiceProvider::class,
    ShippingServiceProvider::class,
    ShopServiceProvider::class,
    SitemapServiceProvider::class,
    EgyptShippingServiceProvider::class,
    ShopTheLookServiceProvider::class,
    SizeGuideServiceProvider::class,
    SocialCommerceServiceProvider::class,
    SocialLoginServiceProvider::class,
    SocialShareServiceProvider::class,
    StripeServiceProvider::class,
    TaxServiceProvider::class,
    ThemeServiceProvider::class,
    UserServiceProvider::class,
    AiSupportServiceProvider::class,
    OrderNotificationServiceProvider::class,
    ProductQAServiceProvider::class,
    BlogServiceProvider::class,
    WalletServiceProvider::class,
    PushNotificationServiceProvider::class,
    FawryServiceProvider::class,
    PaymobServiceProvider::class,
    AffiliateServiceProvider::class,
    BostaServiceProvider::class,
    AramexServiceProvider::class,
    GiftCardServiceProvider::class,
    FlashSaleServiceProvider::class,
    GoogleShoppingServiceProvider::class,
    ReferralServiceProvider::class,
    SmsNotificationServiceProvider::class,
    LoyaltyServiceProvider::class,
    ValuServiceProvider::class,
];
