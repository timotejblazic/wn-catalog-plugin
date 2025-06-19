<?php namespace Tb\Catalog\Components;

use Tb\Catalog\Models\Brand;
use Tb\Catalog\Models\Category;
use Tb\Catalog\Models\Product;
use Cms\Classes\ComponentBase;

class ProductList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Product List',
            'description' => 'Displays a filtered, sortable list of products'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageSize' => [
                'title'             => 'Products per page',
                'type'              => 'string',
                'default'           => '4',
                'validationPattern' => '^[0-9]+$',
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function onFilter()
    {
        $this->prepareVars();

        return [
            '#product-list' => $this->renderPartial('@_products')
        ];
    }

    protected function prepareVars()
    {
        $this->page['categories'] = Category::orderBy('title')->get();
        $this->page['brands'] = Brand::orderBy('title')->get();
        $this->page['filterParams'] = $this->getFilterParams();
        $this->page['products'] = $this->loadProducts();
    }

    protected function getFilterParams(): array
    {
        return [
            'category' => input('category'),
            'brand'    => input('brand'),
            'priceMin' => input('price_min'),
            'priceMax' => input('price_max'),
            'sort'     => input('sort'),
            'page'     => input('page', 1),
        ];
    }

    protected function loadProducts()
    {
        $params = $this->getFilterParams();
        $page = max(1, (int)$params['page']);
        $size = (int)$this->property('pageSize');

        $query = Product::with('variants', 'categories', 'brand')
            ->whereHas('variants') // ensure at least one variant
            ->where('base_price', '>=', 0);

        // filter by category slug
        if ($params['category']) {
            $query->whereHas('categories', function ($q) use ($params) {
                $q->where('slug', $params['category']);
            });
        }

        // filter by brand slug
        if ($params['brand']) {
            $query->whereHas('brand', function ($q) use ($params) {
                $q->where('slug', $params['brand']);
            });
        }

        // price range (on base_price)
        if (is_numeric($params['priceMin'])) {
            $query->where('base_price', '>=', $params['priceMin']);
        }
        if (is_numeric($params['priceMax'])) {
            $query->where('base_price', '<=', $params['priceMax']);
        }

        // sorting
        switch ($params['sort']) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
        }

        // paginate
        return $query->paginate($size, $page);
    }
}
