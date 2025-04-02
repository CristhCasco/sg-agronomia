<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Company;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Log;

trait JsonTrait {


    function jsonData($sale_id) {

        //venta - detalle -product - cliente -user

        //"id":20,"items":1,"total":100000,"cash":100000,"change":0,"status":"PAGADO","payment_type":"CONTADO","payment_method":"EFECTIVO","discount":0,"discount_total":0,"user_id":1,"customer_id":1,"created_at":"2024-07-12T02:07:05.000000Z","updated_at":"2024-07-12T02:07:05.000000Z","customer":{"id":1,"person":"FISICA","name":"DESCONOCIDO","last_name":"NUN","ci":1,"company":"","ruc":"","address":"CDE","phone":"1","birthday":null,"email":null,"image":null,"created_at":"2023-12-12T12:22:30.000000Z","updated_at":"2023-12-12T23:23:35.000000Z"},"user":{"id":1,"name":"Admin","last_name":"Casco","ci":6560887,"user":"admin","email":"cristhian.casco@hotmail.com","phone":"0983668960","address":"CDE","profile":"Admin","status":"ACTIVO","image":"https:\/\/dummyimage.com\/200x150\/5c5756\/fff","email_verified_at":null,"created_at":"2023-12-12T12:19:08.000000Z","updated_at":"2023-12-12T12:26:41.000000Z"}}
        //|[{"id":23,"price":100000,"quantity":1,"sub_total":100000,"sale_id":20,"product_id":169,"created_at":"2024-07-12T02:07:05.000000Z","updated_at":"2024-07-12T02:07:05.000000Z","name":"ADAPTADOR DE CORRIENTE USB 20W"}]
        //|{"id":1,"person":"FISICA","name":"DESCONOCIDO","last_name":"NUN","ci":1,"company":"","ruc":"","address":"CDE","phone":"1","birthday":null,"email":null,"image":null,"created_at":"2023-12-12T12:22:30.000000Z","updated_at":"2023-12-12T23:23:35.000000Z"}
        //|{"id":1,"name":"Admin","last_name":"Casco","ci":6560887,"user":"admin","email":"cristhian.casco@hotmail.com","phone":"0983668960","address":"CDE","profile":"Admin","status":"ACTIVO","image":"https:\/\/dummyimage.com\/200x150\/5c5756\/fff","email_verified_at":null,"created_at":"2023-12-12T12:19:08.000000Z","updated_at":"2023-12-12T12:26:41.000000Z"}

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

      
        //dd($sale->created_at);
        //Carbon::parse($sale->created_at)->toDateTimeString();
        //dd(date_default_timezone_get());


        //FILTRAR COLUMNAS DE TABLA DETALLES
        // $detalle = $sale->details()->select('product_id','quantity','price')
        // //TRAER LA RELACION DEL MODEL DE DETALLE DE VENTA CON EL ID DE PRODUCTO LE DIGO QUE ME TRAIGA SU NOMBRE ('RELACION:IDENTIFICADOR,NOMBRE')
        // ->with('product:id,name')
        // ->get();
        
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