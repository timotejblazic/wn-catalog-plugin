<?php namespace Tb\Catalog\Components;

use Tb\Catalog\Models\Product;
use Cms\Classes\ComponentBase;

class ProductList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Product List',
            'description' => 'Displays a list of all products.'
        ];
    }

    public function defineProperties()
    {
        return [
            'maxItems' => [
                'title'             => 'Maximum products',
                'description'       => 'Limit the number of products shown (0 = all)',
                'default'           => 0,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Only integers allowed'
            ],
        ];
    }

    public function onRun()
    {
        $this->page['products'] = $this->loadProducts();
    }

    protected function loadProducts()
    {
        $maxItems = intval($this->property('maxItems'));

        if ($maxItems) {
            return Product::all()->take($maxItems);
        }

        return Product::all();
    }
}
