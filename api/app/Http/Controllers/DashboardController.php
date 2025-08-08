<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ConnectedShop;

class DashboardController extends Controller
{
    public function dashboard(Request $request): View
    {
        $connectedShopify = ConnectedShop::where('user_id', auth()->id())->first();

        return view('dashboard')->with([
            'connectedShopify' => $connectedShopify,
            'domainShop' => config('services.shopify.url_shop')
        ]);
    }
}
