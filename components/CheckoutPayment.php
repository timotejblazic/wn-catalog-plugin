<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Tb\Catalog\Services\CartManager;
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
        $this->page['paymentMethods'] = array_keys(config('payment.drivers'));
    }

    public function onPlaceOrder()
    {
        $method = post('payment_method');
        $cart = new CartManager();
        $items = $cart->getItems();

        if ($items->isEmpty()) {
            Flash::error('Your cart is empty.');
            return;
        }

        $status = OrderStatus::findByCode(OrderStatus::PENDING);
        $order = Order::create([
            'basket_id'        => $cart->getBasket()->id,
            'user_id'          => optional(Auth::getUser())->id,
            'status_id'        => $status->id,
            'total_amount'     => $cart->getTotal(),
            'shipping_address' => json_encode(post('shipping')),
            'billing_address'  => json_encode(post('billing')),
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'           => $order->id,
                'product_variant_id' => $item->variant->id,
                'quantity'           => $item->quantity,
                'unit_price'         => $item->unit_price,
            ]);
        }

        $pm = new PaymentManager();
        $init = $pm->initiate($method, $order);

        // $cart->clear();

        return Redirect::to($init['redirectUrl']);
    }
}
