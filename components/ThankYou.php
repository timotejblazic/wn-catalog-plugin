<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Tb\Catalog\Services\PaymentManager;
use Tb\Catalog\Models\Order;
use Tb\Catalog\Models\PaymentStatus;
use Winter\Storm\Support\Facades\Input;

class ThankYou extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Thank You / Payment Return',
            'description' => 'Confirms gateway payment and updates order status'
        ];
    }

    public function defineProperties()
    {
        return [
            'orderIdParam' => [
                'title'   => 'Order ID Parameter',
                'type'    => 'string',
                'default' => 'id',
            ],
        ];
    }

    public function onRun()
    {
        $method = Input::get('method');
        $data = Input::all();

        $result = (new PaymentManager())->confirm($method, $data);

        $this->page['orderId'] = $result['orderId'];
        $this->page['paymentStatus'] = $result['paymentStatus'];
    }
}
