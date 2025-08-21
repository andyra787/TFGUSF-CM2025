@extends('adminlte::page')
@section('title', 'Pacientes')
@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Lista de Pacientes</h1>
        <form action="{{ route('pacientes.index') }}" method="GET">
            <div class="row g-2">
                <!--buscador -->
                <div class="col-auto">
                    <label for="tipo_busqueda" class="col-form-label">Buscar por: </label>
                </div>
                <div class="col-auto">
                    <select name="tipo_busqueda" id="tipo_busqueda" class="form-select form-control">
                        <option value="nombre" @if($tipo_busqueda == 'nombre') selected @endif>Nombre</option>
                        <option value="apellido" @if($tipo_busqueda == 'apellido') selected @endif>Apellido</option>
                        <option value="num_doc" @if($tipo_busqueda == 'num_doc') selected @endif>C.I</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="search" name="buscarpor" value="{{$buscarpor ? $buscarpor : ''}}" class="form-control" placeholder="Buscar..." aria-label="search">
                </div>
                <div class="col-auto">
                    <button class="btn btn-info mb-2" type="submit">Buscar</button>
                </div>
                <!-- boton nuevo -->
                <div class="col-auto ml-auto">
                    <a href="/registro-paciente" class="btn btn-success">Nuevo</a>
                </div>
            </div>
        </form>

        
    </div>

    <br>

    <div class="table-responsive">
        <table class="table table-striped table-border" id="tabla">
            <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">C.I</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Sexo</th>
                <th scope="col">Edad</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
                @unless(empty($pacientes[0]))
                    @foreach($pacientes as $paciente)
                        <tr class="">
                            <td scope="row">{{$paciente->cod_paciente}}</td>
                            <td scope="row">{{$paciente->num_doc}}</td>
                            <td scope="row">{{$paciente->nombre}}</td>
                            <td scope="row">{{$paciente->apellido}}</td>
                            <td scope="row">{{$paciente->sexo}}</td>
                            <td scope="row">{{$paciente->edad}}</td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#verDatosPaciene{{ $paciente->id_paciente }}">Ver</button>

                                <!-- Modal para ver especialidad -->
                                <div class="modal fade" id="verDatosPaciene{{ $paciente->id_paciente }}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="verDatosPacieneLabel{{ $paciente->id_paciente }}" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="verDatosPacieneLabel{{ $paciente->id_paciente }}">Detalle del Paciente</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="depto">Nombre</label>
                                                        <input type="text" class="form-control" name="dpto" id="dpto" value="{{ $paciente->nombre }}" disabled>
                                                    </div>
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="ciudad">Apellido</label>
                                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="{{ $paciente->apellido }}" disabled>
                                                    </div>
                                                    <div class="form-group col-12 mb-4">
                                                        <label for="ciudad">C.I</label>
                                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="{{ $paciente->num_doc }}" disabled>
                                                    </div>
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="ciudad">Sexo</label>
                                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="{{ $paciente->sexo }}" disabled>
                                                    </div>
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="ciudad">Edad</label>
                                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="{{ $paciente->edad }}" disabled>
                                                    </div>
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="depto">Departamento</label>
                                                        <input type="text" class="form-control" name="dpto" id="dpto" value="{{ $paciente->departamento }}" disabled>
                                                    </div>
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="ciudad">Ciudad</label>
                                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="{{ $paciente->ciudad }}" disabled>
                                                    </div>
                                                    <div class="form-group col-6 mb-4">
                                                        <label for="direccion">Dirección</label>
                                                        <input type="text" class="form-control" name="direccion" id="direccion" value="{{ $paciente->direccion }}" disabled>
                                                    </div>
                                                    <div class="form-group col-4 mb-4">
                                                        <label for="maps">MAPS</label>
                                                        <input type="text" class="form-control" name="maps" id="maps" value="{{ $paciente->url_maps }}" disabled>
                                                    </div>
                                                    <div class="form-group col-2 mb-4">
                                                        <label for="mapUrl">Botón de enlace</label>
                                                        <a href="{{ $paciente->url_maps }}" id="mapUrl" class="@if($paciente->url_maps == '') disabled @endif btn btn-secondary col-12" target="_blank"> Abrir en MAPS</a>
                                                    </div>
                                                    <div class="form-group col-12 mb-4">
                                                        <label for="diagnostico">Diagnostico</label>
                                                        <textarea class="form-control" id="diagnostico" disabled>{{ $paciente->diagnostico }}</textarea>
                                                    </div>
                                                    <div class="form-group col-12 mb-4">
                                                        <label for="comentario">Comentario</label>
                                                        <textarea class="form-control" id="comentario" disabled>{{ $paciente->comentario }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-danger col-6" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('pacientes.edit', $paciente->id_paciente) }}" class="btn btn-warning">Editar</a>
                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="11">No hay pacientes registrados</td>
                    </tr>
                @endunless

            </tbody>
        </table>
    </div>
@stop

@section('css')

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="sweetalert2.all.min.js"></script>
        <script>import Swal from 'sweetalert2'</script>
        @if(session('mensaje'))
            <script>
                Swal.fire({
                title: "¡Guardado con éxito",
                text: "El paciente se creó correctamente",
                icon: "success"
                });</script>
        @endif

@stop
