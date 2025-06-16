<?php namespace Tb\Catalog\Services;

use Tb\Catalog\Models\PaymentMethod;
use Winter\Storm\Exception\ApplicationException;

class PaymentManager
{
    protected $gateways = [];

    public function __construct()
    {
        foreach (PaymentMethod::all() as $method) {
            $class = 'Tb\\Catalog\\Classes\\' . ucfirst($method->type) . 'Gateway';

            if (class_exists($class)) {
                $this->gateways[$method->type] = app()->make($class, ['method' => $method]);
            }
        }
    }

    protected function gateway($driverCode)
    {
        if (!isset($this->gateways[$driverCode])) {
            throw new ApplicationException("Payment driver [$driverCode] not configured");
        }

        return $this->gateways[$driverCode];
    }

    public function initiate($driver, $order)
    {
        return $this->gateway($driver)->initiatePayment($order);
    }

    public function confirm($driver, $data)
    {
        return $this->gateway($driver)->confirmPayment($data);
    }
}
