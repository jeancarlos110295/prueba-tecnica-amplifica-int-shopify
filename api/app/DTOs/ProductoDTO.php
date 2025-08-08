<?php

namespace App\DTOs;

class ProductoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nombre,
        public readonly ?string $sku,
        public readonly ?string $precio,
        public readonly ?string $img,
    ) {}
}
