<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Marcadores
            </h2>

            @can('create', \App\Models\Marker::class)
                <a
                    href="{{ route('markers.create') }}"
                    class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700"
                >
                    Nuevo marcador
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('markers.index') }}" method="GET" class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5 items-end">
                        <div>
                            <x-input-label for="type" value="Tipo" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los tipos</option>
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="status" value="Estado" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los estados</option>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="sort_by" value="Ordenar por" />
                            <select id="sort_by" name="sort_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="latest" @selected(request('sort_by') === 'latest')>Más recientes</option>
                                <option value="oldest" @selected(request('sort_by') === 'oldest')>Más antiguos</option>
                                <option value="title_asc" @selected(request('sort_by') === 'title_asc')>Título A-Z</option>
                                <option value="title_desc" @selected(request('sort_by') === 'title_desc')>Título Z-A</option>
                                <option value="owner_asc" @selected(request('sort_by') === 'owner_asc')>Propietario A-Z</option>
                                <option value="owner_desc" @selected(request('sort_by') === 'owner_desc')>Propietario Z-A</option>
                            </select>
                        </div>

                        <div class="flex items-center h-10">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="my_markers" value="1" @checked(request('my_markers')) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Solo mis marcadores</span>
                            </label>
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button type="submit">
                                Filtrar
                            </x-primary-button>
                            
                            <a href="{{ route('markers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    @if ($markers->isEmpty())
                        <p class="text-sm text-gray-600">
                            No se encontraron marcadores con los criterios seleccionados.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Titulo</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tipo</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Estado</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Propietario</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Coordenadas</th>
                                        <th class="px-4 py-3 text-right font-medium text-gray-600">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($markers as $marker)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $marker->title }}</div>
                                                @if ($marker->address)
                                                    <div class="text-xs text-gray-500">{{ $marker->address }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">{{ $types[$marker->type] ?? $marker->type }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $statuses[$marker->status] ?? $marker->status }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $marker->user?->name ?? 'Sin usuario' }}</td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $marker->latitude }}, {{ $marker->longitude }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex justify-end gap-3">
                                                    @can('update', $marker)
                                                        <a href="{{ route('markers.edit', $marker) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                            Editar
                                                        </a>
                                                    @endcan

                                                    @can('delete', $marker)
                                                        @if ($marker->status !== \App\Models\Marker::STATUS_REMOVED)
                                                            <form method="POST" action="{{ route('markers.destroy', $marker) }}">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    type="submit"
                                                                    class="text-sm font-medium text-red-600 hover:text-red-500"
                                                                    onclick="return confirm('Se marcará este registro como removido. ¿Continuar?')"
                                                                >
                                                                    Remover
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @cannot('update', $marker)
                                                        <span class="text-sm text-gray-400">
                                                            Solo lectura
                                                        </span>
                                                    @endcannot
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $markers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
