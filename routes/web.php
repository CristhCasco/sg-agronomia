<?php

use App\Models\SaleCredit;
use App\Http\Livewire\Dash;
use App\Http\Livewire\PosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PurchasesReport;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\AsignarController;
use App\Http\Livewire\CashoutController;
use App\Http\Livewire\ReportsController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\ProductsController;
use App\Http\Controllers\ExportController;
use App\Http\Livewire\CustomersController;

//EXPORTAR PRODUCTOS
use App\Http\Livewire\PurchasesController;
//IMPORTAR PRODUCTOS
use App\Http\Livewire\SuppliersController;

use App\Http\Livewire\CategoriesController;
use App\Http\Livewire\SaleCreditsController;
use App\Http\Livewire\DenominationsController;
use App\Http\Livewire\PurchasesCreditsController;
use App\Http\Controllers\inventory\Imports\InventoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', 'LoginController@login');
});

Auth::routes(['register' => false]); // deshabilitamos el registro de nuevos users

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', Dash::class);

//Rutas  protegidos por autenticacion
Route::middleware(['auth'])->group(function () {

    Route::get("categories", CategoriesController::class)->name('categories');
    Route::get("products", ProductsController::class)->name('products');
    Route::get("customers", CustomersController::class)->name('customers');
    Route::get("suppliers", SuppliersController::class)->name('suppliers');
    Route::get("pos", PosController::class)->name('pos');
    Route::get("purchases", PurchasesController::class)->name('purchases');
    Route::get('/credits/sales', SaleCreditsController::class)->name('sale-credits');
    Route::get('/credits/purchases', PurchasesCreditsController::class)->name('purchase-credits');


    Route::group(['middleware' => ['role:admin']], function () {

        Route::get("users", UsersController::class)->name('users');
        Route::get("roles", RolesController::class)->name('roles');
        Route::get("permissions", PermisosController::class)->name('permissions');
        Route::get("assign", AsignarController::class)->name('assign');
        Route::get("denominations", DenominationsController::class)->name('denominations');
    });

    Route::get("cashout", CashoutController::class)->name('cashout');
    Route::get("reports", [HomeController::class, 'reports'])->name('reports');
    Route::get("sales/reports", ReportsController::class)->name('sales.reports');
    Route::get("purchases/reports", PurchasesReport::class)->name('purchases.reports');

    //reportes PDF
    Route::get('report/pdf/{user}/{type}/{f1}/{f2}', [ExportController::class, 'reportPDF']);
    Route::get('report/pdf/{user}/{type}', [ExportController::class, 'reportPDF']);

    Route::get('report/purchases/pdf/{user}/{type}', [ExportController::class, 'reportPurchasePDF']);
    Route::get('report/purchases/pdf/{user}/{type}/{f1}/{f2}', [ExportController::class, 'reportPurchasePDF']);
    Route::get('/reports/inventory', [ReportsController::class, 'inventory'])->name('inventory.reports');

    //EXPORTAR PRODUCTOS A EXCEL
    Route::get('export/products', [ExportController::class, 'exportProducts'])->name('export.products');

    //CONTROL DE INVENTARIO
    Route::get('inventory', [InventoryController::class, 'showImportForm'])->name('inventory.show');
    Route::post('inventory/import', [InventoryController::class, 'import'])->name('inventory.import');
    Route::post('inventory/saveScanned', [InventoryController::class, 'saveScanned'])->name('inventory.saveScanned');
    Route::post('inventory/generateCount', [InventoryController::class, 'generateCount'])->name('inventory.generateCount');
    Route::post('/inventory/download', [InventoryController::class, 'downloadInventoryResults'])->name('inventory.download');

});
