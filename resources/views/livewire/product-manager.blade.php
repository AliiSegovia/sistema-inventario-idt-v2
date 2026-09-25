<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Gestión de Productos - Grupo IDT
                    </h2>
                    <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md w-full md:w-auto text-center">
                        + Nuevo Producto
                    </button>
                </div>

                <!-- BARRA DE BÚSQUEDA Y FILTRO POR CATEGORÍA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <input type="text" wire:model.live="search" placeholder="Buscar por nombre o SKU..." class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                    </div>
                    <div>
                        <select wire:model.live="selectedCategory" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="bg-emerald-500 text-white p-3 rounded-lg mb-4 shadow">
                        {{ session('message') }}
                    </div>
                @endif

                @if($isOpen)
                    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg w-1/2 shadow-2xl border border-gray-700">
                            <h3 class="text-lg font-bold mb-4 text-indigo-400">{{ $product_id ? 'Editar Producto' : 'Crear Producto' }}</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Nombre</label>
                                <input type="text" wire:model="name" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">SKU</label>
                                <input type="text" wire:model="sku" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Categoría</label>
                                <select wire:model="category_id" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                    <option value="">Seleccione...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Stock</label>
                                <input type="number" wire:model="stock" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Precio</label>
                                <input type="number" step="0.01" wire:model="price" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end gap-2 mt-6">
                                <button wire:click="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Cancelar</button>
                                <button wire:click="store()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Guardar</button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- MODAL DE MOVIMIENTOS -->
                @if($isMovementOpen)
                    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg w-1/2 shadow-2xl border border-gray-700">
                            <h3 class="text-lg font-bold mb-4 text-cyan-400">Registrar Entrada / Salida de Stock</h3>
                            
                            @if (session()->has('error'))
                                <div class="bg-red-500 text-white p-2 rounded mb-3 text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Tipo de Movimiento</label>
                                <select wire:model="movementType" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                    <option value="in">Entrada (Stock +)</option>
                                    <option value="out">Salida (Stock -)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Cantidad</label>
                                <input type="number" wire:model="movementQuantity" class="w-full border rounded-lg p-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-700">
                                @error('movementQuantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end gap-2 mt-6">
                                <button wire:click="closeMovementModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Cancelar</button>
                                <button wire:click="storeMovement()" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg transition">Registrar</button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="w-10 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($products as $product)
                                <!-- FILA PRINCIPAL -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <button wire:click="toggleRow({{ $product->id }})" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none transition-transform duration-200">
                                            <svg class="w-5 h-5 transform {{ $expandedProductId === $product->id ? 'rotate-90 text-indigo-600 dark:text-indigo-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $product->category->name ?? 'Sin categoría' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">{{ $product->stock }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="openMovementModal({{ $product->id }})" class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 mr-2">Movimiento</button>
                                        <button wire:click="edit({{ $product->id }})" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 mr-2">Editar</button>
                                        <button wire:click="delete({{ $product->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400">Eliminar</button>
                                    </td>
                                </tr>

                                <!-- FILA DESPLEGABLE CON EL HISTORIAL DEL PRODUCTO -->
                                @if($expandedProductId === $product->id)
                                    <tr>
                                        <td colspan="7" class="bg-gray-50 dark:bg-gray-900/60 p-6">
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800 shadow-inner">
                                                <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mb-3 uppercase tracking-wider">
                                                    Historial de Movimientos para: {{ $product->name }}
                                                </h4>

                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                                        <thead>
                                                            <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                                                                <th class="py-2 text-left">Tipo</th>
                                                                <th class="py-2 text-left">Cantidad</th>
                                                                <th class="py-2 text-left">Usuario</th>
                                                                <th class="py-2 text-left">Fecha y Hora</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                            @forelse($product->movements()->with('user')->latest()->get() as $mov)
                                                                <tr>
                                                                    <td class="py-2 whitespace-nowrap">
                                                                        @if($mov->type === 'in')
                                                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Entrada (+)</span>
                                                                        @else
                                                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Salida (-)</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="py-2 whitespace-nowrap font-bold">{{ $mov->quantity }}</td>
                                                                    <td class="py-2 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $mov->user->name ?? 'Sistema' }}</td>
                                                                    <td class="py-2 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400 italic">
                                                                        No hay movimientos registrados para este producto todavía.
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No se encontraron productos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>

            </div>

        </div>
    </div>
</div>