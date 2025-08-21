@extends('adminlte::page')

@section('title', 'Ciudades')

@section('plugins.Select2', true)

@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Lista de Ciudades</h1>
        <form action="{{ route('ciudades.index') }}" method="GET">
            <div class="row g-2">
                <!--buscador -->
                <div class="col-auto">
                    <input type="search" name="buscarpor" class="form-control" value="{{$buscarpor ? $buscarpor : ''}}" placeholder="Buscar por nombre"
                        aria-label="search">
                </div>
                <div class="col-auto">
                    <button class="btn btn-info mb-2" type="submit">Buscar</button>
                </div>
        </form>

        <!-- boton nuevo -->
        <div class="col-auto ml-auto">
            <button type="button" class="btn btn-primary" data-toggle="modal"
                data-target="#nuevaCiudadModal">Nuevo</button>
        </div>

        <!-- Modal para crear nueva ciudad -->
        <div class="modal fade" id="nuevaCiudadModal" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="nuevaCiudadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaCiudadModalLabel">Nueva Ciudad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('ciudades.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                                <br>
                            </div>
                            <div class="form-group">
                                <label for="departamento_id">Departamento</label>
                                <select name="departamento_id" id="departamento_id" class="form-control" required>
                                    <option value="">Seleccione un departamento</option>
                                    @foreach ($departamentos as $d)
                                        <option value="{{ $d->id_departamento }}">{{ $d->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
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
                        <th scope="col">Ciudad</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @unless(empty($ciudades[0]))
                        @foreach ($ciudades as $c)
                            <tr>
                                <td>{{ $c->nombre }}</td>
                                <td>{{ $c->departamento->nombre }}</td>
                                <td>
                                    <div class="btn-group">
                                        <!-- <div class="mr-2"> -->
                                            {{-- boton ver --}}
                                            <!-- <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#verCiudadModad{{ $c->id_ciudad }}">Ver</button> -->

                                            <!-- Modal para ver medico -->
                                            <!-- <div class="modal fade" id="verCiudadModad{{ $c->id_ciudad }}" tabindex="-1"
                                                role="dialog" data-backdrop="static"
                                                aria-labelledby="verCiudadModadLabel{{ $c->id_ciudad }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="verCiudadModadLabel{{ $c->id_ciudad }}">
                                                                Detalle de la Ciudad
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label for="name">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre"
                                                                id="nombre" value="{{ $c->nombre }}" disabled>
                                                            <br>

                                                            <label for="departamento_id">Departamento</label>
                                                            <input type="text" class="form-control" name="departamento_id"
                                                                id="departamento_id" value="{{ $c->departamento->nombre }}"
                                                                disabled>
                                                            <br>
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function() {
                                                    $('#verCiudadModad').on('hidden.bs.modal', function() {
                                                        $(this).find('form')[0].reset();
                                                    });
                                                });
                                            </script>
                                        </div> -->

                                        <div class="mr-2">
                                            {{-- boton editar --}}
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#editarCiudadModal{{ $c->id_ciudad }}">Editar</button>

                                            <!-- Modal para editar medico -->
                                            <div class="modal fade" id="editarCiudadModal{{ $c->id_ciudad }}" tabindex="-1"
                                                role="dialog" data-backdrop="static"
                                                aria-labelledby="editarCiudadModalLabel{{ $c->id_ciudad }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editarCiudadModalLabel{{ $c->id_ciudad }}">Editar Ciudad
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('ciudades.update', $c) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <label for="nombre">Nombre</label>
                                                                <input type="text" class="form-control" name="nombre"
                                                                    id="nombre" value="{{ $c->nombre }}">
                                                                <br>

                                                                <label for="departamento_id">Departamento</label>
                                                                <select name="departamento_id" id="departamento_id"
                                                                    class="form-control">
                                                                    @foreach ($departamentos as $d)
                                                                        <option value="{{ $d->id_departamento }}"
                                                                            {{ $c->departamento_id == $d->id_departamento ? 'selected' : '' }}>
                                                                            {{ $d->nombre }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <br><br>
                                                                <div class="d-flex justify-content-between">
                                                                    <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-success">Guardar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="mr-2">
                                            <form action="{{ route('ciudades.update', $c->id_ciudad) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="estado" value="{{ $c->estado ? '0' : '1' }}">
                                                <button type="submit"
                                                    class="btn btn-{{ $c->estado ? 'danger' : 'success' }}">
                                                    {{ $c->estado ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        </div> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="3">No hay ciudades registradas</td>
                        </tr>
                    @endunless
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{ $ciudades->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@stop