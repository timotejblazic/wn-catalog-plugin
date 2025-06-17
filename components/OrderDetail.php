<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Tb\Catalog\Models\Order;
use Winter\User\Facades\Auth;

class OrderDetail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Order Detail',
            'description' => 'Shows details for a single order'
        ];
    }

    public function defineProperties()
    {
        return [
            'orderParam' => [
                'title'       => 'Order ID URL parameter',
                'description' => 'URL parameter name for the order ID',
                'type'        => 'string',
                'default'     => 'id'
            ]
        ];
    }

    public function onRun()
    {
        $user = Auth::getUser();

        if (!$user) {
            return Redirect::to('/login');
        }

        $orderId = $this->param($this->property('orderParam'));
        $order = Order::with(['items.variant.product', 'status', 'deliveryMethod', 'paymentStatus'])
            ->find($orderId);

        if (!$order || $order->user_id !== $user->id) {
            return Redirect::to('/account/orders');
        }

        $order->shipping_address = json_decode($order->shipping_address, true);
        $order->billing_address = json_decode($order->billing_address, true);

        $this->page['order'] = $order;
    }
}
