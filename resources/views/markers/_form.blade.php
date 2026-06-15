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
