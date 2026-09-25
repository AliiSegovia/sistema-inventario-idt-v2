<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-2">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o SKU..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        
                        <select wire:model.live="selectedCategory" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                        + Nuevo Producto
                    </button>
                </div>

                <table class="table-auto w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">SKU</th>
                            <th class="px-4 py-3">Categoría</th>
                            <th class="px-4 py-3">Stock</th>
                            <th class="px-4 py-3">Precio</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @forelse($products as $product)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $product->sku }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? 'Sin categoría' }}</td>
                                <td class="px-4 py-3 text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock > 5 ? 'bg-green-100 text-green-850' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button wire:click="openMovementModal({{ $product->id }})" class="text-green-600 hover:text-green-900 font-semibold">Movimiento</button>
                                    <button wire:click="edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</button>
                                    <button wire:click="delete({{ $product->id }})" onclick="confirm('¿Estás seguro de eliminar este producto?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">No se encontraron productos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PARA CREAR / EDITAR PRODUCTO --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $editMode ? 'Editar Producto' : 'Crear Producto' }}
                </h3>

                <form wire:submit.prevent="store">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" wire:model="sku" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select wire:model="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea wire:model="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" wire:model="stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Precio</label>
                        <input type="number" step="0.01" wire:model="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm">Cancelar</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL PARA MOVIMIENTOS RÁPIDOS --}}
    @if($isMovementOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Registrar Movimiento</h3>
                <p class="text-sm text-gray-500 mb-4">Producto: <b>{{ optional($selectedProductForMovement)->name }}</b></p>

                <form wire:submit.prevent="storeMovement">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tipo de Movimiento</label>
                        <select wire:model="movementType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="in">Entrada</option>
                            <option value="out">Salida</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" wire:model="movementQuantity" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('movementQuantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="closeMovementModal" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm">Cancelar</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>