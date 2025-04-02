<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryResultsExport implements FromCollection, WithHeadings
{
    protected $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return collect($this->results);
    }

    public function headings(): array
    {
        return [
            'Código de Barras',
            'Descripción',
            'Cantidad en Excel',
            'Cantidad Contada',
            'Diferencia',
            'Usuario',
            'Fecha',
        ];
    }
}