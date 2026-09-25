<div class="flex justify-between h-16">
    <div class="flex">
        <!-- Tus enlaces de navegación actuales -->
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>
            
            <x-nav-link href="{{ route('productos') }}" :active="request()->routeIs('productos')">
                Productos
            </x-nav-link>

            <x-nav-link href="{{ route('operarios') }}" :active="request()->routeIs('operarios')">
                Operarios
            </x-nav-link>

            <x-nav-link href="{{ route('movimientos') }}" :active="request()->routeIs('movimientos')">
                Movimientos
            </x-nav-link>
        </div>
    </div>

    <!-- Botón de Cerrar Sesión ubicado a la derecha -->
    <div class="hidden sm:flex sm:items-center sm:ms-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-red-600 focus:outline-none transition ease-in-out duration-150">
                Cerrar sesión
            </button>
        </form>
    </div>
</div>