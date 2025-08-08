<?php

namespace App\Services\Shopify\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductosExport implements FromArray, WithHeadings
{
    public function __construct(private array $rows) {}

    public function headings(): array
    {
        return ['ID', 'Nombre', 'SKU', 'Precio', 'Imagen'];
    }

    public function array(): array
    {
        return $this->rows;
    }
}
