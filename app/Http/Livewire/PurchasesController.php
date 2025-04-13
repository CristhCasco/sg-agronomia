<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\CartTrait;
use Illuminate\Support\Arr;
use App\Models\Denomination;
use App\Models\PurchaseDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Component
{


    //use CartTrait;

    protected $listeners = [
        'addProduct' => 'addProduct',
        'removeItem',
        'addProductFromPurchase',
        'setSupplier'
    ];

    //laravel collections 
    public $carrito;
    public $totalCarrito = 0;
    public $totalItems = 0; // popiedad para guardar la cantidad de productos agregados al carrito
    public $total, $itemsQuantity, $efectivo, $change, $price, $suppliers = [], $supplierId, $supplierName,
        $status, $payment_type, $payment_method, $discount, $discount_total;
    public $margen_ganancia = 30; // Valor por defecto del 30%
    public $due_date;



    function mount()
    {

        $this->efectivo = 0;
        $this->change = 0;
        //$this->total = totalCart();
        //$this->itemsQuantity = totalItems();
        $this->suppliers = Supplier::orderBy('name', 'asc')->get();
        $this->status = 'PAGADO';
        $this->payment_type = 'CONTADO';
        $this->payment_method = 'EFECTIVO';
        $this->discount = 0;
        $this->discount_total = 0;


        // Asignar PROVEEDOR ocasional por defecto
        $occasionalsupplierId = 1; // Reemplaza con el ID real del cliente ocasional
        $occasionalSupplier = Supplier::find($occasionalsupplierId);
        if ($occasionalSupplier) {
            $this->supplierId = $occasionalSupplier->id;
            $this->supplierName = $occasionalSupplier->name;
        }

        if (session()->has("carrito")) {
            $this->carrito = session("carrito");
        } else {
            $this->carrito = new Collection; //nueva coleccion de laravel
        }
        //$this->carrito = $this->carrito->sortBy(['name', ['name', 'asc']]);
        $this->carrito = collect(session('carrito', []));
    }


    // public function getContent(): Collection
    // {
    //     return $this->carrito->sortBy(['name', ['name', 'asc']]);

    // }
    function addProductFromPurchase($code)
    {
        // dd($code);
        $this->addProduct(0, 1, $code);
    }

    public function addProduct($productId, $qty = 1, $barcode = null) //1
    {
        if ($barcode == null)
            $product = Product::find($productId);
        else
            $product = Product::where('barcode', $barcode)->first();



        $coll = collect(
            [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $qty,
                'cost' => 1,
                'total' => 1 * $qty,
                'image' => $product->image
            ]
        );

        //si no esta en el carrito lo inserta
        if (!$this->InCart($product->id)) {

            $pre = Arr::add($coll, null, null);
            $this->carrito->push($pre);
            $this->save();
            $this->emit('purchase-ok', 'Producto agregado');

            //
        } else {
            $this->Increment($product->id, $qty);
        }
    }


    //Esta función, InCart, está diseñada para verificar si un producto específico
    // ya está en el carrito de compras.
    public function InCart($id)
    {
        $mycart = $this->carrito;
        $cont = $mycart->where('id', $id)->count();
        return $cont > 0 ? true : false;
    }

    public function Increment($id, $cant = 1, $replace = false)
    //$cant =1;
    {
        $mycart = $this->carrito;
        $oldItem = $mycart->where('id', $id)->first();
        $newItem = $oldItem;

        if ($replace)
            $newItem['qty'] = $cant;
        else
            $newItem['qty'] += $cant;


        $newItem['total'] =   $newItem['qty'] * $newItem['cost'];

        //delete from cart       
        $this->carrito = $this->carrito->reject(function ($product) use ($id) {
            return $product['id'] === $id;
        });
        $this->save();

        //add item to cart           
        $this->carrito->push(Arr::add(
            $newItem,
            null,
            null
        ));
        $this->save();

        $this->emit('purchase-ok', 'Producto actualizado');
    }


    public function UpdateQty($id, $cant = 1)
    {
        $mycart = $this->carrito;
        $oldItem = $mycart->where('id', $id)->first();
        $newItem = $oldItem;


        $newItem['qty'] = $cant;

        $newItem['total'] =   $newItem['qty'] * $newItem['cost'];

        //delete from cart       
        $this->carrito = $this->carrito->reject(function ($product) use ($id) {
            return $product['id'] === $id;
        });
        $this->save();

        if ($cant > 0) {
            //add item to cart           
            $this->carrito->push(Arr::add(
                $newItem,
                null,
                null
            ));
            $this->save();

            $this->emit('purchase-ok', 'Producto actualizado');
        } else {
            // -1, se elimina producto del carrito
        }
    }


    public function setCost($id, $cost = 1)
    {

        if (empty($cost) || strlen($cost) < 1 || is_numeric(!$cost)) {
            $this->emit('purchase-error', 'El Costo es Inválido');
            return;
        }


        $mycart = $this->carrito;
        $oldItem = $mycart->where('id', $id)->first();
        $newItem = $oldItem;

        $newItem['cost'] = $cost;

        $newItem['total'] =   $newItem['qty'] * $newItem['cost'];

        //delete from cart       
        $this->carrito = $this->carrito->reject(function ($product) use ($id) {
            return $product['id'] === $id;
        });
        $this->save();

        //add item to cart           
        $this->carrito->push(Arr::add(
            $newItem,
            null,
            null
        ));
        $this->save();

        $this->emit('purchase-ok', 'Precio de Compra actualizado');
    }



    //eliminar producto
    public function removeItem($id)
    {
        //dd($id);//4
        $this->carrito = $this->carrito->reject(function ($product) use ($id) {
            return $product['id'] === intval($id);
        });
        $this->save();
    }



    public function clear()
    {
        $this->carrito = new Collection;
        $this->save();
    }


    public function save()
    {
        session()->put("carrito", $this->carrito);
        session()->save();
    }



    // public function totalCart()
    // {
    //     $amount = $this->carrito->sum(function ($product) {
    //         return round($product['total']);
    //     });
    //     return $amount;
    // }

        public function totalCart()
    {
        return round($this->subtotalCart(), 2);
    }



    public function totalItems()
    {
        $items = $this->carrito->sum(function ($product) {
            return $product['qty'];
        });
        return $items;
    }


    public function render()
    {
        //$this->removeItem(4);   
        // $this->clear();
        // $producto = Product::find(1);
        //$this->addProduct($producto, 2);  

        $this->totalCarrito = $this->totalCart();  //obtienes el total de los productos agregados al carrito
        $this->totalItems = $this->totalItems();

        $this->carrito = $this->carrito->sortBy(['name', 'desc']);

        return view('livewire.purchases.component2', [
            'denominations' => Denomination::get(),
            'carrito' => $this->carrito
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    // public function subtotalCart()
    // {
    //     $subtNeto = $this->carrito->sum(function ($product) {

    //         return $product['cost'] * $product['qty'];
    //     });

    //     return $subtNeto;
    // }

        public function subtotalCart()
    {
        return round($this->carrito->sum(function ($product) {
            return round($product['cost'], 2) * round($product['qty'], 3);
        }), 2);
    }


    public function savePurchase()
    {
        $supplier = Supplier::find($this->supplierId);

        if (!$supplier) {
            $this->dispatchBrowserEvent('purchase-error', ['msg' => 'Proveedor no encontrado.']);
            return;
        }

        // ⚠️ Bloquear si proveedor es "ocacional" o "desconocido" y la compra es a crédito
        $nombre = strtolower($supplier->name);
        if ($this->payment_type === 'CREDITO' && in_array($nombre, ['ocacional', 'desconocido'])) {
            $this->dispatchBrowserEvent('purchase-error', ['msg' => 'No se permite hacer compras a crédito con proveedores ocacionales o desconocidos.']);
            return;
        }
        if ($this->payment_type == 'CONTADO' && $this->payment_method == 'EFECTIVO') {
            if (empty($this->efectivo) || !is_numeric($this->efectivo) || $this->efectivo <= 0) {
                $this->dispatchBrowserEvent('purchase-error', ['msg' => 'EL MONTO DEL EFECTIVO ES INVÁLIDO']);
                return;
            }
        }
    
        if ($this->supplierId == null) {
            $this->dispatchBrowserEvent('purchase-error', ['msg' => 'SELECCIONA EL PROVEEDOR']);
            return;
        }
    
        DB::beginTransaction();
    
        try {
            $status = $this->payment_type == 'CREDITO' ? 'PENDIENTE' : 'PAGADO';
    
            $purchase = Purchase::create([
                'items'          => $this->totalItems(),
                'sub_total'      => round($this->subtotalCart(), 2),
                'total'          => $this->totalCart(),
                'cash'           => 0,
                'change'         => 0,
                'status'         => $status,
                'payment_type'   => $this->payment_type,
                'payment_method' => $this->payment_method,
                // 'due_date' => $this->payment_type == 'CREDITO' ? now()->addDays(30) : null,
                'discount'       => 0,
                'discount_total' => 0,
                'supplier_id'    => $this->supplierId,
                'user_id'        => auth()->id()
            ]);
    
            foreach ($this->carrito as $item) {
                PurchaseDetail::create([
                    'price'       => $item['cost'],
                    'quantity'    => $item['qty'],
                    'product_id'  => $item['id'],
                    'purchase_id' => $purchase->id,
                    'total'       => $item['total']
                ]);
    
                $product = Product::find($item['id']);
                $product->stock += $item['qty'];
                $product->cost = $item['cost'];
    
                if (isset($item['margen']) && is_numeric($item['margen']) && $item['margen'] >= 0) {
                    $ganancia = $item['cost'] * ($item['margen'] / 100);
                    $product->price = round($item['cost'] + $ganancia, 2);
                }
    
                $product->save();
            }
    
            // Crea el crédito desde la relación definida en el modelo
            if ($this->payment_type === 'CREDITO') {
                $purchase->credit()->create([
                    'supplier_id'       => $this->supplierId,
                    'total_credit'      => $this->totalCart(),
                    'amount_paid'       => 0,
                    'remaining_balance' => $this->totalCart(),
                    'status'            => 'PENDIENTE',
                   'due_date'          => $this->due_date ?? now()->addDays(30),
                ]);
            }
    
            DB::commit();
    
            $this->clear();
            $this->efectivo = 0;
            $this->change = 0;
    
            $this->dispatchBrowserEvent('purchase-ok', ['msg' => 'Compra Registrada']);
            $this->resetSupplier();
            $this->resetPaymentInfo();
            $this->mostrarResumen = false;
    
        } catch (\Throwable $th) {
            DB::rollback();
            $this->dispatchBrowserEvent('purchase-error', ['msg' => $th->getMessage()]);
        }
    }
    

    public function ACash($value)
    {
        $this->efectivo =  $this->totalCarrito;
        $this->change = ($this->efectivo - $this->totalCarrito);
    }

    public function setSupplier($supplierId, $supplierName)
    {
        $this->supplierName = $supplierName;
        $this->supplierId = $supplierId;
    }

    public function resetSupplier()
    {
        $this->supplierId = '';
        $this->supplierName = '';
    }

    public function updateCost($id, $cost)
    {
        foreach ($this->carrito as $index => $item) {
            if ($item['id'] == $id) {
                $this->carrito[$index]['cost'] = is_numeric($cost) ? floatval($cost) : 0;
                $this->carrito[$index]['total'] = round($this->carrito[$index]['qty'] * $this->carrito[$index]['cost'], 2);

                // Guarda apenas se modifica
                session()->put('carrito', $this->carrito);
                session()->save();

                break;
            }
        }

        $this->totalCarrito = $this->totalCart();
        $this->totalItems = $this->totalItems();

        $this->emit('purchase-ok', 'Costo actualizado automáticamente');
    }

    public function updatedCarrito($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if (!isset($this->carrito[$index])) return;

        $item = $this->carrito[$index];

        $item['qty'] = isset($item['qty']) && is_numeric($item['qty']) ? floatval($item['qty']) : 1;
        $item['cost'] = isset($item['cost']) && is_numeric($item['cost']) ? floatval($item['cost']) : 0;
        $item['total'] = round($item['qty'] * $item['cost'], 2);

        $this->carrito[$index] = $item;

        // Guardar en la sesión SIEMPRE
        session()->put('carrito', $this->carrito);
        session()->save();

        $this->totalCarrito = $this->totalCart();
        $this->totalItems = $this->totalItems();

        $this->emit('purchase-ok', 'Producto actualizado automáticamente');
    }


    public $mostrarResumen = false;

    public function confirmarResumen()
    {
        $this->mostrarResumen = true;
    }

    public function resetPaymentInfo()
{
    $this->payment_type = 'CONTADO';
    $this->payment_method = 'EFECTIVO';
    $this->due_date = now()->addDays(30)->format('Y-m-d');
    $this->efectivo = 0;
    $this->change = 0;
}





}
