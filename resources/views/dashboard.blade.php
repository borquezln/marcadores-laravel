<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">
                        Hola, {{ $user->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        Rol: <span class="font-medium">{{ $user->role }}</span>
                    </p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <a href="{{ route('map.index') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100 hover:border-indigo-300">
                    <h3 class="font-medium text-gray-900">Mapa</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Acceso disponible para todos los usuarios activos.
                    </p>
                </a>

                @can('viewAny', \App\Models\Marker::class)
                    <a href="{{ route('markers.index') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100 hover:border-indigo-300">
                        <h3 class="font-medium text-gray-900">Marcadores</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Alta, edición y gestión básica de marcadores.
                        </p>
                    </a>
                @endcan

                @if ($user->canManageUsers())
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                        <h3 class="font-medium text-gray-900">Usuarios</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Administración pendiente de implementación.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
