<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Operarios</h2>
            <button wire:click="create" class="px-4 py-2 bg-gray-900 text-white rounded-md shadow-sm text-sm font-medium hover:bg-gray-800">Nuevo operario</button>
        </div>

        @if($isOpen)
            <div class="bg-white p-6 shadow-xl sm:rounded-lg max-w-xl">
                <h3 class="font-bold text-lg mb-4">{{ isset($operatorId) && $operatorId ? 'Editar Operario' : 'Registrar Operario' }}</h3>
                <form wire:submit.prevent="store" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" wire:model="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contraseña {{ isset($operatorId) && $operatorId ? '(Dejar en blanco para mantener la actual)' : '' }}</label>
                        <input type="password" wire:model="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Guardar</button>
                        <button type="button" wire:click="$set('isOpen', false)" class="px-4 py-2 bg-gray-300 rounded-md">Cancelar</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NOMBRE</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CORREO ELECTRÓNICO</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($operators as $op)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $op->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $op->email }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <button wire:click="edit({{ $op->id }})" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No hay operarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $operators->links() }}</div>
        </div>
    </div>
</div>