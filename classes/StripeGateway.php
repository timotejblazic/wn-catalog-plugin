<?php namespace Tb\Catalog\Classes;

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Tb\Catalog\Models\Order;
use Tb\Catalog\Models\PaymentStatus;
use Winter\Storm\Exception\ApplicationException;

class StripeGateway extends AbstractPaymentGateway
{
    protected $method;

    public function __construct($method)
    {
        $this->method = $method;
        Stripe::setApiKey($this->method->secret_key);
    }

    public function initiatePayment($order)
    {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $this->prepareStripeItems($order),
            'mode'                 => 'payment',
            'success_url'          => url('/thank-you/' . $order->id) . '?method=stripe&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => url('/checkout'),
            'metadata'             => ['order_id' => $order->id],
        ]);

        return ['redirectUrl' => $session->url];
    }

    public function confirmPayment($data)
    {
        if (empty($data['session_id'])) {
            throw new ApplicationException('Missing Stripe session_id in return parameters.');
        }

        $checkoutSession = Session::retrieve($data['session_id']);

        $pi = PaymentIntent::retrieve($checkoutSession->payment_intent);

        $orderId = $checkoutSession->metadata['order_id'] ?? null;
        if (!$orderId) {
            throw new ApplicationException('No order_id found in Stripe session metadata.');
        }

        // Map stripe status codes to internal codes
        $stripeStatus = $pi->status;
        $map = [
            'succeeded' => PaymentStatus::PAID,
            'canceled'  => PaymentStatus::FAILED,
        ];
        $internalCode = $map[$stripeStatus] ?? PaymentStatus::PENDING;

        $order = Order::findOrFail($orderId);
        $status = PaymentStatus::findByCode($internalCode);

        $order->payment_status_id = $status->id;
        $order->save();

        return [
            'orderId'       => $order->id,
            'paymentStatus' => PaymentStatus::findByCode($internalCode)->name,
        ];
    }

    protected function prepareStripeItems($order)
    {
        // -- This is the approach for every item to show up in the Stripe summary --
        //        return $order->items->map(function ($item) {
        //            $variant = $item->variant;
        //
        //            return [
        //                'price_data' => [
        //                    'currency'     => 'eur',
        //                    'unit_amount'  => intval($variant->price * 100),
        //                    'product_data' => ['name' => $variant->product->title . ' (' . $variant->title . ')'],
        //                ],
        //                'quantity'   => $item->quantity,
        //            ];
        //        })->toArray();

        return [
            [
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => intval($order->total_amount * 100),
                    'product_data' => ['name' => 'Order #' . $order->id],
                ],
                'quantity'   => 1,
            ]
        ];
    }
}
