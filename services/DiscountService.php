<?php namespace Tb\Catalog\Services;

use Tb\Catalog\Models\Coupon;

class DiscountService
{
    public function calculate($items, $total, $couponCode = null)
    {
        // 1) Product-level discounts
        $productResult = $this->calculateProductDiscount($items);
        $productDiscount = $productResult['amount'];
        $breakdown = $productResult['breakdown'];

        // 2) Coupon-level discount
        $couponResult = $this->calculateCouponDiscount($total - $productDiscount, $couponCode);
        $couponDiscount = $couponResult['amount'];
        $breakdown += $couponResult['breakdown'];

        $totalDiscount = round($productDiscount + $couponDiscount, 2);

        return [
            'totalDiscount' => $totalDiscount,
            'breakdown'     => $breakdown,
        ];
    }

    protected function calculateProductDiscount($items)
    {
        $breakdown = [];
        $totalDiscount = 0.0;

        foreach ($items as $item) {
            $product = $item->variant->product;
            if (!$product->discount_type || !$product->discount_value) {
                continue;
            }

            $unitPrice = $item->unit_price;
            if ($product->discount_type === 'fixed') {
                $perUnit = min($product->discount_value, $unitPrice);
            } else {
                $perUnit = round($unitPrice * ($product->discount_value / 100), 2);
            }

            $itemDiscount = $perUnit * $item->quantity;
            $breakdown['product_' . $product->id] = round($itemDiscount, 2);
            $totalDiscount += $itemDiscount;
        }

        return [
            'amount'    => round($totalDiscount, 2),
            'breakdown' => $breakdown,
        ];
    }

    protected function calculateCouponDiscount($subtotalAfterProduct, $couponCode)
    {
        $breakdown = [];
        $totalDiscount = 0.0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();

            if ($coupon && $coupon->isValid()) {
                if ($coupon->type === 'fixed') {
                    $amount = min($coupon->value, $subtotalAfterProduct);
                } else {
                    $amount = round($subtotalAfterProduct * ($coupon->value / 100), 2);
                }

                $breakdown['coupon'] = $amount;
                $totalDiscount += $amount;
            }
        }

        return [
            'amount'    => round($totalDiscount, 2),
            'breakdown' => $breakdown,
        ];
    }
}
