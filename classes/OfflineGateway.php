<?php namespace Tb\Catalog\Classes;

use Tb\Catalog\Models\PaymentStatus;

class OfflineGateway extends AbstractPaymentGateway
{
    public function initiatePayment($order)
    {
        $pending = PaymentStatus::findByCode(PaymentStatus::PENDING);
        $order->payment_status_id = $pending->id;
        $order->save();

        $url = url('/thank-you/' . $order->id) . '?method=offline&orderId=' . $order->id;
        return ['redirectUrl' => $url];
    }

    public function confirmPayment($data)
    {
        return [
            'orderId'       => $data['orderId'] ?? null,
            'paymentStatus' => PaymentStatus::PENDING,
        ];
    }
}
