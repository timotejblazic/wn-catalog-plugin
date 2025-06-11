<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Tb\Catalog\Services\CartManager;
use Winter\Storm\Support\Facades\Flash;
use Winter\Storm\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class AddToCart extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Add To Cart',
            'description' => 'Adds a product variant to the shopping cart'
        ];
    }

    public function defineProperties()
    {
        return [
            'variantIdParam' => [
                'title'       => 'Variant ID URL Parameter',
                'description' => 'The URL parameter that contains the variant ID',
                'type'        => 'string',
                'default'     => 'variantId',
            ],
            'redirectToCart' => [
                'title'       => 'Redirect to Cart Page',
                'description' => 'Page name to redirect after adding',
                'type'        => 'string',
                'default'     => ''
            ]
        ];
    }

    public function onAddToCart()
    {
        $variantId = Input::get($this->property('variantIdParam'));
        $quantity  = (int) Input::get('quantity', 1);

        $cart = new CartManager();
        $item = $cart->addItem($variantId, $quantity);

        Flash::success('Added to cart');

        $redirect = trim($this->property('redirectToCart'));
        if ($redirect) {
            return Redirect::to($redirect);
        }
    }
}
