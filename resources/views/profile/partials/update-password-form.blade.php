<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Actualizar contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Asegúrese que su cuenta use una contraseña larga y aleatoria para mantenerla segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label style="color: #111827;" for="current_password" :value="__('Contraseña actual')" />
            <x-text-input style="background-color: white; color: #111827;" id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label style="color: #111827;" for="password" :value="__('Nueva Contraseña')" />
            <x-text-input style="background-color: white; color: #111827;" id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" oninput="validatePassword()" />
            <p id="password-requirements" class="text-sm mt-2" style="color: red; display: none;">
            {{ __('La contraseña debe tener al menos 8 caracteres.') }}
            </p>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label style="color: #111827;" for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input style="background-color: white; color: #111827;" id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <script>
            function validatePassword() {
            const passwordInput = document.getElementById('password');
            const requirementsMessage = document.getElementById('password-requirements');

            if (passwordInput.value.length >= 8) {
                requirementsMessage.style.color = 'green';
                requirementsMessage.textContent = "{{ __('La contraseña cumple con los requisitos.') }}";
                requirementsMessage.style.display = 'block';
            } else {
                requirementsMessage.style.color = 'red';
                requirementsMessage.textContent = "{{ __('La contraseña debe tener al menos 8 caracteres.') }}";
                requirementsMessage.style.display = 'block';
            }
            }
        </script>

        <div class="flex items-center gap-4">
            <x-primary-button style="background-color:#007bff; color: white;">{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
@section('js')
<script>
       function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.src = "{{ asset('icons/invisible.png') }}"; // Cambia el ícono a uno que indique "ocultar"
                icon.alt = "Ocultar contraseña";
            } else {
                passwordInput.type = "password";
                icon.src = "{{ asset('icons/ojo.png') }}"; // Cambia el ícono de nuevo al de "mostrar"
                icon.alt = "Mostrar contraseña";
            }
}

    </script>
        @stop