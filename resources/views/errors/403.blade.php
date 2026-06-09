<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Acceso no permitido - {{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo_marcadores_claro.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <main class="min-h-screen bg-gray-100 px-4 py-12">
            <div class="mx-auto max-w-md rounded-lg bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-500">403</p>
                <h1 class="mt-2 text-2xl font-semibold text-gray-900">Acceso no permitido</h1>
                <p class="mt-3 text-sm text-gray-600">
                    Tu usuario no tiene permiso para entrar a esta sección.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            Ir al panel
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            Iniciar sesión
                        </a>
                    @endauth

                    <button type="button" onclick="history.back()" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        Volver atrás
                    </button>
                </div>
            </div>
        </main>
    </body>
</html>
