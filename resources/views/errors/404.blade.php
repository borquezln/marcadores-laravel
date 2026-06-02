<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Página no encontrada - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <main class="min-h-screen bg-gray-100 px-4 py-12">
            <div class="mx-auto max-w-md rounded-lg bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-500">404</p>
                <h1 class="mt-2 text-2xl font-semibold text-gray-900">Página no encontrada</h1>
                <p class="mt-3 text-sm text-gray-600">
                    La dirección que intentaste abrir no existe o ya no está disponible.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                        Ir al inicio
                    </a>

                    <button type="button" onclick="history.back()" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        Volver atrás
                    </button>
                </div>
            </div>
        </main>
    </body>
</html>
