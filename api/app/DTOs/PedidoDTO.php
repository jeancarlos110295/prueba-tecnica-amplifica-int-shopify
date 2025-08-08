<?php

namespace App\DTOs;

class PedidoDTO
{
    /**
     * @param PedidoItemDTO[] $items
     */
    public function __construct(
        public readonly string $id,
        public readonly string $cliente,
        public readonly string $fecha,
        public readonly string $estado,
        public readonly array $items,
    ) {}
}
