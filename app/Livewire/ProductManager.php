<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;
use App\Models\Movement;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class ProductManager extends Component
{
    use WithPagination;

    public $name, $sku, $category_id, $description, $stock = 0, $price = 0;
    public $search = '';
    public $selectedCategory = '';
    
    public $isOpen = false;
    public $isMovementOpen = false;
    public $editMode = false;
    public $productId;

    // Variables para movimientos
    public $movementType = 'in';
    public $movementQuantity = 1;
    public $selectedProductForMovement;

    // Fila desplegable para historial
    public $expandedProductId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'sku', 'category_id', 'description', 'stock', 'price', 'productId', 'editMode']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function closeMovementModal()
    {
        $this->isMovementOpen = false;
    }

    public function openMovementModal($id)
    {
        $this->selectedProductForMovement = Product::findOrFail($id);
        $this->movementType = 'in';
        $this->movementQuantity = 1;
        $this->isMovementOpen = true;
    }

    public function toggleRow($id)
    {
        $this->expandedProductId = $this->expandedProductId === $id ? null : $id;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $this->productId,
            'category_id' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Product::updateOrCreate(['id' => $this->productId], [
            'name' => $this->name,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'stock' => $this->stock,
            'price' => $this->price,
        ]);

        $this->reset(['name', 'sku', 'category_id', 'description', 'stock', 'price', 'productId', 'editMode']);
        $this->isOpen = false;
        
        session()->flash('message', $this->productId ? 'Producto actualizado correctamente.' : 'Producto guardado correctamente.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
        $this->stock = $product->stock;
        $this->price = $product->price ?? 0;
        
        $this->editMode = true;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Producto eliminado correctamente.');
    }

    public function storeMovement()
    {
        $this->validate([
            'movementQuantity' => 'required|integer|min:1',
        ]);

        $product = $this->selectedProductForMovement;

        if ($this->movementType === 'out' && $product->stock < $this->movementQuantity) {
            session()->flash('error', 'Stock insuficiente para realizar la salida.');
            return;
        }

        // Actualizar stock
        if ($this->movementType === 'in') {
            $product->stock += $this->movementQuantity;
        } else {
            $product->stock -= $this->movementQuantity;
        }
        $product->save();

        // Registrar movimiento
        Movement::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $this->movementType,
            'quantity' => $this->movementQuantity,
        ]);

        $this->isMovementOpen = false;
        session()->flash('message', 'Movimiento registrado con éxito.');
    }

    public function render()
    {
        $categories = Category::all();

        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->latest()
            ->paginate(5);

        return view('livewire.product-manager', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}