<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Gracias por registrarte. Antes de continuar, verificá tu correo desde el enlace que te enviamos. Si no lo recibiste, podés pedir otro.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Te enviamos un nuevo enlace de verificación al correo registrado.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Reenviar correo de verificación
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
