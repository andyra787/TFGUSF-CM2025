<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Hospital</title>
    <!-- Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- CSS -->
    @vite(['resources/css/login-style.css'])
</head>
<body>
    <div class="login-container">
        <div class="form-container">
            <h2 class="text-center main-title">Iniciar Sesión</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="input-group">
                    <label for="email" :value="__('Email')" >Usuario</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- Password -->
                <div class="input-group">
                    <label for="password" :value="__('Password')" >Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" style="margin-bottom: 5px;" />
                      <!-- Ícono de ojo -->
                      <!-- Ícono de ojo -->
                    <!-- Ícono de ojo desactivado -->
                    <img src="{{ asset('icons/ojo.png') }}" 
                        alt="Mostrar contraseña" 
                        id="toggleIcon" 
                        onclick="togglePassword('password', 'toggleIcon')" 
                        style="width: 24px; height: 24px; position: absolute; right: 10px; top: 45px; transform: translateY(-50%); cursor: pointer; display: none;">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    
                </div>
                
                  
                 <!-- Remember Me -->
                <div class="input-group rememberMe-container" for="remember_me">
                    <input id="remember_me" type="checkbox" class="rounded" name="remember">
                    <span class="ml-5">{{ __('Recordarme') }}</span>
                </div>
                <div class="input-group access">
                    <button type="submit" class="access-button"> {{ __('Entrar') }}</button>
                </div>
                <!-- @if (Route::has('password.request'))
                <div class="access">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}"> {{ __('Olvidé mi contraseña') }}</a>
                </div>
                @endif -->
            </form>
        </div>
        <div class="logo-container">
            <img src="{{ asset('vendor/adminlte/dist/img/usf-Photoroom.png') }}" alt="Logo USF">
        </div>
    </div>
    <script>
        function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (input.type === "password") {
                    input.type = "text"; // Cambiar a texto
                    icon.src = "{{ asset('icons/invisible.png') }}"; // Cambiar a ícono de "ocultar"
                } else {
                    input.type = "password"; // Cambiar a contraseña
                    icon.src = "{{ asset('icons/ojo.png') }}"; // Cambiar a ícono de "mostrar"
                }
            }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session("error") }}',
                icon: 'error',
            })
        </script>
    @endif
</body>
</html>
