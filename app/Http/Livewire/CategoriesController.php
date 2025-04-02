<?php

namespace App\Http\Livewire;


use App\Models\Category;
use iluminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class CategoriesController extends Component
{
    use WithFileUploads;
    use WithPagination;


    public $name, $selected_id, $image, $search, $componentName, $pageTitle;
    private $pagination = 5;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categorias';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {
        if(strlen($this->search) > 0)
        {
            $data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        }
        else
        {
            $data = Category::orderBy('id', 'desc')->paginate($this->pagination);
        }
        
        return view('livewire.category.categories', ['categories' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit($id)
    {
        $record = Category::find($id, ['id', 'name', 'image']);
        $this->name = $record->name;
        $this->selected_id = $record->id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $rules = [
            'name' => 'required|unique:categories|min:3'
        ];

        $messages = [
            'name.required' => 'Nombre de la categoria es requerido',
            'name.unique' => 'Ya existe una categoria con ese nombre',
            'name.min' => 'El nombre de la categoria debe tener al menos 3 caracteres'
        ];

        $this->validate($rules, $messages);

        $category = Category::create([
            'name' => $this->name
        ]);

        $customFileName;
        if($this->image)
        {   //para crear un nombre unico para la imagen
            $customFileName = uniqid() . '_.' . $this->image->extension();
            //para guardar la imagen en el storage
            $this->image->storeAs('public/categories', $customFileName);
            //para actualizar el campo image de la tabla categories
            $category->image = $customFileName;
            //para guardar el registro
            $category->save();
        }

        $this->resetUI();
        $this->emit('category-added', 'Categoria Registrada');
    }

    public function Update()
    {
        $rules = [
            'name' => "required|min:3|unique:categories,name,{$this->selected_id}"
        ];

        $messages = [
            'name.required' => 'Nombre de la categoria es requerido',
            'name.unique' => 'Ya existe una categoria con ese nombre',
            'name.min' => 'El nombre de la categoria debe tener al menos 3 caracteres'
        ];

        $this->validate($rules, $messages);

        //para actualizar el registro
        $category = Category::find($this->selected_id);
        //para actualizar el campo name
        $category->update([
            'name' => $this->name
        ]);

        //validar si el usuario subio una imagen
        if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/categories', $customFileName);
			$imageName = $category->image;

			$category->image = $customFileName;
			$category->save();

			if($imageName !=null)
			{
				if(file_exists('storage/categories' . $imageName))
				{
					unlink('storage/categories' . $imageName);
				}
			} 

		}

		$this->resetUI();
		$this->emit('category-updated', 'Categoría Actualizada');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
    } 

    //para escuchar el evento deleteRow
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy(Category $category)
	{   	

		$imageName = $category->image; 
		$category->delete();

		if($imageName !=null) {
			unlink('storage/categories/' . $imageName);
		}

		$this->resetUI();
		$this->emit('category-deleted', 'Categoría Eliminada');

	}
        
}

