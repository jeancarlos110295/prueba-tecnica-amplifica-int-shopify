<?php

namespace App\Interfaces\Shopify;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderProviderInterface
{
    /** @return LengthAwarePaginator */
    public function listRecentOrders(string $shop, string $accessToken, int $page = 1, int $perPage = 20): LengthAwarePaginator;
}
