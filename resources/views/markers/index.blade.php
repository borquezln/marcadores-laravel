@php
    $typeBadgeClasses = [
        \App\Models\Marker::TYPE_PLACE => 'border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
        \App\Models\Marker::TYPE_BILLBOARD => 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300',
        \App\Models\Marker::TYPE_STAND => 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
    ];

    $typeDotClasses = [
        \App\Models\Marker::TYPE_PLACE => 'bg-blue-500',
        \App\Models\Marker::TYPE_BILLBOARD => 'bg-red-500',
        \App\Models\Marker::TYPE_STAND => 'bg-emerald-500',
    ];

    $statusBadgeClasses = [
        \App\Models\Marker::STATUS_ACTIVE => 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
        \App\Models\Marker::STATUS_INACTIVE => 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300',
        \App\Models\Marker::STATUS_REMOVED => 'border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-[#323232] text-gray-600 dark:text-gray-400',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Marcadores
            </h2>

            @can('create', \App\Models\Marker::class)
                <a
                    href="{{ route('markers.create') }}"
                    class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Nuevo marcador
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-800 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-[#323232] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('markers.index') }}" method="GET" class="mb-6 rounded-lg border border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-[#323232] p-4">
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <x-input-label for="type" value="Tipo" />
                                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300">
                                    <option value="">Todos los tipos</option>
                                    @foreach ($types as $value => $label)
                                        <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="status" value="Estado" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300">
                                    <option value="">Todos los estados</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="sort_by" value="Ordenar por" />
                                <select id="sort_by" name="sort_by" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300">
                                    <option value="latest" @selected(request('sort_by') === 'latest')>Más recientes</option>
                                    <option value="oldest" @selected(request('sort_by') === 'oldest')>Más antiguos</option>
                                    <option value="title_asc" @selected(request('sort_by') === 'title_asc')>Título A-Z</option>
                                    <option value="title_desc" @selected(request('sort_by') === 'title_desc')>Título Z-A</option>
                                    <option value="owner_asc" @selected(request('sort_by') === 'owner_asc')>Propietario A-Z</option>
                                    <option value="owner_desc" @selected(request('sort_by') === 'owner_desc')>Propietario Z-A</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <label class="flex min-h-10 w-full cursor-pointer items-center rounded-md border border-gray-200 dark:border-neutral-600 bg-white dark:bg-[#323232] px-3 py-2 text-sm text-gray-700 dark:text-gray-300 shadow-sm">
                                    <input type="checkbox" name="my_markers" value="1" @checked(request('my_markers')) class="rounded border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Solo mis marcadores</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <x-primary-button type="submit">
                                Filtrar
                            </x-primary-button>

                            <a href="{{ route('markers.index') }}" class="inline-flex items-center rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] px-4 py-2 text-xs font-semibold uppercase text-gray-700 dark:text-gray-300 shadow-sm transition hover:bg-gray-50 dark:hover:bg-[#3d3d3d] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    @if ($markers->isEmpty())
                        <p class="rounded-lg border border-dashed border-gray-300 dark:border-neutral-600 bg-gray-50 dark:bg-[#323232] px-4 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
                            No se encontraron marcadores con los criterios seleccionados.
                        </p>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-600">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-600 text-sm">
                                <thead class="bg-gray-50 dark:bg-[#3d3d3d]">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Titulo</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Tipo</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Estado</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Propietario</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Coordenadas</th>
                                        <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-neutral-600 bg-white dark:bg-[#323232]">
                                    @foreach ($markers as $marker)
                                        <tr class="transition hover:bg-gray-50 dark:hover:bg-[#3d3d3d]">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $marker->title }}</div>
                                                @if ($marker->address)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $marker->address }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium {{ $typeBadgeClasses[$marker->type] ?? 'border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-[#323232] text-gray-700 dark:text-gray-300' }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $typeDotClasses[$marker->type] ?? 'bg-gray-400' }}"></span>
                                                    {{ $types[$marker->type] ?? $marker->type }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium {{ $statusBadgeClasses[$marker->status] ?? 'border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-[#323232] text-gray-700 dark:text-gray-300' }}">
                                                    {{ $statuses[$marker->status] ?? $marker->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $marker->user?->name ?? 'Sin usuario' }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-400">
                                                {{ $marker->latitude }}, {{ $marker->longitude }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex justify-end gap-3">
                                                    @can('update', $marker)
                                                        <a href="{{ route('markers.edit', $marker) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
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
                                                                    class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300"
                                                                    onclick="return confirm('Se marcará este registro como removido. ¿Continuar?')"
                                                                >
                                                                    Remover
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @cannot('update', $marker)
                                                        <span class="text-sm text-gray-400 dark:text-gray-500">
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
