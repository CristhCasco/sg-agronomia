<?php
namespace App\Http\Controllers\inventory\Imports;

use Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\InventoryDifference;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryResultsExport;
use App\Http\Controllers\inventory\Imports\ProductsImport;

class InventoryController extends Controller
{
    //VER INVENTARIO
    public function showImportForm()
    {
        return view('livewire.inventory.inventory_control');
    }

    //IMPORTAR INVENTARIO
    public function import(Request $request)
    {
        $file = $request->file('file');
        $import = new ProductsImport;
        Excel::import($import, $file);

        $importedProducts = $import->getProducts();

        foreach ($importedProducts as $importedProduct) {
            Inventory::create([
                'barcode' => $importedProduct['codigo'],
                'description' => $importedProduct['descripcion'],
                'quantity_excel' => intval($importedProduct['cantidad']),
                'user_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('inventory.show');
    }

    //GUARDAR INVENTARIO
    public function saveScanned(Request $request)
    {
        $barcode = $request->input('barcode');
        $quantityCounted = intval($request->input('quantity_counted'));

        $inventory = Inventory::where('barcode', $barcode)->first();
        if ($inventory) {
            $inventory->quantity_counted = $quantityCounted;
            $inventory->save();
        }

        return redirect()->route('inventory.show');
    }

    //GENERAR DIFERENCIAS
    public function generateCount()
    {
        $inventories = Inventory::all();
        $results = [];

        foreach ($inventories as $inventory) {
            $difference = $inventory->quantity_excel - $inventory->quantity_counted;

            InventoryDifference::create([
                'barcode' => $inventory->barcode,
                'description' => $inventory->description,
                'quantity_excel' => $inventory->quantity_excel,
                'quantity_counted' => $inventory->quantity_counted,
                'difference' => $difference,
                'user_id' => $inventory->user_id,
            ]);

            $results[] = [
                'barcode' => $inventory->barcode,
                'description' => $inventory->description,
                'quantity_excel' => $inventory->quantity_excel,
                'quantity_counted' => $inventory->quantity_counted,
                'difference' => $difference,
                'user' => $inventory->user->name,
                'date' => $inventory->updated_at->format('d-m-Y H:i:s'),
            ];
        }

        Inventory::truncate();
        InventoryDifference::truncate();

        // Guardar los resultados en la sesión para usarlos en la descarga
        session(['inventory_results' => $results]);

        return view('livewire.inventory.inventory_results', compact('results'));
    }

        public function downloadInventoryResults()
    {
        $results = session('inventory_results');

        if (!$results) {
            return redirect()->back()->with('error', 'No hay resultados para descargar.');
        }

        $fileName = 'InventarioContado_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new InventoryResultsExport($results), $fileName);
    }
}