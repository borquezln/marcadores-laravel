<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    Revisá los valores seleccionados antes de guardar.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($users->isEmpty())
                        <p class="text-sm text-gray-600">
                            Todavía no hay usuarios registrados.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Nombre</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Email</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Rol</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Estado</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Último login</th>
                                        <th class="px-4 py-3 text-right font-medium text-gray-600">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($users as $managedUser)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                {{ $managedUser->name }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $managedUser->email }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @can('update', $managedUser)
                                                    <select
                                                        name="role"
                                                        form="user-form-{{ $managedUser->id }}"
                                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        required
                                                    >
                                                        @foreach ($roles as $value => $label)
                                                            <option value="{{ $value }}" @selected(old('role', $managedUser->role) === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <span class="text-gray-700">{{ $roles[$managedUser->role] ?? $managedUser->role }}</span>
                                                @endcan
                                            </td>
                                            <td class="px-4 py-3">
                                                @can('update', $managedUser)
                                                    <select
                                                        name="status"
                                                        form="user-form-{{ $managedUser->id }}"
                                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        required
                                                    >
                                                        @foreach ($statuses as $value => $label)
                                                            <option value="{{ $value }}" @selected(old('status', $managedUser->status) === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <span class="text-gray-700">{{ $statuses[$managedUser->status] ?? $managedUser->status }}</span>
                                                @endcan
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $managedUser->last_login_at?->timezone(config('app.timezone'))->format('d/m/Y H:i \G\M\TP') ?? 'Nunca' }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                @can('update', $managedUser)
                                                    <form id="user-form-{{ $managedUser->id }}" method="POST" action="{{ route('admin.users.update', $managedUser) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                                        >
                                                            Guardar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-sm text-gray-400">
                                                        No editable
                                                    </span>
                                                @endcan
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
