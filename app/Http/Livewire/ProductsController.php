<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Product;
use Intervention\Image\Facades\Image;

class ProductsController extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $name,$barcode,$brand,$model,$size,$color,$description,$cost,$price,$stock,
    $min_stock,$categoryid,$image,$search,$selected_id,$pageTitle,$componentName,$is_weighted, $price_per_kg;
    private $pagination = 10;

    //para ver el estado de la paginacion personalizada
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    //para inicializar las variables
    public function mount ()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->categoryid = 'Elegir';
    }

    public function render()
    {
        if (strlen($this->search) > 0) {
            // Para buscar por nombre o código de barras
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->select('products.*', 'c.name as category')
                ->where('products.name', 'like', '%' . $this->search . '%')
                ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                ->orWhere('products.model', 'like', '%' . $this->search . '%')
                ->orWhere('products.brand', 'like', '%' . $this->search . '%')
                ->orWhere('products.description', 'like', '%' . $this->search . '%')
                ->orderBy('products.id', 'DESC')
                ->paginate($this->pagination);
        } else {
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->select('products.*', 'c.name as category')
                ->orderBy('products.id', 'DESC')
                ->paginate($this->pagination);
        }

        return view('livewire.products.component', [
            'data' => $products,
            'categories' => Category::orderBy('name', 'ASC')->get()
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

protected    $rules = [
        'name' => 'required|min:3',
        'barcode' => 'required|unique:products',
        'categoryid' => 'required|not_in:Elegir',
        //'cost' => 'required|numeric|min:0',
        'price' => 'required_if:is_weighted,false|nullable|numeric|min:0', // Requerido para productos por unidad
        'price_per_kg' => 'required_if:is_weighted,true|nullable|numeric|min:0', // Requerido para productos por peso
        'stock' => 'required|numeric|min:0',
        //'min_stock' => 'required|numeric|min:0',
        //'is_weighted' => 'boolean',
    ];

   protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'barcode.required' => 'El código de barras es obligatorio.',
        'barcode.unique' => 'El código de barras ya existe.',
        'categoryid.not_in' => 'Seleccione una categoría.',
        'cost.required' => 'El costo es obligatorio.',
        'price.numeric' => 'El precio debe ser un número.',
        'price_per_kg.numeric' => 'El precio por kilogramo debe ser un número.',
        'stock.required' => 'El stock es obligatorio.',
        'is_weighted.boolean' => 'El campo debe ser verdadero o falso.',
    ];

    public function validateFields()
    {
        try {
            // Intenta validar los campos
            $this->validate();

            // Si todo está bien, emite un evento indicando éxito
           
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores, los envía como evento
            dd('validationFailed', $e->validator->errors()->all());
        }
    }

    public function Store()
{
    // $rules = [
    //     'name' => 'required|min:3',
    //     'barcode' => 'required|unique:products',
    //     'categoryid' => 'required|not_in:Elegir',
    //     'size' => 'required',
    //     'color' => 'required|min:3',
    //     'cost' => 'required|numeric|regex:/^\d{4,}$/',
    //     'price' => 'required|numeric|regex:/^\d{4,}$/',
    //     'stock' => 'required|numeric|min:0',
    //     'min_stock' => 'required|numeric|min:0'
    // ];

    // $message = [
    //     'name.required' => 'El nombre es requerido',
    //     'name.min' => 'El nombre debe tener al menos 3 caracteres',
    //     'barcode.required' => 'El codigo de barras es requerido',
    //     'barcode.unique' => 'El codigo de barras ya existe',
    //     'categoryid.required' => 'La categoria es requerida',
    //     'categoryid.not_in' => 'Seleccione una categoria',
    //     'size.required' => 'El tamaño es requerido',
    //     'color.required' => 'El color es requerido',
    //     'color.min' => 'El color debe tener al menos 3 caracteres',
    //     'cost.required' => 'El costo es requerido',
    //     'cost.numeric' => 'El costo debe ser numerico',
    //     'cost.regex' => 'El costo debe tener al menos 4 dígitos. Por favor, ingrese un costo válido sin puntos ni comas',
    //     'price.required' => 'El precio es requerido',
    //     'price.numeric' => 'El precio debe ser numerico',
    //     'price.regex' => 'El precio debe tener al menos 4 dígitos. Por favor, ingrese un precio válido sin puntos ni comas',
    //     'stock.required' => 'El stock es requerido',
    //     'stock.numeric' => 'El stock debe ser numerico',
    //     'stock.min' => 'El stock debe ser mayor a 0',
    //     'min_stock.required' => 'El stock minimo es requerido',
    //     'min_stock.numeric' => 'El stock minimo debe ser numerico',
    //     'min_stock.min' => 'El stock minimo debe ser mayor a 0'
    // ];

   // try {
        
    
    $rules = [
        'name' => 'required|min:3',
        'barcode' => 'required|unique:products',
        'categoryid' => 'required|not_in:Elegir',
        //'cost' => 'required|numeric|min:0',
        'price' => 'required_if:is_weighted,false|nullable|numeric|min:0', // Requerido para productos por unidad
        'price_per_kg' => 'required_if:is_weighted,true|nullable|numeric|min:0', // Requerido para productos por peso
        'stock' => 'required|numeric|min:0',
        'min_stock' => 'required|numeric|min:0',
        'is_weighted' => 'boolean',
    ];

    $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'barcode.required' => 'El código de barras es obligatorio.',
        'barcode.unique' => 'El código de barras ya existe.',
        'categoryid.not_in' => 'Seleccione una categoría.',
        'cost.required' => 'El costo es obligatorio.',
        'price.numeric' => 'El precio debe ser un número.',
        'price_per_kg.numeric' => 'El precio por kilogramo debe ser un número.',
        'stock.required' => 'El stock es obligatorio.',
        'is_weighted.boolean' => 'El campo debe ser verdadero o falso.',
    ];

   //$this->validate($rules, $messages);
   $this->validateFields();

     // Redondear los precios a 2 decimales
     $roundedPrice = $this->price ? round($this->price, 2) : null;
     $roundedPricePerKg = $this->price_per_kg ? round($this->price_per_kg, 2) : null;

    // $product = Product::create([
    //     'name' => $this->name,
    //     'barcode' => $this->barcode,
    //     'brand' => $this->brand,
    //     'model' => $this->model,
    //     'size' => $this->size,
    //     'color' => $this->color,
    //     'description' => $this->description,
    //     'cost' => $this->cost,
    //     'price' => $this->price,
    //     'stock' => $this->stock,
    //     'min_stock' => $this->min_stock,
    //     'category_id' => $this->categoryid,
    //     //'image' => $this->image->store('products','public')
    // ]);

     $product = Product::create([
        'name' => $this->name,
        'barcode' => $this->barcode,
        'brand' => $this->brand,
        'model' => $this->model,
        'size' => $this->size,
        'color' => $this->color,
        'description' => $this->description,
        'cost' => floatval($this->cost),
        'price' => floatval($roundedPrice),
        'price_per_kg' => floatval($roundedPricePerKg),
        'stock' => floatval($this->stock),
        'min_stock' =>floatval($this->min_stock),
        'is_weighted' => $this->is_weighted ?? 0,
        'category_id' => $this->categoryid,
    ]);

    //dd($product);

    //LLAMAR A LA FUNCION E MANEJO DE IMAGENES
    $this->handleImageUpload($product);
    $this->resetUI();
    $this->emit('product-added', 'Producto Registrado');

}

    public function Edit(Product $product)
    {
        // $this->selected_id = $product->id;
        // $this->name = $product->name;
        // $this->barcode = $product->barcode;
        // $this->brand = $product->brand;
        // $this->model = $product->model;
        // $this->size = $product->size;
        // $this->color = $product->color;
        // $this->description = $product->description;
        // $this->cost = $product->cost;
        // $this->price = $product->price;
        // $this->stock = $product->stock;
        // $this->min_stock = $product->min_stock;
        // $this->categoryid = $product->category_id;
        // $this->image = null;


        // $this->emit('modal-show','show modal!');

        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->brand = $product->brand;
        $this->model = $product->model;
        $this->size = $product->size;
        $this->color = $product->color;
        $this->description = $product->description;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->price_per_kg = $product->price_per_kg;
        $this->stock = $product->stock;
        $this->min_stock = $product->min_stock;
        $this->is_weighted = $product->is_weighted;
        $this->categoryid = $product->category_id;
        $this->image = null;

        $this->emit('modal-show', 'show modal!');

    }

        public function Update()
    {
        // $rules = [
        //     'name' => 'required|min:3',
        //     'barcode' => 'required',
        //     'categoryid' => 'required|not_in:Elegir',
        //     'size' => 'required',
        //     'color' => 'required|min:3',
        //     'cost' => 'required|numeric|regex:/^\d{4,}$/',
        //     'price' => 'required|numeric|regex:/^\d{4,}$/',
        //     'stock' => 'required|numeric|min:0',
        //     'min_stock' => 'required|numeric|min:0'
        // ];

        // $message = [
        //     'name.required' => 'El nombre es requerido',
        //     'name.min' => 'El nombre debe tener al menos 3 caracteres',
        //     'barcode.required' => 'El codigo de barras es requerido',
        //     'categoryid.required' => 'La categoria es requerida',
        //     'categoryid.not_in' => 'Seleccione una categoria',
        //     'size.required' => 'El tamaño es requerido',
        //     'color.required' => 'El color es requerido',
        //     'color.min' => 'El color debe tener al menos 3 caracteres',
        //     'cost.required' => 'El costo es requerido',
        //     'cost.numeric' => 'El costo debe ser numerico',
        //     'cost.regex' => 'El costo debe tener al menos 4 dígitos. Por favor, ingrese un costo válido sin puntos ni comas',
        //     'price.required' => 'El precio es requerido',
        //     'price.numeric' => 'El precio debe ser numerico',
        //     'price.regex' => 'El precio debe tener al menos 4 dígitos. Por favor, ingrese un precio válido sin puntos ni comas',
        //     'stock.required' => 'El stock es requerido',
        //     'stock.numeric' => 'El stock debe ser numerico',
        //     'stock.min' => 'El stock debe ser mayor a 0',
        //     'min_stock.required' => 'El stock minimo es requerido',
        //     'min_stock.numeric' => 'El stock minimo debe ser numerico',
        //     'min_stock.min' => 'El stock minimo debe ser mayor a 0'
        // ];

        $rules = [
            'name' => 'required|min:3',
            'barcode' => 'required|unique:products,barcode,' . $this->selected_id, // Permitir el mismo código de barras para el producto actual
            'categoryid' => 'required|not_in:Elegir',
            //'cost' => 'required|numeric|min:0',
            'price' => 'nullable|numeric|min:0', // Opcional para productos por peso
            'price_per_kg' => 'nullable|numeric|min:0', // Opcional para productos por unidad
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'is_weighted' => 'boolean',
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'barcode.required' => 'El código de barras es obligatorio.',
            'barcode.unique' => 'El código de barras ya existe.',
            'categoryid.not_in' => 'Seleccione una categoría.',
            'cost.required' => 'El costo es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price_per_kg.numeric' => 'El precio por kilogramo debe ser un número.',
            'stock.required' => 'El stock es obligatorio.',
            'is_weighted.boolean' => 'El campo debe ser verdadero o falso.',
        ];

        $this->validate($rules, $messages);


    $product = Product::find($this->selected_id);

    // $product->update([
    //     'name' => $this->name,
    //     'barcode' => $this->barcode,
    //     'brand' => $this->brand,
    //     'model' => $this->model,
    //     'size' => $this->size,
    //     'color' => $this->color,
    //     'description' => $this->description,
    //     'cost' => $this->cost,
    //     'price' => $this->price,
    //     'stock' => $this->stock,
    //     'min_stock' => $this->min_stock,
    //     'category_id' => $this->categoryid,
    //     //'image' => $this->image->store('products','public')
    // ]);

    $product->update([
        'name' => $this->name,
        'barcode' => $this->barcode,
        'brand' => $this->brand,
        'model' => $this->model,
        'size' => $this->size,
        'color' => $this->color,
        'description' => $this->description,
        'cost' => $this->cost,
        'price' => $this->price,
        'price_per_kg' => $this->price_per_kg,
        'stock' => $this->stock,
        'min_stock' => $this->min_stock,
        'is_weighted' => $this->is_weighted,
        'category_id' => $this->categoryid,
    ]);

    //LLAMAR A LA FUNCION E MANEJO DE IMAGENES
    $this->handleImageUpload($product);
    $this->resetUI();
    $this->emit('product-updated', 'Producto Actualizado');
}

    public function resetUI()
    {
        $this->name = '';
        $this->barcode = '';
        $this->brand = '';
        $this->model = '';
        $this->size = '';
        $this->color = '';
        $this->description = '';
        $this->cost = '';
        $this->price = '';
        $this->price_per_kg = ''; // Nuevo campo
        $this->stock = '';
        $this->min_stock = '';
        $this->categoryid = 'Elegir';
        $this->image = null;
        $this->is_weighted = false; // Nuevo campo
        $this->selected_id = 0;
        $this->resetValidation();
    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy($id)
    {
        $product = Product::find($id);
        $imageTemp = $product->image; //imagen temporal
        $product->delete();

        if($imageTemp != null)
        {
            if(file_exists('storage/products/'.$imageTemp))
            {
                unlink('storage/products/'.$imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('product-deleted','Producto Eliminado');
    }


    //FUNCION MAEJO DE CARGA DE IMAGENES
    private function handleImageUpload($product)
    {
        if ($this->image) {
            $customFileName = uniqid() . '.jpg'; // Forzar la extensión a .jpg
            $imagePath = storage_path('app/public/products/' . $customFileName);

            // Comprimir y guardar la imagen en formato JPEG
            Image::make($this->image)
                ->orientate() // Orientar la imagen correctamente
                ->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('jpg', 60) // Forzar el formato JPEG y establecer la calidad en 60
                ->save($imagePath);

            $imageTemp = $product->image; // Imagen temporal
            $product->image = $customFileName;
            $product->save();

            if ($imageTemp != null) {
                if (file_exists(storage_path('app/public/products/' . $imageTemp))) {
                    unlink(storage_path('app/public/products/' . $imageTemp));
                }
            }
        }
    }



    
}
