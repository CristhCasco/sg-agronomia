<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Company;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Log;

trait JsonTrait {


    function jsonData($sale_id) {

        $sale = Sale::find($sale_id);

        $detalle = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
        ->select('sale_details.*', 'p.name')
        ->where('sale_details.sale_id', $sale_id)
        ->orderBy('p.name')
        ->get();


        $cliente =  $sale->customer;

        $user = $sale->user;


        $json = $sale->toJson(). '|' . $detalle->toJson(). '|' . $cliente->toJson() . '|' . $user->toJson();

        $b64 = base64_encode($json);

        return $b64;
    }

    //ORIGINAL
    public function jsonData20($sale_id) 
    { 
        
        //FILTRAR UNICAMENTE LAS COLUMNAS QUE NECESITO
        $sale = Sale::select('id','user_id','customer_id','total','items','status','payment_type','cash','change', 'created_at')
        ->find($sale_id);

        // Ajusta la zona horaria después de recuperar el registro
        $sale->created_at = Carbon::parse($sale->created_at, 'UTC')->setTimezone('America/Asuncion');

        
        $detalle = $sale->details()
        ->select('product_id', 'quantity', 'price', 'sub_total') // Incluye los campos necesarios
        ->with('product:id,name')
        ->get();

        
        $cliente = $sale->customer()->select('id', 'name', 'last_name')->first();
    
        $user = $sale->user;
        $company = Company::select('id', 'name', 'address', 'phone', 'ruc')->first();

        $sale->unsetRelation('user');
        $sale->unsetRelation('customer');

        $size = array('size' => 80, 'type' => 'receipt');

        //$size = array('size' => 58, 'type' => 'receipt');

        //$size = array('size' => '52x25', 'type' => 'label');

       

        $json = $sale->toJson(). '|' . $detalle->toJson(). '|' . $cliente->toJson() . '|' . $user->toJson() . '|' . json_encode($size) . '|' . json_encode($company);

        dd($json);

        $compres = gzcompress($json, 9);

        $b64 = base64_encode($compres);

        return $b64;
    }

    public function jsonData2($sale_id) 
    { 
        // FILTRAR UNICAMENTE LAS COLUMNAS QUE NECESITO
        $sale = Sale::select('id', 'user_id', 'customer_id', 'total', 'items', 'status', 'payment_type', 'cash', 'change', 'created_at')
            ->find($sale_id);

        // Ajusta la zona horaria después de recuperar el registro
        $sale->created_at = Carbon::parse($sale->created_at, 'UTC')->setTimezone('America/Asuncion');

        // Convertir los valores de la venta a enteros (redondeando si es necesario)
        $sale->total = (int) round($sale->total);
        $sale->items = (int) round($sale->items); // Convertir items totales a enteros
        $sale->cash = (int) round($sale->cash);
        $sale->change = (int) round($sale->change);

        // FILTRAR COLUMNAS DE TABLA DETALLES
        $detalle = $sale->details()
            ->select('product_id', 'quantity', 'price', 'sub_total') // Incluye los campos necesarios
            ->with('product:id,name')
            ->get()
            ->map(function ($item) {
                // Convertir los valores a enteros
                $item->quantity = (float) round($item->quantity); // Cantidad
                $item->price = (float) round($item->price); // Precio
                $item->sub_total = (float) round($item->sub_total); // Subtotal
                return $item;
            });

        $cliente = $sale->customer()->select('id', 'name', 'last_name')->first();
        $user = $sale->user;
        $company = Company::select('id', 'name', 'address', 'phone', 'ruc')->first();

        // Generar información adicional del tamaño del documento
        $size = array('size' => 80, 'type' => 'receipt');

        // Construir el JSON para enviarlo
        $json = $sale->toJson() . '|' . $detalle->toJson() . '|' . $cliente->toJson() . '|' . $user->toJson() . '|' . json_encode($size) . '|' . json_encode($company);

        //dd($json);
        // Comprimir y codificar en base64
        $compres = gzcompress($json, 9);
        $b64 = base64_encode($compres);
        //dd($b64);
        //Log::info($b64);
        return $b64;
    }




    function jsonData3($sale_id)
    {
        // {"id":20,"user_id":1,"customer_id":1,"total":100000,"items":1,"status":"PAGADO","payment_type":"CONTADO","cash":100000,"change":0,"user":{"id":1,"user_name":"Admin"},"customer":{"id":1,"customer_name":"DESCONOCIDO"},"details":[{"sale_id":20,"product_id":169,"quantity":1,"price":100000,"product":{"id":169,"name":"ADAPTADOR DE CORRIENTE USB 20W"}}]}


        //PARA HACER ESTA COSULTA SE UTILIZA TODAS LAS RELACIONES
        $sale = Sale::select('id','user_id','customer_id','total','items','status','payment_type','cash','change')
        //CON EL METODO WITH SE CARGA LAS RELACIONES DEL MODELO SALE //OBS SIEMPRE COLOCAR EL ID SOINO DEVUELVE NULL
        ->with(['user'=> function ($query) {
            $query->select('id','name as user_name');
        },  'customer' => function ($query) {
            $query->select('id', 'name as customer_name');
        },  'details' => function ($query) {
            $query->select('sale_id', 'product_id', 'quantity', 'price');
            //INGRESAR AL DETALLE Y LUEGO A SU RELACION CON PRODUCT
        }, 'details.product' => function ($query) {
            $query->select('id', 'name');
        }])
        ->find($sale_id);

        $json = $sale->toJson();

        $compres = gzcompress($json, 9);

       // dd($compres);

        $b64 = base64_encode($compres);

        return $b64;
    }
}