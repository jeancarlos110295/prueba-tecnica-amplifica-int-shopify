<?php

namespace App\DTOs;

class PedidoItemDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly ?string $sku,
        public readonly int $cantidad,
        public readonly ?string $precio,
    ) {}
}
