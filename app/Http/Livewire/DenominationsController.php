<?php

namespace App\Http\Livewire;


use App\Models\Denomination;
use iluminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class DenominationsController extends Component
{
    use WithFileUploads;
    use WithPagination;


    public $type, $value, $selected_id, $image, $search, $componentName, $pageTitle;
    private $pagination = 5;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Denominaciones';
        $this->type = 'Elegir';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {
        if(strlen($this->search) > 0)
        {
            $data = Denomination::where('type', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        }
        else
        {
            $data = Denomination::orderBy('id', 'desc')->paginate($this->pagination);
        }
        
        return view('livewire.denominations.component', ['data' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit($id)
    {
        $record = Denomination::find($id, ['id', 'type', 'value', 'image']);
        $this->type = $record->type;
        $this->value = $record->value;
        $this->selected_id = $record->id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $rules = [
            'type' => 'required|not_in:Elegir',
            'value' => 'required|unique:denominations'
        ];

        $messages = [
            'type.required' => 'El tipo de la denominacion es requerido',
            'type.not_in' => 'El tipo de la denominacion es requerido',
            'value.required' => 'El valor de la denominacion es requerido',
            'value.unique' => 'Ya existe una denominacion con ese valor'
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::create([
            'type' => $this->type,
            'value' => $this->value
        ]);

        $customFileName;
        if($this->image)
        {   //para crear un nombre unico para la imagen
            $customFileName = uniqid() . '_.' . $this->image->extension();
            //para guardar la imagen en el storage
            $this->image->storeAs('public/denominations', $customFileName);
            //para actualizar el campo image de la tabla denominations
            $denomination->image = $customFileName;
            //para guardar el registro
            $denomination->save();
        }

        $this->resetUI();
        $this->emit('item-added', 'Categoria Registrada');
    }

    public function Update()
    {
        $rules = [
            'type' => 'required|not_in:Elegir',
            'value' => "required|unique:denominations,value,{$this->selected_id}"
        ];

        $messages = [
            'type.required' => 'El tipo de la denominacion es requerido',
            'type.not_in' => 'El tipo de la denominacion es requerido',
            'value.required' => 'El valor de la denominacion es requerido',
            'value.unique' => 'Ya existe una denominacion con ese valor'
        ];

        $this->validate($rules, $messages);

        //para actualizar el registro
        $denomination = Denomination::find($this->selected_id);
        //para actualizar los campos
        $denomination->update([
            'type' => $this->type,
            'value' => $this->value
        ]);

        //validar si el usuario subio una imagen
        if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/denominations', $customFileName);
			$imageName = $denomination->image;

			$denomination->image = $customFileName;
			$denomination->save();

			if($imageName !=null)
			{
				if(file_exists('storage/denominations' . $imageName))
				{
					unlink('storage/denominations' . $imageName);
				}
			} 

		}

		$this->resetUI();
		$this->emit('item-updated', 'Denominacion Actualizada');
    }

    public function resetUI()
    {
        $this->type = '';
        $this->value = '';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
    } 

    //para escuchar el evento deleteRow
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function Destroy(Denomination $denomination)
	{   	

		$imageName = $denomination->image; 
		$denomination->delete();

		if($imageName !=null) {
			unlink('storage/denominations/' . $imageName);
		}

		$this->resetUI();
		$this->emit('item-deleted', 'Categoría Eliminada');

	}
        
}

