<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Operator;

#[Layout('layouts.app')]
class OperatorManager extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $operatorId;
    public $isOpen = false;

    public function create()
    {
        $this->reset(['name', 'email', 'operatorId']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:operators,email,' . $this->operatorId,
        ]);

        Operator::updateOrCreate(['id' => $this->operatorId], [
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->reset(['name', 'email', 'operatorId']);
        $this->isOpen = false;
        
        session()->flash('message', $this->operatorId ? 'Operario actualizado correctamente.' : 'Operario registrado correctamente.');
    }

    public function edit($id)
    {
        $operator = Operator::findOrFail($id);
        
        $this->operatorId = $operator->id;
        $this->name = $operator->name;
        $this->email = $operator->email;
        
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Operator::findOrFail($id)->delete();
        session()->flash('message', 'Operario eliminado correctamente.');
    }

    public function render()
    {
        return view('livewire.operator-manager', [
            'operators' => Operator::latest()->paginate(5)
        ]);
    }
}