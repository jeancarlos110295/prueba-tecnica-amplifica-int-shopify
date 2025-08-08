<?php

namespace App\Services\Shopify;

use App\DTOs\ProductoDTO;
use App\Interfaces\Shopify\ProductoInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class ShopifyProductService implements ProductoInterface
{
    public $apiVersion = null;

    public function __construct() {
        $this->apiVersion = config('services.shopify.version');
    }

    public function listarProductos(string $shop, string $accessToken, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        $limit = 250;

        $url = "https://{$shop}/admin/api/{$this->apiVersion}/products.json";
        
        $resp = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get($url, [
            'limit'  => $limit,
            'fields' => 'id,title,variants,image',
        ])->throw();

        $products = collect($resp->json('products') ?? [])
            ->map(function (array $p) {
                $firstVariant = $p['variants'][0] ?? null;

                return new ProductoDTO(
                    id: (string)$p['id'],
                    nombre: $p['title'] ?? '',
                    sku: $firstVariant['sku'] ?? null,
                    precio: $firstVariant['price'] ?? null,
                    img: $p['image']['src'] ?? null,
                );
            });

        return new LengthAwarePaginator(
            items: $products->forPage($page, $perPage)->values(),
            total: $products->count(),
            perPage: $perPage,
            currentPage: $page,
            options: [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );
    }
}
