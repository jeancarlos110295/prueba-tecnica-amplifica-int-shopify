<?php

namespace App\Interfaces\Shopify;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductoInterface
{
    /** @return LengthAwarePaginator */
    public function listarProductos(string $shop, string $accessToken, int $page = 1, int $perPage = 20): LengthAwarePaginator;
}
