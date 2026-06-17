<div class="space-y-4">
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Los campos marcados con <span class="font-semibold text-red-600 dark:text-red-400">*</span> son obligatorios.
    </p>
</div>

<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="title" value="Título *" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $marker->title)" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" value="Tipo *" />
        <select
            id="type"
            name="type"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            @foreach ($types as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $marker->type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="address" value="Dirección" />
        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $marker->address)" />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="latitude" value="Latitud *" />
        <x-text-input id="latitude" name="latitude" type="text" inputmode="decimal" class="mt-1 block w-full" :value="old('latitude', $marker->latitude)" required />
        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="longitude" value="Longitud *" />
        <x-text-input id="longitude" name="longitude" type="text" inputmode="decimal" class="mt-1 block w-full" :value="old('longitude', $marker->longitude)" required />
        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
    </div>

    <div class="sm:col-span-2 flex flex-col gap-2">
        <div>
            <x-secondary-button id="get-my-location-form" type="button">
                Usar mi ubicación actual
            </x-secondary-button>
        </div>
        <div id="geolocation-status-form" class="hidden text-sm p-3 rounded-md"></div>
    </div>

    <div>
        <x-input-label for="status" value="Estado *" />
        <select
            id="status"
            name="status"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $marker->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="notes" value="Notas" />
        <textarea
            id="notes"
            name="notes"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-[#323232] text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('notes', $marker->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const statusForm = document.getElementById('geolocation-status-form');
        const showStatusForm = (text, type) => {
            statusForm.className = 'text-sm p-3 rounded-md border mt-2';
            if (type === 'loading') {
                statusForm.classList.add('border-blue-200', 'dark:border-blue-800', 'bg-blue-50', 'dark:bg-blue-900/30', 'text-blue-700', 'dark:text-blue-300');
            } else if (type === 'error') {
                statusForm.classList.add('border-red-200', 'dark:border-red-800', 'bg-red-50', 'dark:bg-red-900/30', 'text-red-700', 'dark:text-red-300');
            }
            statusForm.textContent = text;
            statusForm.classList.remove('hidden');
        };
        const hideStatusForm = () => {
            statusForm.classList.add('hidden');
        };

        const btnForm = document.getElementById('get-my-location-form');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        btnForm.addEventListener('click', () => {
            if (!navigator.geolocation) {
                showStatusForm('La geolocalización no está soportada por este navegador.', 'error');
                return;
            }

            showStatusForm('Cargando ubicación...', 'loading');

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    hideStatusForm();
                    latInput.value = position.coords.latitude;
                    lngInput.value = position.coords.longitude;
                },
                (error) => {
                    let message = 'Error de geolocalización.';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Permiso de geolocalización denegado.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Ubicación no disponible.';
                            break;
                        case error.TIMEOUT:
                            message = 'Tiempo de espera agotado al obtener la ubicación.';
                            break;
                        default:
                            message = 'Error de geolocalización: ' + error.message;
                            break;
                    }
                    showStatusForm(message, 'error');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    });
</script>
