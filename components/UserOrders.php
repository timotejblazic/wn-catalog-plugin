<?php namespace Tb\Catalog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use Tb\Catalog\Models\Order;
use Winter\User\Facades\Auth;

class UserOrders extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'User Orders',
            'description' => 'Displays a list of the current user’s past orders'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $user = Auth::getUser();

        if (!$user) {
            return Redirect::to('/account');
        }

        $this->page['user'] = Auth::getUser();
        $this->page['orders'] = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
