<?php

namespace Vanguard\Exports;
use Maatwebsite\Excel\Concerns\FromArray;

class GoodsExport implements FromArray
{
    protected $invoices;
    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }
    public function array(): array
    {
        return $this->invoices;
    }
}