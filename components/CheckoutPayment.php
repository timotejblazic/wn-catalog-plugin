<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Tb\Catalog\Models\Coupon;
use Tb\Catalog\Models\DeliveryMethod;
use Tb\Catalog\Models\PaymentMethod;
use Tb\Catalog\Services\CartManager;
use Tb\Catalog\Services\DiscountService;
use Tb\Catalog\Services\PaymentManager;
use Tb\Catalog\Models\Order;
use Tb\Catalog\Models\OrderItem;
use Tb\Catalog\Models\OrderStatus;
use Winter\Storm\Support\Facades\Flash;
use Winter\User\Facades\Auth;

class CheckoutPayment extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Checkout & Redirect',
            'description' => 'Places the order and redirects to chosen gateway'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirectPage' => [
                'title'       => 'Thank You Page',
                'description' => 'Page to redirect after return from gateway',
                'type'        => 'string',
                'default'     => 'thank-you'
            ],
        ];
    }

    public function onRun()
    {
        $this->page['paymentMethods'] = PaymentMethod::all();
        $this->page['deliveryMethods'] = DeliveryMethod::all();
        $this->page['cartItems'] = (new CartManager())->getItems();

        // Summary with no coupon, default method
        $defaultMethodId = $this->page['deliveryMethods']->first()->id ?? null;
        $prices = $this->calculateAllPrices(null, $defaultMethodId);

        // Inject vars to frontend
        foreach ($prices as $key => $value) {
            $this->page[$key] = $value;
        }
    }

    public function onPlaceOrder()
    {
        $cart = new CartManager();
        $items = $cart->getItems();
        $deliveryMethodId = post('delivery_method_id');

        if ($items->isEmpty()) {
            Flash::error('Your cart is empty.');
            return;
        }

        // Load and validate coupon
        $couponCode = trim(post('coupon_code'));
        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
        }

        $prices = $this->calculateAllPrices($couponCode, $deliveryMethodId);

        // Increment coupon usage if coupon is applied
        if (!empty($prices['couponSavings']) && $coupon) {
            $coupon->markUsed();
        }

        // Create order
        $status = OrderStatus::findByCode(OrderStatus::PENDING);
        $order = Order::create([
            'basket_id'          => $cart->getBasket()->id,
            'user_id'            => optional(Auth::getUser())->id,
            'status_id'          => $status->id,
            'coupon_id'          => isset($coupon) ? $coupon->id : null,
            'shipping_address'   => json_encode(post('shipping')),
            'billing_address'    => json_encode(post('billing')),
            'delivery_method_id' => $deliveryMethodId,
            'discount_amount'    => $prices['productDiscount'] + $prices['couponSavings'],
            'shipping_cost'      => $prices['shippingCost'],
            'total_amount'       => $prices['overallTotal'],
        ]);

        // Create order items
        foreach ($items as $item) {
            OrderItem::create([
                'order_id'           => $order->id,
                'product_variant_id' => $item->variant->id,
                'quantity'           => $item->quantity,
                'unit_price'         => $item->unit_price,
            ]);
        }

        // Initiate payment
        $method = post('payment_method');
        $init = (new PaymentManager())->initiate($method, $order);

        // $cart->clear();

        return Redirect::to($init['redirectUrl']);
    }

    public function onUpdateSummary()
    {
        $prices = $this->calculateAllPrices(post('coupon_code'), post('delivery_method_id'));

        foreach ($prices as $key => $value) {
            $this->page[$key] = $value;
        }

        return [
            '#js-summary-container' => $this->renderPartial('@_summary'),
        ];
    }

    protected function calculateAllPrices($couponCode, $deliveryMethodId)
    {
        // 1) Cart
        $cart = new CartManager();
        $items = $cart->getItems();
        $total = $cart->getTotal();

        // 2) Discounts (product + coupon)
        $discountData = (new DiscountService())->calculate($items, $total, $couponCode);
        $couponSavings = $discountData['breakdown']['coupon'] ?? 0;
        $productDiscount = $discountData['totalDiscount'] - $couponSavings;
        $discountedProductTotal = round($total - $productDiscount, 2);

        // 3) Shipping
        $method = $deliveryMethodId ? DeliveryMethod::find($deliveryMethodId) : DeliveryMethod::all()->first();
        $shippingCost = $method ? $method->calculateCost($discountedProductTotal) : 0.0;

        // 4) Overall
        $overallTotal = round($discountedProductTotal + $shippingCost - $couponSavings, 2);

        return compact(
            'total',
            'productDiscount',
            'discountedProductTotal',
            'couponSavings',
            'shippingCost',
            'overallTotal'
        );
    }
}
