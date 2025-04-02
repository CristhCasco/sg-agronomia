<?php
namespace App\Http\Controllers\inventory\export;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class ProductsExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    public function collection()
    {
        return Product::select('barcode', 'name', 'stock', 'cost', 'price')->get();
    }

    public function headings(): array
    {
        return [
            'codigo',
            'descripcion',
            'cantidad',
            'costo',
            'precio',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la primera fila (encabezados)
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->setCellValue('A1', 'Fecha: ' . Carbon::now()->format('Y-m-d H:i:s'));
                // $event->sheet->getStyle('A1')->applyFromArray([
                //     'font' => [
                //         'bold' => true,
                //     ],
                // ]);
            },
        ];
    }
}