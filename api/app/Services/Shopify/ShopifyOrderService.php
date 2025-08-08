<?php

namespace App\Services\Shopify;

use Carbon\Carbon;
use App\DTOs\PedidoDTO;
use App\DTOs\PedidoItemDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Shopify\OrderProviderInterface;

class ShopifyOrderService implements OrderProviderInterface
{
    private string $apiVersion;

    public function __construct(?string $apiVersion = null)
    {
        $this->apiVersion = $apiVersion ?: config('services.shopify.version');
    }

    public function listRecentOrders(string $shop, string $accessToken, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        $limit = min($perPage, 250);
        $createdAtMin = Carbon::now()->subDays(30)->toIso8601String();

        $url = "https://{$shop}/admin/api/{$this->apiVersion}/orders.json";

        $resp = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get($url, [
            'limit'          => $limit,
            'created_at_min' => $createdAtMin,
            'status'         => 'any',
            'fields'         => 'id,created_at,financial_status,fulfillment_status,line_items,customer',
        ])->throw();

        $orders = collect($resp->json('orders') ?? [])->map(function (array $o) {
            $cliente = $this->buildCliente($o['customer'] ?? null);
            $estado  = $this->buildEstado($o['financial_status'] ?? null, $o['fulfillment_status'] ?? null);

            $items = collect($o['line_items'] ?? [])->map(function (array $li) {
                return new PedidoItemDTO(
                    nombre: $li['title'] ?? '',
                    sku: $li['sku'] ?? null,
                    cantidad: (int) ($li['quantity'] ?? 0),
                    precio: isset($li['price']) ? (string)$li['price'] : null,
                );
            })->values()->all();

            return new PedidoDTO(
                id: (string)$o['id'],
                cliente: $cliente,
                fecha: Carbon::parse($o['created_at'] ?? now())->format('Y-m-d H:i'),
                estado: $estado,
                items: $items,
            );
        });

        return new LengthAwarePaginator(
            items: $orders->forPage($page, $perPage)->values(),
            total: $orders->count(),
            perPage: $perPage,
            currentPage: $page,
            options: ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function buildCliente(?array $customer): string
    {
        if (!$customer) return '—';
        $nombre = trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''));
        return $nombre !== '' ? $nombre : ($customer['email'] ?? '—');
    }

    private function buildEstado(?string $financial, ?string $fulfillment): string
    {
        $f1 = $financial ?: 'desconocido';
        $f2 = $fulfillment ?: 'sin_fulfillment';
        return "{$f1} / {$f2}";
    }
}
