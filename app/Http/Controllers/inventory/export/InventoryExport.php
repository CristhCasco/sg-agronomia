<?php
namespace App\Http\Controllers\inventory\export;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Inventory::all()->map(function ($inventory) {
            return [
                'barcode' => $inventory->barcode,
                'description' => $inventory->description,
                'quantity_excel' => $inventory->quantity_excel,
                'quantity_counted' => $inventory->quantity_counted,
                'difference' => $inventory->difference,
                'cost' => $inventory->cost,
                'price' => $inventory->price,
                'user' => $inventory->user->name,
                'date' => $inventory->updated_at->format('d-m-Y H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Descripcion',
            'Cantidad en Excel',
            'Cantidad Contada',
            'Diferencia',
            'Costo',
            'Precio',
            'Usuario',
            'Fecha',
        ];
    }
}