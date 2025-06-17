<?php namespace Tb\Catalog\Services;

use Winter\User\Facades\Auth;
use Tb\Catalog\Models\Basket;
use Tb\Catalog\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class CartManager
{
    public function getBasket()
    {
        if ($user = Auth::getUser()) {
            return Basket::firstOrCreate(['user_id' => $user->id]);
        }
        return Basket::firstOrCreate(['session_id' => Session::getId()]);
    }

    public function addItem($variantId, $qty = 1)
    {
        $basket = $this->getBasket();
        $item = $basket->items()->where('product_variant_id', $variantId)->first();
        $variant = ProductVariant::findOrFail($variantId);
        $price = $variant->price;

        // Check stock
        $currentQty = $this->getQuantityInCart($variant);
        if (!$variant->isAvailable($currentQty + $qty)) {
            $variant->handleOutOfStock();
        }

        if ($item) {
            $item->quantity += $qty;
            $item->unit_price = $price;
            $item->save();
        } else {
            $item = $basket->items()->create([
                'product_variant_id' => $variantId,
                'quantity'           => $qty,
                'unit_price'         => $price,
            ]);
        }

        return $item;
    }

    public function updateItem($itemId, $qty)
    {
        $basket = $this->getBasket();
        $item = $basket->items()->find($itemId);

        if (!$item) {
            return false;
        }

        if ($qty < 1) {
            return $item->delete();
        }

        // Check stock
        if (!$item->variant->isAvailable($qty)) {
            $item->variant->handleOutOfStock();
        }

        $item->quantity = $qty;

        return $item->save();
    }

    public function removeItem($itemId)
    {
        return $this->getBasket()->items()->where('id', $itemId)->delete();
    }

    public function clear()
    {
        $this->getBasket()->items()->delete();
    }

    public function getItems()
    {
        return $this->getBasket()
            ->items()
            ->with('variant.product')
            ->get();
    }

    public function getTotal()
    {
        $sum = 0.0;

        foreach ($this->getItems() as $item) {
            $sum += $item->unit_price * $item->quantity;
        }

        return $sum;
    }

    public function mergeGuestCartIntoUser()
    {
        $sessionBasket = Basket::where('session_id', Session::getId())->first();

        if ($sessionBasket && $user = Auth::getUser()) {
            $userBasket = Basket::firstOrCreate(['user_id' => $user->id]);

            foreach ($sessionBasket->items as $item) {
                $userBasket->items()->updateOrCreate(
                    ['product_variant_id' => $item->product_variant_id],
                    ['quantity'   => \DB::raw("quantity + {$item->quantity}"),
                     'unit_price' => $item->unit_price]
                );
            }

            $sessionBasket->delete();
        }
    }

    protected function getQuantityInCart($variant)
    {
         return $this->getBasket()
             ->items()
             ->where('product_variant_id', $variant->id)
             ->sum('quantity');
    }
}
