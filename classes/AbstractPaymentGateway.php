<?php namespace Tb\Catalog\Classes;

abstract class AbstractPaymentGateway
{
    protected static $paymentMethods = [
        'stripe'  => 'Stripe',
        'offline' => 'Cash on Delivery',
    ];

    public static function paymentMethods()
    {
        return static::$paymentMethods;
    }

    abstract public function initiatePayment($order);

    abstract public function confirmPayment($data);
}
