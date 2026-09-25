<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Registrar movimiento</h3>
            
            <form wire:submit.prevent="saveMovement">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <select wire:model="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Seleccione un producto</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} — stock: {{ $prod->stock }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select wire:model="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" wire:model="quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">Registrar movimiento</button>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Historial de movimientos</h3>
            @if($movements->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($movements as $m)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $m->product->name ?? 'Eliminado' }}</td>
                                <td class="px-4 py-2 text-sm uppercase font-semibold {{ $m->type == 'entrada' ? 'text-green-600' : 'text-red-600' }}">{{ $m->type }}</td>
                                <td class="px-4 py-2 text-sm">{{ $m->quantity }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-gray-400 border border-dashed rounded-md">Sin movimientos</div>
            @endif
        </div>
    </div>
</div>