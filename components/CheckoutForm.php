<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Tb\Catalog\Models\Order;
use Tb\Catalog\Models\OrderItem;
use Tb\Catalog\Models\OrderStatus;
use Tb\Catalog\Services\CartManager;
use Winter\Storm\Support\Facades\Flash;
use Winter\Storm\Support\Facades\Input;
use Winter\User\Facades\Auth;

class CheckoutForm extends ComponentBase
{
    public $errors = [];

    public function componentDetails()
    {
        return [
            'name'        => 'Checkout Form',
            'description' => 'Collects customer info and places an order'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirectPage' => [
                'title'       => 'Redirect Page',
                'description' => 'Page to go to after a successful order, e.g. thank-you',
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
        $this->page['errors']   = $this->errors;
    }

    public function onPlaceOrder()
    {
        $cart = new CartManager();
        $items = $cart->getItems();

        if ($items->isEmpty()) {
            return;
        }

        // TODO: backend validation

        $status = OrderStatus::findByCode(OrderStatus::PENDING);

        $shipping = [
            'name'    => Input::get('shipping_name'),
            'street'  => Input::get('shipping_street'),
            'city'    => Input::get('shipping_city'),
            'zip'     => Input::get('shipping_zip'),
            'country' => Input::get('shipping_country'),
        ];
        $billing = [
            'name'    => Input::get('billing_name'),
            'street'  => Input::get('billing_street'),
            'city'    => Input::get('billing_city'),
            'zip'     => Input::get('billing_zip'),
            'country' => Input::get('billing_country'),
        ];

        $order = Order::create([
            'basket_id'        => $cart->getBasket()->id,
            'user_id'          => optional(Auth::getUser())->id,
            'status_id'        => $status->id,
            'total_amount'     => $cart->getTotal(),
            'shipping_address' => json_encode($shipping),
            'billing_address'  => json_encode($billing),
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'           => $order->id,
                'product_variant_id' => $item->variant->id,
                'quantity'           => $item->quantity,
                'unit_price'         => $item->unit_price,
            ]);
        }

        $cart->clear();

        Flash::success('Your order has been placed!');

        $redirectPage = $this->property('redirectPage');

        return Redirect::to($this->pageUrl($redirectPage, ['id' => $order->id]));
    }
}
