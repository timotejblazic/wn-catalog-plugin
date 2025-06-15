<?php namespace Tb\Catalog\Services;

use Tb\Catalog\Models\Coupon;

class DiscountService
{
    public function calculate($items, $total, $couponCode = null)
    {
        $breakdown = [];
        $totalDiscount = 0.0;

        // 1) Product discount
        $productDiscount = 0.0;
        foreach ($items as $item) {
            $product = $item->variant->product;

            if ($product->discount_type && $product->discount_value) {
                $unitPrice = $item->unit_price;

                if ($product->discount_type === 'fixed') {
                    $perUnit = min($product->discount_value, $unitPrice);
                } else {
                    $perUnit = round($unitPrice * ($product->discount_value / 100), 2);
                }

                $itemDiscount = $perUnit * $item->quantity;

                $breakdown['product_' . $product->id] = $itemDiscount;
                $productDiscount += $itemDiscount;
            }
        }
        $totalDiscount += $productDiscount;

        // 2) Coupon discount
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();

            if ($coupon && $coupon->isValid()) {
                if ($coupon->type === 'fixed') {
                    $amount = min($coupon->value, $total);
                } else {
                    $amount = round($total * ($coupon->value / 100), 2);
                }

                $breakdown['coupon'] = $amount;
                $totalDiscount += $amount;
            }
        }

        // 3) Future discount rules go here

        return [
            'totalDiscount' => round($totalDiscount, 2),
            'breakdown'     => $breakdown,
        ];
    }
}
