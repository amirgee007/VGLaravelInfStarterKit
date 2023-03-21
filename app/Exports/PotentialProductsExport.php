<?php

namespace Vanguard\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;

class PotentialProductsExport implements FromCollection
{
    private $potential_products;

    public function __construct($data)
    {
        $this->potential_products = $data;
    }

    public function collection()
    {
        return collect($this->potential_products);
    }
}