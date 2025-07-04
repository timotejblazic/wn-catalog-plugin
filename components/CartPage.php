<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Tb\Catalog\Services\CartManager;
use Winter\Storm\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class CartPage extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Cart Page',
            'description' => 'Displays and updates the shopping cart'
        ];
    }

    public function onRun()
    {
        $cart = new CartManager();
        $this->page['cartItems'] = $cart->getItems();
        $this->page['cartTotal'] = $cart->getTotal();
    }

    public function onUpdateCart()
    {
        $itemId   = (int) Input::get('itemId');
        $quantity = (int) Input::get('quantity');

        $cart = new CartManager();
        $cart->updateItem($itemId, $quantity);

        return $this->renderCartPartial();
    }

    public function onRemoveItem()
    {
        $itemId = (int) Input::get('itemId');

        $cart = new CartManager();
        $cart->removeItem($itemId);

        return $this->renderCartPartial();
    }

    protected function renderCartPartial()
    {
        $cart = new CartManager();

        $this->page['cartItems'] = $cart->getItems();
        $this->page['cartTotal'] = $cart->getTotal();

        return [
            '#js-basket-summary' => $this->renderPartial('@_summary'),
        ];
    }
}

