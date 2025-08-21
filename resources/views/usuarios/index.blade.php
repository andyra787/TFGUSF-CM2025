@extends('adminlte::page')

@section('title', 'Usuarios')

@section('plugins.Sweetalert2', true)

@section('content')
<div class="container">
        <br>
        @include('flash::message')
        <h1>Usuarios</h1>
        <form action="{{ route('usuarios.index') }}" method="GET">
            <div class="row g-2">
                <!--buscador -->
                <div class="col-auto">
                    <input type="search" name="buscarpor" class="form-control" value="{{$buscarpor ? $buscarpor : ''}}" placeholder="Buscar por nombre" aria-label="search">
                </div>
                <div class="col-auto">
                    <button class="btn btn-info mb-2" type="submit">Buscar</button>
                </div>
        </form>

        <!-- boton nuevo -->
        <div class="col-auto ml-auto">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevoUsuario">Nuevo</button>
        </div>

        <!-- Modal para crear nuevo usuario -->
        <div class="modal fade" id="nuevoUsuario" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="nuevoUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoUsuarioLabel">Nuevo Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('usuarios.store') }}" method="POST">
                            @csrf
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div class="form-group mt-4">
                                <label for="email">{{ __('Email') }}</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="form-group mt-4">
                                <label for="rol">{{ __('Rol') }}</label>
                                <select id="rol" name="rol" class="form-control white-background" required autocomplete="rol">
                                    <option value="">--Seleccionar--</option>
                                    <option value="admin">Admin</option>
                                    <option value="administrativo">Administrador</option>
                                    <option value="recepcionista">Recepcionista</option>
                                    <option value="medico">Doctor</option>
                                </select>
                                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                            </div>

                            <div class="form-group mt-4" id="medicoSelect" hidden>
                                <label for="medico">{{ __('Medico ID') }}</label>
                                <select id="medico" name="medico" class="form-control white-background" required autocomplete="medico">
                                    @unless(empty($medicos[0]))
                                        <option value="nn">--Seleccionar--</option>
                                        @foreach($medicos as $m)
                                            <option value="{{$m->id_medico}}">{{$m->nombre}}</option>
                                        @endforeach
                                    @else
                                        <option value="nn">-- No hay medicos para seleccionar --</option>
                                    @endunless
                                </select>
                                <x-input-error :messages="$errors->get('medico')" class="mt-2" />
                            </div>

                            <div class="form-group mt-4">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" class="form-control" type="password" name="password" minlength="8" required autocomplete="new-password" />
                                <img src="{{ asset('icons/ojo.png') }}" 
                                    alt="Mostrar contraseña" 
                                    id="toggleIconPassword" 
                                    onclick="togglePassword('password', 'toggleIconPassword')" 
                                    style="width: 24px; height: 24px; position: absolute; right: 25px; transform: translateY(-30px); cursor: pointer;">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <small id="passwordHelp" class="form-text text-danger" style="display: none;">La contraseña debe tener al menos 8 caracteres.</small>
                            </div>

                            <div class="form-group mt-4">
                                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <img src="{{ asset('icons/ojo.png') }}" 
                                    alt="Mostrar contraseña" 
                                    id="toggleIconConfirmPassword" 
                                    onclick="togglePassword('password_confirmation', 'toggleIconConfirmPassword')" 
                                    style="width: 24px; height: 24px; position: absolute; right: 25px; transform: translateY(-30px); cursor: pointer;">
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <script>
                                document.getElementById('password').addEventListener('input', function () {
                                    const passwordHelp = document.getElementById('passwordHelp');
                                    if (this.value.length < 8) {
                                        passwordHelp.style.display = 'block';
                                    } else {
                                        passwordHelp.style.display = 'none';
                                    }
                                });
                            </script>

                            <div class="d-flex justify-content-between mt-3">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
								<button type="submit" class="btn btn-success">Guardar</button>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <!--tabla -->
    <div class="table-responsive">
        <table class="table table-striped table-border" id="tabla">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @unless(empty($usuarios[0]))
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->rol }}</td>
                            <td>
                            <div class="btn-group">
                                    <div class="mr-2">
                                        <!-- boton editar -->
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarUsuario{{ $usuario->id }}">Editar</button>

                                        <!-- Modal para editar tipo de consulta-->
                                        <div class="modal fade" id="editarUsuario{{ $usuario->id }}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="editarUsuarioLabel{{ $usuario->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editarUsuarioLabel{{ $usuario->id }}">Editar Datos del usuario</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('usuarios.update') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" class="form-control" name="id" id="id_{{ $usuario->id }}" value="{{ $usuario->id }}">
                                                            <div class="form-group mb-4">
                                                                <label for="nombre{{$usuario->id}}">Nombre</label>
                                                                <input type="text" class="form-control" name="name" id="nombre{{$usuario->id}}" value="{{ $usuario->name }}">
                                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                            </div>
                                                            
                                                            <div class="form-group mt-4 mb-4">
                                                                <label for="email{{$usuario->id}}">Email</label>
                                                                <input type="text" class="form-control" name="email" id="email{{$usuario->id}}" value="{{ $usuario->email }}">
                                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                            </div>

                                                            <div class="form-group mt-4 mb-4">
                                                                <label for="rol">Rol</label>
                                                                <select class="form-control" id="rolEdit{{$usuario->id}}" name="rol">
                                                                    <option>-- Seleccionar un rol --</option>
                                                                    <option value="admin" {{$usuario->rol == 'admin' ? 'selected' : null}}>Admin</option>
                                                                    <option value="administrativo" {{$usuario->rol == 'administrativo' ? 'selected' : null}}>Administrador</option>
                                                                    <option value="recepcionista" {{$usuario->rol == 'recepcionista' ? 'selected' : null}}>Recepcionista</option>
                                                                    <option value="medico" {{$usuario->rol == 'medico' ? 'selected' : null}}>Doctor</option>
                                                                </select>
                                                                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                                                            </div>

                                                            <div class="form-group mt-4" id="medicoSelectEdit{{$usuario->id}}" hidden>
                                                                <label for="medico{{$usuario->id}}">{{ __('Medico ID') }}</label>
                                                                <select id="medico{{$usuario->id}}" name="medico" class="form-control white-background" required>
                                                                    @unless(empty($medicos[0]))
                                                                        <option value="nn">--Seleccionar--</option>
                                                                        @foreach($medicos as $m)
                                                                            <option value="{{$m->id_medico}}">{{$m->nombre}}</option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="nn">-- No hay medicos para seleccionar --</option>
                                                                    @endunless
                                                                </select>
                                                                <x-input-error :messages="$errors->get('medico')" class="mt-2" />
                                                            </div>

                                                            <div class="d-flex justify-content-between">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                <input type="submit" class="btn btn-success" value="Guardar">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mr-2">
                                        <!-- boton editar password -->
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#cambiarContra{{ $usuario->id }}">Cambiar Contraseña</button>

                                        <!-- Modal para editar -->
                                        <div class="modal fade" id="cambiarContra{{ $usuario->id }}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="cambiarContraLabel{{ $usuario->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cambiarContraLabel{{ $usuario->id }}">Cambiar contraseña del usuario</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('usuarios.passChange') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" class="form-control" name="id" id="id{{ $usuario->id }}" value="{{ $usuario->id }}">
                                                            
                                                            <div class="form-group mb-4">
                                                                <label for="changePassword{{$usuario->id}}">Contraseña Nueva</label>
                                                                <input type="password" class="form-control" minlength="8" name="changePassword" id="changePassword{{$usuario->id}}" required>
                                                                <small id="passwordHelp{{$usuario->id}}" class="form-text text-danger" style="display: none;">La contraseña debe tener al menos 8 caracteres.</small>
                                                            </div>
                                                    
                                                            <div class="form-group mt-4 mb-4">
                                                                <label for="password_confirmation{{$usuario->id}}">Confirmar Contraseña Nueva</label>
                                                                <input type="password" class="form-control" minlength="8" name="password_changeConfirmation" id="password_confirmation{{$usuario->id}}" required>
                                                                <small id="passwordMismatch{{$usuario->id}}" class="form-text text-danger" style="display: none;">Las contraseñas no coinciden.</small>
                                                            </div>

                                                            <script>
                                                                document.getElementById('changePassword{{$usuario->id}}').addEventListener('input', validatePasswords{{$usuario->id}});
                                                                document.getElementById('password_confirmation{{$usuario->id}}').addEventListener('input', validatePasswords{{$usuario->id}});

                                                                function validatePasswords{{$usuario->id}}() {
                                                                    const password = document.getElementById('changePassword{{$usuario->id}}').value;
                                                                    const confirmPassword = document.getElementById('password_confirmation{{$usuario->id}}').value;
                                                                    const mismatchMessage = document.getElementById('passwordMismatch{{$usuario->id}}');

                                                                    if (password !== confirmPassword) {
                                                                        mismatchMessage.style.display = 'block';
                                                                    } else {
                                                                        mismatchMessage.style.display = 'none';
                                                                    }
                                                                }
                                                            </script>

                                                            <script>
                                                                document.getElementById('changePassword{{$usuario->id}}').addEventListener('input', function () {
                                                                    const passwordHelp = document.getElementById('passwordHelp{{$usuario->id}}');
                                                                    if (this.value.length < 8) {
                                                                        passwordHelp.style.display = 'block';
                                                                    } else {
                                                                        passwordHelp.style.display = 'none';
                                                                    }
                                                                });
                                                            </script>
                                                           
                                                            <div class="d-flex justify-content-between">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                <input type="submit" class="btn btn-success" value="Guardar">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mr-2">
                                        <form action="{{ route('usuarios.cambiarEstado') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $usuario->id }}">
                                            <button type="submit" class="{{ $usuario->estado == 'activo' ? 'btn btn-danger' : 'btn btn-success' }}">{{ $usuario->estado == 'activo' ? 'Desactivar' : 'Activar' }}</button>
                                        </form>
                                    </div>
                                </div>
                            </td>                                
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="5">No hay usuarios registrados</td>
                    </tr>
                @endunless
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $usuarios->links() }}
        </div>
    </div>
@stop

@section('css')
@stop

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
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            let rol = document.getElementById('rol');
            let medicoSelect = document.getElementById('medicoSelect');

            rol.addEventListener('change', () => {
                if(rol.value == 'medico') {
                    medicoSelect.hidden = false;
                } else {
                    medicoSelect.hidden = true;
                }
            });

            @foreach ($usuarios as $usuario)
                let rolEdit{{ $usuario->id }} = document.getElementById('rolEdit{{ $usuario->id }}');
                let medicoSelectEdit{{ $usuario->id }} = document.getElementById('medicoSelectEdit{{ $usuario->id }}');

                if (rolEdit{{ $usuario->id }} && medicoSelectEdit{{ $usuario->id }}) {
                    rolEdit{{ $usuario->id }}.addEventListener('change', () => {
                        if(rolEdit{{ $usuario->id }}.value == 'medico') {
                            medicoSelectEdit{{ $usuario->id }}.hidden = false;
                        } else {
                            medicoSelectEdit{{ $usuario->id }}.hidden = true;
                        }
                    });
                }
            @endforeach
        });
    </script>

    
    @if (session('passChangeError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("passChangeError") }}',
            })
        </script>
    @endif
    
    @if (session('userCreateUpdate'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Atención!',
                text: '{{ session("userCreateUpdate") }}',
            })
        </script>
    @endif

    @if(session('passChangeSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Guardado!',
                text: '{{ session("passChangeSuccess") }}',
            })
        </script>
    @endif
    
    @if(session('cambioEstado'))
        <script>
            Swal.fire({
                icon: "success",
                title: 'Estado actualizado',
                text: '{{ session("cambioEstado") }}',
            })
        </script>
    @endif
@stop