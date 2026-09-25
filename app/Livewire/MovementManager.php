<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Movement;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class MovementManager extends Component
{
    use WithPagination;

    public $product_id, $type = 'in', $quantity = 1;
    public $isOpen = false;
    public $search = '';

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'type' => 'required|in:in,out',
        'quantity' => 'required|integer|min:1',
    ];

    public function create()
    {
        $this->reset(['product_id', 'type', 'quantity']);
        $this->type = 'in';
        $this->quantity = 1;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    // <-- AQUÍ CAMBIAMOS 'store' POR 'saveMovement' PARA QUE COINCIDA CON TU VISTA
    public function saveMovement()
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);

        if ($this->type === 'out' && $product->stock < $this->quantity) {
            session()->flash('error', 'Stock insuficiente para realizar la salida.');
            return;
        }

        Movement::create([
            'product_id' => $this->product_id,
            'user_id' => Auth::id(),
            'type' => $this->type,
            'quantity' => $this->quantity,
        ]);

        if ($this->type === 'in') {
            $product->stock += $this->quantity;
        } else {
            $product->stock -= $this->quantity;
        }
        $product->save();

        $this->reset(['product_id', 'type', 'quantity']);
        $this->isOpen = false;
        
        session()->flash('message', 'Movimiento registrado correctamente.');
    }

    public function render()
    {
        $movements = Movement::with(['product', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.movement-manager', [
            'movements' => $movements,
            'products' => Product::all(),
        ]);
    }
}