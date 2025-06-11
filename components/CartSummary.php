<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Tb\Catalog\Services\CartManager;

class CartSummary extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Cart Summary',
            'description' => 'Displays summary of the current shopping cart'
        ];
    }

    public function onRun()
    {
        $cart = new CartManager();
        $this->page['cartItems'] = $cart->getItems();
        $this->page['cartTotal'] = $cart->getTotal();
    }
}
