<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Movement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ProductManager extends Component
{
    use WithPagination;

    public $name, $sku, $category_id, $stock, $price, $product_id;
    public $isOpen = false;

    // Propiedades para Búsqueda y Filtro requeridas por la rúbrica
    public $search = '';
    public $selectedCategory = '';

    // Propiedades para Movimientos
    public $movementProductId, $movementType = 'in', $movementQuantity;
    public $isMovementOpen = false;

    // Propiedad para el acordeón de historial por producto
    public $expandedProductId = null;

    // Resetear paginación al buscar o filtrar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with('category');

        // Búsqueda por nombre o SKU
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por categoría
        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.product-manager', [
            'products' => $query->paginate(10),
            'categories' => Category::all()
        ])->layout('layouts.app');
    }

    public function toggleRow($productId)
    {
        if ($this->expandedProductId === $productId) {
            $this->expandedProductId = null;
        } else {
            $this->expandedProductId = $productId;
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->sku = '';
        $this->category_id = '';
        $this->stock = '';
        $this->price = '';
        $this->product_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'sku' => 'required|unique:products,sku,' . $this->product_id,
            'category_id' => 'required',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'stock' => $this->stock,
            'price' => $this->price,
        ]);

        session()->flash('message', $this->product_id ? '¡Producto actualizado con éxito!' : '¡Producto creado con éxito!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->stock = $product->stock;
        $this->price = $product->price;

        $this->openModal();
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('message', '¡Producto eliminado con éxito!');
    }

    public function openMovementModal($productId)
    {
        $this->movementProductId = $productId;
        $this->movementType = 'in';
        $this->movementQuantity = '';
        $this->isMovementOpen = true;
    }

    public function closeMovementModal()
    {
        $this->isMovementOpen = false;
    }

    public function storeMovement()
    {
        $this->validate([
            'movementProductId' => 'required|exists:products,id',
            'movementType' => 'required|in:in,out',
            'movementQuantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($this->movementProductId);

        if ($this->movementType === 'out' && $product->stock < $this->movementQuantity) {
            session()->flash('error', 'No hay suficiente stock disponible para esta salida.');
            return;
        }

        if ($this->movementType === 'in') {
            $product->stock += $this->movementQuantity;
        } else {
            $product->stock -= $this->movementQuantity;
        }
        $product->save();

        Movement::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $this->movementType,
            'quantity' => $this->movementQuantity,
        ]);

        session()->flash('message', '¡Movimiento registrado y stock actualizado con éxito!');
        $this->closeMovementModal();
    }
}