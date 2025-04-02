<?php
namespace App\Http\Controllers\inventory\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ProductsImport implements ToCollection
{
    protected $products = [];

    public function collection(Collection $rows)
    {
        // Omitir la primera fila (encabezado)
        $rows = $rows->slice(1);

        foreach ($rows as $row) {
            $this->products[] = [
                'codigo' => $row[0],
                'descripcion' => $row[1],
                'cantidad' => $row[2],
                'costo' => $row[3],
                'precio' => $row[4],
            ];
        }
    }

    public function getProducts()
    {
        return $this->products;
    }
}