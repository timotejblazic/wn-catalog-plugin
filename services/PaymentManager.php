<?php namespace Tb\Catalog\Services;

use Winter\Storm\Exception\ApplicationException;

class PaymentManager
{
    protected $gateways = [];

    public function __construct()
    {
        $drivers = config('payment.drivers');

        foreach ($drivers as $code => $cfg) {
            $class = 'Tb\\Catalog\\Classes\\' . ucfirst($code) . 'Gateway';

            if (!class_exists($class)) {
                continue;
            }

            $this->gateways[$code] = app()->make($class);
        }
    }

    protected function gateway($driverCode)
    {
        if (!isset($this->gateways[$driverCode])) {
            throw new ApplicationException("Payment driver [$driverCode] not configured");
        }
        return $this->gateways[$driverCode];
    }

    public function initiate(string $driver, $order): array
    {
        return $this->gateway($driver)->initiatePayment($order);
    }

    public function confirm(string $driver, array $data): array
    {
        return $this->gateway($driver)->confirmPayment($data);
    }
}
