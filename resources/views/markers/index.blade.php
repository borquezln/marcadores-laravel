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
                    @if ($markers->isEmpty())
                        <p class="text-sm text-gray-600">
                            Todavía no hay marcadores cargados.
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
