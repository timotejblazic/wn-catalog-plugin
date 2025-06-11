<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Tb\Catalog\Models\Product;

class ProductSingle extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ProductSingle Component',
            'description' => 'Display all the details of the product...'
        ];
    }

    public function defineProperties()
    {
        return [
            'slugParam' => [
                'title'       => 'URL Parameter',
                'description' => 'The URL parameter used for the product slug',
                'type'        => 'string',
                'default'     => 'slug',
            ],
        ];
    }

    public function onRun()
    {
        $slug = $this->param($this->property('slugParam'));

        $this->page['product'] = Product::where('slug', $slug)->firstOrFail();
    }
}
