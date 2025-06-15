<?php namespace Tb\Catalog\Classes;

abstract class AbstractPaymentGateway
{
    abstract public function initiatePayment($order);

    abstract public function confirmPayment($data);
}
