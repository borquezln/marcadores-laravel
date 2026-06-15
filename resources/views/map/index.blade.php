@php
    use Illuminate\Support\Js;

    $markerCount = count($markers);
    $typeColors = [
        'place' => '#3b82f6',
        'billboard' => '#ef4444',
        'stand' => '#10b981',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mapa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#323232] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Marcadores activos</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $markerCount }} {{ $markerCount === 1 ? 'marcador visible' : 'marcadores visibles' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-[#3d3d3d] px-3 py-2">
                        @foreach ($types as $value => $label)
                            <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-neutral-600 bg-white dark:bg-[#323232] px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 shadow-sm">
                                <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $typeColors[$value] ?? '#6b7280' }}"></span>
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>

                    <link
                        rel="stylesheet"
                        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                        crossorigin=""
                    >

                    <style>
                        #map {
                            width: 100%;
                            height: 32rem;
                            min-height: 24rem;
                        }

                        #map.leaflet-container img {
                            max-width: none;
                        }
                    </style>

                    <div id="map" class="overflow-hidden rounded border border-gray-200 dark:border-neutral-600"></div>

                    @if ($markerCount === 0)
                        <p class="text-sm text-gray-600 dark:text-gray-400">No hay marcadores activos para mostrar.</p>
                    @endif

                    <script
                        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                        crossorigin=""
                    ></script>

                    <script>
                        L.Marker.prototype.options.icon = L.icon({
                            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41],
                        });

                        const typeColors = {{ Js::from($typeColors) }};
                        const markers = {{ Js::from($markers) }};
                        const defaultCenter = {{ Js::from($defaultCenter) }};
                        const initialCenter = markers.length > 0
                            ? [markers[0].latitude, markers[0].longitude]
                            : [defaultCenter.latitude, defaultCenter.longitude];

                        const mapElement = document.getElementById('map');
                        const map = L.map(mapElement).setView(initialCenter, defaultCenter.zoom);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        }).addTo(map);

                        const escapeHtml = (value) => String(value ?? '')
                            .replaceAll('&', '&amp;')
                            .replaceAll('<', '&lt;')
                            .replaceAll('>', '&gt;')
                            .replaceAll('"', '&quot;')
                            .replaceAll("'", '&#039;');

                        markers.forEach((marker) => {
                            const popup = [
                                `<strong>${escapeHtml(marker.title)}</strong>`,
                                `Tipo: ${escapeHtml(marker.type_label)}`,
                                marker.address ? `Direccion: ${escapeHtml(marker.address)}` : null,
                            ].filter(Boolean).join('<br>');

                            const color = typeColors[marker.type] || '#6b7280';

                            L.circleMarker([marker.latitude, marker.longitude], {
                                radius: 8,
                                fillBackgroundColor: color,
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.8,
                                fillColor: color
                            })
                            .addTo(map)
                            .bindPopup(popup);
                        });

                        const resizeMap = () => {
                            map.invalidateSize();
                        };

                        requestAnimationFrame(resizeMap);
                        window.addEventListener('load', resizeMap, { once: true });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
