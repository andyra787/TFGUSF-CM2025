<x-guest-layout>
    <!-- Define el diseño de la página como el diseño de invitado -->

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        <!-- Muestra un mensaje de agradecimiento y solicita la verificación del correo electrónico -->
    </div>

    @if (session('status') == 'verification-link-sent')
        <!-- Verifica si hay un mensaje en la sesión indicando que se envió un enlace de verificación -->

        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            <!-- Muestra un mensaje indicando que se envió un nuevo enlace de verificación -->
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <!-- Contenedor para los botones de reenviar el correo de verificación y cerrar sesión -->

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <!-- Agrega un token CSRF para proteger el formulario contra ataques CSRF -->

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                    <!-- Botón para reenviar el correo de verificación -->
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <!-- Agrega un token CSRF para proteger el formulario contra ataques CSRF -->

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Log Out') }}
                <!-- Botón para cerrar sesión -->
            </button>
        </form>
    </div>
</x-guest-layout>
<!-- Finaliza el diseño de la página como el diseño de invitado -->
