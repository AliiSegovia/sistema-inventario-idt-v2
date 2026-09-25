<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Nuevo producto</h2>
            
            <form wire:submit.prevent="saveProduct" class="max-w-xl space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" wire:model="sku" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categoría</label>
                    <input type="text" wire:model="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea wire:model="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>