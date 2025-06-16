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
        $cart = new CartManager();
        $this->page['cartItems'] = $cart->getItems();
        $this->page['cartTotal'] = $cart->getTotal();
        $this->page['paymentMethods'] = PaymentMethod::all();
        $this->page['deliveryMethods'] = DeliveryMethod::all();
    }

    public function onPlaceOrder()
    {
        $cart = new CartManager();
        $items = $cart->getItems();
        $total = $cart->getTotal();

        if ($items->isEmpty()) {
            Flash::error('Your cart is empty.');
            return;
        }

        // Calculate discounts
        $couponCode = trim(post('coupon_code'));
        $discountData = (new DiscountService())->calculate($cart->getItems(), $total, $couponCode);

        // Increment coupon usage
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
                $coupon ?? $coupon->markUsed();
        }

        // Discount data
        $discountAmount = $discountData['totalDiscount'];
        $breakdown = $discountData['breakdown'];

        // Delivery method - calculate cost
        $deliveryMethodId = post('delivery_method_id');
        $shippingCost = DeliveryMethod::find($deliveryMethodId)->calculateCost($total - $discountAmount);

        $status = OrderStatus::findByCode(OrderStatus::PENDING);
        $order = Order::create([
            'basket_id'          => $cart->getBasket()->id,
            'user_id'            => optional(Auth::getUser())->id,
            'status_id'          => $status->id,
            'coupon_id'          => isset($coupon) ? $coupon->id : null,
            'total_amount'       => $total - $discountAmount + $shippingCost,
            'discount_amount'    => $discountAmount,
            'shipping_address'   => json_encode(post('shipping')),
            'billing_address'    => json_encode(post('billing')),
            'delivery_method_id' => $deliveryMethodId,
            'shipping_cost'      => $shippingCost,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'           => $order->id,
                'product_variant_id' => $item->variant->id,
                'quantity'           => $item->quantity,
                'unit_price'         => $item->unit_price,
            ]);
        }

        $method = post('payment_method');
        $init = (new PaymentManager())->initiate($method, $order);

        // $cart->clear();

        return Redirect::to($init['redirectUrl']);
    }
}
