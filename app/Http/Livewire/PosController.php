<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use App\Models\Customer;
use App\Traits\JsonTrait;
use App\Models\SaleCredit;
use App\Models\SaleDetail;
use App\Models\Denomination;
use App\Models\SaleCreditPayment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PosController extends Component
{

    use JsonTrait;


    public $posCart;
    public $totalCart = 0;
    public $totalItems = 0;
    public $total, $cash, $change, $customers = [], $customerId,
    $customerName, $status, $payment_type, $payment_method, $discount, $discount_total,
    $custom_due_date;
    public $showDiscountInput = [];

    

    //ESCUCHAS DE EVENTOS
    protected $listeners = [
        'scan-code' => 'addProduct',
        'removeItem' => 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale',
        'refresh' => '$refresh',
        'scan-code-byid' => 'scanCodeById',
        'setCustomer',
        'print-json'
    ];

    public function mount()
    {
        $this->cash = 0;
        $this->change = 0;
        $this->customers = Customer::orderBy('name', 'ASC')->get();
        $this->status = 'PAGADO';
        $this->payment_type = 'CONTADO';
        $this->payment_method = 'EFECTIVO';
        $this->discount = 0;
        $this->discount_total = 0;
        $this->assignOccasionalCustomer();


        if (session()->has("posCart")) {
            $this->posCart = collect(session("posCart"));
        } else {
            $this->posCart = collect([]);
        }

        $this->total = $this->totalCart(); // Obtener el total del carrito
        $this->itemsQuantity = $this->totalItems(); // Obtener la cantidad total de ítems

    }
    

    public function render()
    {
        $this->itemsQuantity = $this->totalItems(); // Obtener la cantidad total de ítems
        $this->total = $this->totalCart(); // Obtener el total del carrito
        $this->posCart = $this->posCart->sortBy('name'); // Ordenar por nombre de forma ascendente

        return view('livewire.pos.component', [
            'denominations' => Denomination::get(),
            'posCart' => $this->posCart,
            'sales' => Sale::latest()->take(5)->get(), // Solo las 5 más recientes
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function getSales()
    {
        $this->emit('update-sales', Sale::latest()->take(10)->get());
    }

    //FUNCION PARA IMPRIMIR LAS VENTAS
    public function printSale($sale_id)
    {
        $b64 = $this->jsonData2($sale_id);
        $this->dispatchBrowserEvent('print-json', ['data' => $b64]);
    }

    //ASIGNAR CLIENTES OCACIONAL DE FORMA AUTOMATICA
    public function assignOccasionalCustomer()
    {
        $occasionalCustomerId = 1; // Reemplaza con el ID real del cliente ocasional
        $occasionalCustomer = Customer::find($occasionalCustomerId);

        if ($occasionalCustomer) {
            $this->customerId = $occasionalCustomer->id;
            $this->customerName = $occasionalCustomer->name;
        } else {
            $this->customerId = null; // O asigna un valor predeterminado
            $this->customerName = "Cliente no definido";
            $this->emit('error', 'Cliente ocasional no encontrado en la base de datos');
        }
    }

        public function addProduct($barcode, $quantity = 1)
    {
        // Primero, verifica si el código de barras pertenece a un producto por peso
        if ($this->isWeightedProductBarcode($barcode)) {
            // Intenta procesarlo como un producto por peso
            $this->addWeightProduct($barcode);
        } else {
            // Si no, procesarlo como un producto por unidad
            $this->addUnitProduct($barcode, $quantity);
        }
    }

    private function isWeightedProductBarcode($barcode)
    {
        // Verifica si el código empieza con "2"
        if (substr($barcode, 0, 1) == '2') {
            // Extrae el código del producto
            $productCode = ltrim(substr($barcode, 1, 6), '0');

            // Verifica si el producto es por peso
            $product = Product::where('barcode', $productCode)->first();

            return $product && $product->is_weighted;
        }

        return false; // Si no empieza con "2", no es por peso
    }

        public function addWeightProduct($barcode)
    {
        $barcode = (string) $barcode;

        $productCode = ltrim(substr($barcode, 1, 6), '0');
        $weight = round((float)substr($barcode, 7, 6) / 10000, 3);

        $product = Product::where('barcode', $productCode)->first();

        if (!$product || !$product->is_weighted) {
            $this->emit('scan-notfound', 'El producto no es válido para ventas por peso.');
            return;
        }

        if ($product->stock < $weight) {
            $this->emit('no-stock', 'Stock insuficiente para "' . $product->name . '".');
            return;
        }

        $pricePerKg = round($product->price_per_kg, 2);
        $subtotal = round($weight * $pricePerKg, 2);

        $exist = $this->posCart->firstWhere('id', $product->id);

        if ($exist) {
            $this->posCart = $this->posCart->map(function ($item) use ($product, $weight) {
                if ($item['id'] === $product->id) {
                    $item['quantity'] += $weight;
                    $item['total'] = round($item['quantity'] * $product->price_per_kg, 2);
                }
                return $item;
            });
        } else {
            $this->posCart->push([
                'id' => $product->id,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'quantity' => $weight,
                'price' => $pricePerKg,
                'total' => $subtotal,
                'w' => $product->is_weighted
            ]);
        }

        $this->updateCart();
        $this->emit('scan-ok', $exist ? 'Cantidad actualizada' : 'Producto agregado al carrito por peso.');
    }



    public function addUnitProduct($barcode, $quantity = 1)
    {
        $product = Product::where('barcode', $barcode)->first();
    
        if (!$product) {
            $this->emit('scan-notfound', 'El producto no está registrado');
            return;
        }
    
        if ($product->stock < $quantity) {
            $this->emit('no-stock', 'Stock insuficiente');
            return;
        }
    
        $exist = $this->posCart->firstWhere('id', $product->id);
    
        if ($exist) {
            // Si ya existe, aumenta la cantidad
            $this->increaseQty($product->id, $quantity);
            return;
        }
        
    
        // Agrega un nuevo producto al carrito
        $this->posCart->push([
            'id' => $product->id,
            'barcode' => $product->barcode,
            'name' => $product->name,
            'quantity' => $quantity,
            'price' => $product->price,
            'total' => $product->price * $quantity,
            'w' => $product->is_weighted
        ]);
    
        $this->updateCart();
        $this->emit('scan-ok', 'Producto agregado');
    }

    public function updateCart()
    {
        session(['posCart' => $this->posCart]);

        $this->total = round($this->posCart->sum(function ($item) {
            $precioBase = floatval($item['price']);
            $descuento = isset($item['manual_discount']) ? floatval($item['manual_discount']) : 0;
            $precioFinal = max($precioBase - $descuento, 0);
            return $precioFinal * $item['quantity'];
        }), 2);

        $this->itemsQuantity = $this->posCart->sum('quantity');
    }
    

    public function inCart($product_id)
    {
        return $this->posCart->where('id', $product_id)->count() > 0;
    }

    public function updateQuantity($product_id, $quantity)
    {
        if (!is_numeric($quantity)) {
            $this->emit('noty', 'EL VALOR DE LA CANTIDAD ES INCORRECTO');
            return;
        }

        $item = $this->posCart->where('id', $product_id)->first();
        $product = Product::find($product_id);

        if ($product->stock <= 0) {
            $this->emit('no-stock', 'Stock insuficiente');
            return;
        }

        if ($item) {
            if ($quantity > $product->stock) {
                $this->emit('no-stock', 'Stock insuficiente');
                return;
            }

            if ($quantity <= 0) {
                $this->removeItem($product_id);
                return;
            }

            $item['quantity'] = $quantity;
            $item['total'] = $item['price'] * $quantity;

            $this->posCart = $this->posCart->map(function ($cartItem) use ($item) {
                return $cartItem['id'] === $item['id'] ? $item : $cartItem;
            });

            $this->save();
            $this->emit('scan-ok', 'Cantidad actualizada');
        }
    }

    public function removeItem($product_id)
    {
        $this->posCart = $this->posCart->reject(function ($item) use ($product_id) {
            return $item['id'] === $product_id;
        });

        $this->save();
        $this->emit('scan-ok', 'Producto eliminado');
    }


    public function clearCart()
    {
        $this->posCart = collect([]);
        $this->showDiscountInput = [];
        $this->save();
        $this->emit('scan-ok', 'Carrito vaciado');
    }




    public function save()
    {
        session()->put("posCart", $this->posCart);
        session()->save();
        //Log::info('Carrito guardado en la sesión');
        //Log::info(json_encode($this->posCart));
    }

    public function totalCart()
    {
        if ($this->posCart === null) {
            $this->posCart = collect([]);
        }
    
        return round($this->posCart->sum(function ($item) {
            $precioBase = floatval($item['price']);
            $descuento = isset($item['manual_discount']) ? floatval($item['manual_discount']) : 0;
            $precioFinal = max($precioBase - $descuento, 0);
            return round($precioFinal * floatval($item['quantity']), 2);
        }), 2);
    }
    

    public function totalItems()
    {
        if ($this->posCart === null) {
            $this->posCart = collect([]);
        }

        return $this->posCart->count('quantity');
        //return $this->posCart->sum('quantity');
    }

    //GUARDAR VENTAS
    public function saveSale()
    {
        if ($this->total <= 0) {
            $this->emit('sale-error', 'AGREGA PRODUCTOS A LA VENTA');
            return;
        }
        if ($this->cash < 0 || !is_numeric($this->cash)) {
            $this->emit('sale-error', 'INGRESA UN MONTO VÁLIDO DE EFECTIVO');
            return;
        }
        if ($this->total > $this->cash && $this->payment_type == 'CONTADO') {
            $this->emit('sale-error', 'EL EFECTIVO ES MENOR AL TOTAL');
            return;
        }
        if ($this->customerId == null) {
            $this->emit('sale-error', 'DEBES SELECCIONAR UN CLIENTE');
            return;
        }
    
        DB::beginTransaction();
    
        try {
            // Determinar el estado de la venta basado en el tipo de pago
            $saleStatus = ($this->payment_type == 'CREDITO' && $this->total > $this->cash) ? 'PENDIENTE' : 'PAGADO';
    
            // Registrar la venta en `sales`
            $sale = Sale::create([
                'total' => round($this->total, 2),
                'items' => $this->itemsQuantity,
                'cash' => round($this->cash, 2),
                'change' => round($this->cash - $this->total, 2),
                'user_id' => Auth::user()->id,
                'customer_id' => $this->customerId,
                'status' => $saleStatus,
                'payment_type' => $this->payment_type,
                'payment_method' => $this->payment_method,
                'discount' => round($this->discount, 2),
                'discount_total' => round($this->discount_total, 2)
            ]);

            // ❌ Validar que una venta a crédito PENDIENTE no tenga cliente inválido
            if (
                $saleStatus === 'PENDIENTE' &&
                $this->payment_type === 'CREDITO'
            ) {
                $cliente = Customer::find($this->customerId);
                $nombreCliente = strtolower(trim($cliente->name ?? ''));

                if (in_array($nombreCliente, ['ocacional', 'desconocido'])) {
                    $this->emit('sale-error', 'No se puede registrar una venta a crédito pendiente con un cliente "ocacional" o "desconocido".');
                    return;
                }
            }

    
            // 📌 **Si la venta es a CRÉDITO, registrar el crédito en `sale_credits`**
            if ($this->payment_type == 'CREDITO') {

                // Obtener cliente y deuda actual
                $customer = Customer::find($this->customerId);

                if (!$customer) {
                    $this->emit('sale-error', 'Cliente no encontrado.');
                    return;
                }
                
                $currentCredit = SaleCredit::where('customer_id', $this->customerId)
                    ->where('status', 'PENDIENTE')
                    ->sum('remaining_balance');

                $nuevoCredito = $this->total - $this->cash;

                if (($currentCredit + $nuevoCredito) > $customer->credit_limit) {
                    $this->emit('sale-error', 'El cliente ha superado su límite de crédito.');
                    return;
                }

                // Calcular el saldo pendiente correctamente
                $remainingBalance = round($this->total - $this->cash, 2);
    
                // Evitar valores negativos en `remaining_balance`
                if ($remainingBalance < 0) {
                    $remainingBalance = 0;
                }
    
                // Determinar el estado del crédito
                $creditStatus = ($remainingBalance > 0) ? 'PENDIENTE' : 'PAGADO';

                $dueDate = now()->addDays(30); // valor por defecto

                // Si desde la vista se envía otra fecha, usala (esto requiere un nuevo campo público en el componente)
                if ($this->custom_due_date) {
                    $dueDate = $this->custom_due_date;
                }
    
                $credit = SaleCredit::create([
                    'sale_id' => $sale->id,
                    'customer_id' => $this->customerId,
                    'total_credit' => round($this->total, 2),
                    'amount_paid' => round($this->cash, 2), // Guardar lo que ya pagó el cliente
                    'remaining_balance' => round($remainingBalance, 2), // Registrar lo que falta por pagar
                    'status' => $creditStatus,
                    'due_date' => $dueDate
                ]);
    
                // **Registrar automáticamente el pago parcial si el cliente dio dinero**
                if ($this->cash > 0) {
                    SaleCreditPayment::create([
                        'credit_id' => $credit->id,
                        'amount_paid' => round(min($this->cash, $credit->total_credit), 2), // Asegurar que no pague más del crédito
                        'payment_date' => now(),
                        'user_id' => Auth::user()->id
                    ]);
                }
            }
    
            // 📌 **Registrar los productos vendidos en `sale_details`**
            foreach ($this->posCart as $item) {
                SaleDetail::create([
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'sub_total' => $this->calcularPrecioFinal($item) * $item['quantity'],
                    'manual_price' => $item['manual_price'] ?? null,
                    'manual_discount' => $item['manual_discount'] ?? null,
                    'manual_discount_percent' => $item['manual_discount_percent'] ?? null,
                    'product_id' => $item['id'],
                    'sale_id' => $sale->id
                ]);
                
    
                // 📌 **Actualizar el stock**
                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }
    
            DB::commit();
    
            // 📌 **Limpiar carrito**
            $this->completeSale();
    
            // 📌 **Generar JSON para impresión**
            $b64 = $this->jsonData2($sale->id);
    
            // Primera impresión
            $this->dispatchBrowserEvent('print-json', ['data' => $b64]);
    
            // Programar la segunda impresión con retraso
            $this->dispatchBrowserEvent('print-json-delayed', ['data' => $b64]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('sale-error', $e->getMessage());
        }
    
        $this->assignOccasionalCustomer();
        $this->getSales();
    }
    

    public function completeSale()
    {
        // Limpiar carrito
        $this->clearCart();
        $this->cash = 0;
        $this->change = 0;
        $this->total = $this->totalCart();
        $this->itemsQuantity = $this->totalItems();
        $this->payment_type = 'CONTADO'; // Restablecer a 'CONTADO'
        $this->showDiscountInput = [];
        $this->emit('sale-ok', 'Venta realizada');
       
    }

    public function setCustomer($customerId, $customerName)
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
    }

    public function ACash($value)
    {
        $this->cash +=  ($value == 0 ? $this->total : $value);
        $this->change = ($this->cash - $this->total);
    }

    public function resetCustomer()
    {
        $this->customerId = null;
        $this->customerName = null;
    }

    public function scanCodeById($productId)
    {
        $this->increaseQty($productId);
    }

    // =======================
    // 🔸 DESCUENTOS MANUALES
    // =======================

    public function updateManualDiscount($productId, $value)
    {
        $this->posCart = $this->posCart->map(function ($item) use ($productId, $value) {
            if ($item['id'] === $productId) {
                $item['manual_discount'] = floatval($value);
                $precioFinal = max($item['price'] - floatval($value), 0);
                $item['total'] = $precioFinal * $item['quantity'];
            }
            return $item;
        });
    
        $this->updateCart();
        $this->emit('scan-ok', 'Descuento aplicado');
    }    

    private function calcularPrecioFinal($item)
    {
        $precioBase = floatval($item['price']);

        if (isset($item['manual_price'])) {
            return floatval($item['manual_price']);
        }

        if (isset($item['manual_discount'])) {
            return max($precioBase - floatval($item['manual_discount']), 0);
        }

        if (isset($item['manual_discount_percent'])) {
            $descuento = $precioBase * (floatval($item['manual_discount_percent']) / 100);
            return max($precioBase - $descuento, 0);
        }

        return $precioBase;
    }


        public function increaseQty($productId, $cant = 1)
    {
        if (!$productId) {
            $this->emit('no-stock', 'ID de producto no válido');
            return;
        }

        $product = Product::find($productId);
        if (!$product) {
            $this->emit('no-stock', 'Producto no encontrado');
            return;
        }

        $exist = $this->posCart->firstWhere('id', $productId);

        if ($exist) {
            if ($product->stock < ($cant + $exist['quantity'])) {
                $this->emit('no-stock', 'Stock insuficiente para "' . $product->name . '"');
                return;
            }

            $this->posCart = $this->posCart->map(function ($item) use ($exist, $cant) {
                if ($item['id'] === $exist['id']) {
                    $item['quantity'] += $cant;
                    $item['total'] = round($item['quantity'] * $item['price'], 2);
                }
                return $item;
            });
        } else {
            if ($product->stock < $cant) {
                $this->emit('no-stock', 'Stock insuficiente para "' . $product->name . '"');
                return;
            }

            $this->posCart->push([
                'id' => $product->id,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'quantity' => $cant,
                'price' => $product->price,
                'total' => round($product->price * $cant, 2),
            ]);
        }

        $this->updateCart();
        $this->emit('scan-ok', $exist ? 'Cantidad actualizada' : 'Producto agregado al carrito');
    }


public function decreaseQty($productId)
{
    // Buscar el producto en el carrito
    $item = $this->posCart->firstWhere('id', $productId);

    if ($item) {
        // Eliminar el producto del carrito
        $this->posCart = $this->posCart->reject(function ($cartItem) use ($productId) {
            return $cartItem['id'] === $productId;
        });

        // Calcular la nueva cantidad
        $newQty = $item['quantity'] - 1;

        // Si la nueva cantidad es mayor que 0, agregar el producto de nuevo al carrito con la nueva cantidad
        if ($newQty > 0) {
            $item['quantity'] = $newQty;
            $item['total'] = $item['price'] * $newQty;
            $this->posCart->push($item);
        }

        // Guardar el carrito en la sesión
        $this->save();

        // Actualizar el total y la cantidad de ítems en el carrito
        $this->total = $this->totalCart();
        $this->itemsQuantity = $this->totalItems();

        // Emitir evento
        $this->emit('scan-ok', 'Cantidad actualizada');
    } else {
        $this->emit('scan-notfound', 'El producto no está en el carrito');
    }
}

public function toggleDiscountInput($productId)
{
    $this->showDiscountInput[$productId] = !($this->showDiscountInput[$productId] ?? false);
}




}