<?php

use Webkul\AbandonedCart\Tests\AbandonedCartTestCase;
use Webkul\Admin\Tests\AdminTestCase;
use Webkul\Affiliate\Tests\AffiliateTestCase;
use Webkul\AiSupport\Tests\AiSupportTestCase;
use Webkul\Attribute\Tests\AttributeTestCase;
use Webkul\Blog\Tests\BlogTestCase;
use Webkul\BookingProduct\Tests\BookingProductTestCase;
use Webkul\BulkDeal\Tests\BulkDealTestCase;
use Webkul\CartRule\Tests\CartRuleTestCase;
use Webkul\CatalogRule\Tests\CatalogRuleTestCase;
use Webkul\Category\Tests\CategoryTestCase;
use Webkul\Checkout\Tests\CheckoutTestCase;
use Webkul\CMS\Tests\CMSTestCase;
use Webkul\Core\Tests\CoreTestCase;
use Webkul\CostManagement\Tests\CostManagementTestCase;
use Webkul\Customer\Tests\CustomerTestCase;
use Webkul\DataGrid\Tests\DataGridTestCase;
use Webkul\DataTransfer\Tests\DataTransferTestCase;
use Webkul\EgyptShipping\Tests\EgyptShippingTestCase;
use Webkul\FlashSale\Tests\FlashSaleTestCase;
use Webkul\GDPR\Tests\GDPRTestCase;
use Webkul\GiftCard\Tests\GiftCardTestCase;
use Webkul\Installer\Tests\InstallerTestCase;
use Webkul\Inventory\Tests\InventoryTestCase;
use Webkul\Loyalty\Tests\LoyaltyTestCase;
use Webkul\Marketing\Tests\MarketingTestCase;
use Webkul\Notification\Tests\NotificationTestCase;
use Webkul\Payment\Tests\PaymentTestCase;
use Webkul\PayU\Tests\PayUTestCase;
use Webkul\Product\Tests\ProductTestCase;
use Webkul\ProductQA\Tests\ProductQATestCase;
use Webkul\PushNotification\Tests\PushNotificationTestCase;
use Webkul\Razorpay\Tests\RazorpayTestCase;
use Webkul\Referral\Tests\ReferralTestCase;
use Webkul\RMA\Tests\RMATestCase;
use Webkul\Sales\Tests\SalesTestCase;
use Webkul\Shop\Tests\ShopTestCase;
use Webkul\ShopTheLook\Tests\ShopTheLookTestCase;
use Webkul\SizeGuide\Tests\SizeGuideTestCase;
use Webkul\SocialCommerce\Tests\SocialCommerceTestCase;
use Webkul\SocialLogin\Tests\SocialLoginTestCase;
use Webkul\StoreLocator\Tests\StoreLocatorTestCase;
use Webkul\Stripe\Tests\StripeTestCase;
use Webkul\Tax\Tests\TaxTestCase;
use Webkul\User\Tests\UserTestCase;
use Webkul\Wallet\Tests\WalletTestCase;

ini_set('memory_limit', '1024M');

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(AbandonedCartTestCase::class)->in('../packages/Webkul/AbandonedCart/tests');
uses(AdminTestCase::class)->in('../packages/Webkul/Admin/tests');
uses(AffiliateTestCase::class)->in('../packages/Webkul/Affiliate/tests');
uses(AiSupportTestCase::class)->in('../packages/Webkul/AiSupport/tests');
uses(AttributeTestCase::class)->in('../packages/Webkul/Attribute/tests');
uses(BlogTestCase::class)->in('../packages/Webkul/Blog/tests');
uses(BookingProductTestCase::class)->in('../packages/Webkul/BookingProduct/tests');
uses(BulkDealTestCase::class)->in('../packages/Webkul/BulkDeal/tests');
uses(CartRuleTestCase::class)->in('../packages/Webkul/CartRule/tests');
uses(CatalogRuleTestCase::class)->in('../packages/Webkul/CatalogRule/tests');
uses(CategoryTestCase::class)->in('../packages/Webkul/Category/tests');
uses(CheckoutTestCase::class)->in('../packages/Webkul/Checkout/tests');
uses(CMSTestCase::class)->in('../packages/Webkul/CMS/tests');
uses(CoreTestCase::class)->in('../packages/Webkul/Core/tests');
uses(CostManagementTestCase::class)->in('../packages/Webkul/CostManagement/tests');
uses(CustomerTestCase::class)->in('../packages/Webkul/Customer/tests');
uses(DataGridTestCase::class)->in('../packages/Webkul/DataGrid/tests');
uses(DataTransferTestCase::class)->in('../packages/Webkul/DataTransfer/tests');
uses(EgyptShippingTestCase::class)->in('../packages/Webkul/EgyptShipping/tests');
uses(FlashSaleTestCase::class)->in('../packages/Webkul/FlashSale/tests');
uses(GDPRTestCase::class)->in('../packages/Webkul/GDPR/tests');
uses(GiftCardTestCase::class)->in('../packages/Webkul/GiftCard/tests');
uses(InstallerTestCase::class)->in('../packages/Webkul/Installer/tests');
uses(InventoryTestCase::class)->in('../packages/Webkul/Inventory/tests');
uses(LoyaltyTestCase::class)->in('../packages/Webkul/Loyalty/tests');
uses(MarketingTestCase::class)->in('../packages/Webkul/Marketing/tests');
uses(NotificationTestCase::class)->in('../packages/Webkul/Notification/tests');
uses(PaymentTestCase::class)->in('../packages/Webkul/Payment/tests');
uses(PayUTestCase::class)->in('../packages/Webkul/PayU/tests');
uses(ProductTestCase::class)->in('../packages/Webkul/Product/tests');
uses(ProductQATestCase::class)->in('../packages/Webkul/ProductQA/tests');
uses(PushNotificationTestCase::class)->in('../packages/Webkul/PushNotification/tests');
uses(RazorpayTestCase::class)->in('../packages/Webkul/Razorpay/tests');
uses(ReferralTestCase::class)->in('../packages/Webkul/Referral/tests');
uses(RMATestCase::class)->in('../packages/Webkul/RMA/tests');
uses(SalesTestCase::class)->in('../packages/Webkul/Sales/tests');
uses(ShopTestCase::class)->in('../packages/Webkul/Shop/tests');
uses(ShopTheLookTestCase::class)->in('../packages/Webkul/ShopTheLook/tests');
uses(SizeGuideTestCase::class)->in('../packages/Webkul/SizeGuide/tests');
uses(SocialCommerceTestCase::class)->in('../packages/Webkul/SocialCommerce/tests');
uses(SocialLoginTestCase::class)->in('../packages/Webkul/SocialLogin/tests');
uses(StoreLocatorTestCase::class)->in('../packages/Webkul/StoreLocator/tests');
uses(StripeTestCase::class)->in('../packages/Webkul/Stripe/tests');
uses(TaxTestCase::class)->in('../packages/Webkul/Tax/tests');
uses(UserTestCase::class)->in('../packages/Webkul/User/tests');
uses(WalletTestCase::class)->in('../packages/Webkul/Wallet/tests');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
