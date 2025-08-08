<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConnectedShop;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\Shopify\ProductoInterface;
use App\Services\Shopify\ShopifyOAuthService;
use App\Services\Shopify\Exports\ProductosExport;
use App\Interfaces\Shopify\OrderProviderInterface;

class ShopifyController extends Controller
{
    public function __construct(private ShopifyOAuthService $shopifyAuth) {}

    public function install()
    {
        return redirect()->away(
            $this->shopifyAuth->buildAuthUrl(
                config('services.shopify.url_shop')
            )
        );
    }

    public function handleCallback(Request $request)
    {
        $shop = $request->get('shop');
        $code = $request->get('code');

        $token = $this->shopifyAuth->requestAccessToken($shop, $code);

        ConnectedShop::updateOrCreate(
            [
                'user_id' => auth()->id()
            ],
            [
                'shop' => $shop, 
                'access_token' => $token
            ]
        );

        return redirect()->route('shopify.productos');
    }

    public function productos(Request $request, ProductoInterface $productos)
    {
        $connected = ConnectedShop::where('user_id', auth()->id())->firstOrFail();

        $page    = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 5);

        $paginated = $productos->listarProductos($connected->shop, $connected->access_token, $page, $perPage);

        return view('shopify.productos')->with('paginated' , $paginated);
    }

    public function pedidos(OrderProviderInterface $orders)
    {
        $connected = ConnectedShop::where('user_id', auth()->id())->firstOrFail();

        $page    = (int) request('page', 1);
        $perPage = (int) request('per_page', 20);

        $paginated = $orders->listRecentOrders($connected->shop, $connected->access_token, $page, $perPage);

        return view('shopify.pedidos')->with('paginated', $paginated);
    }

    public function exportProductosExcel(ProductoInterface $productos)
    {
        $connected = ConnectedShop::where('user_id', auth()->id())->firstOrFail();
        $paginated = $productos->listarProductos($connected->shop, $connected->access_token, 1, 250);

        $rows = [];

        foreach ($paginated as $p) {
            $rows[] = [$p->id, $p->nombre, $p->sku, $p->precio, $p->img];
        }

        return Excel::download(new ProductosExport($rows), 'productos_'.now()->format('Ymd_His').'.xlsx');
    }
}
