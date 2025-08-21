@extends('adminlte::page')

@section('title', 'Consultas')

@section('content')
<div class="container">
    <br>
    @include('flash::message')
    <h1>Lista de Consultas</h1>
    <div class="row ml-3">
        <!-- Formulario de búsqueda -->
        <form action="{{ route('consultas.index') }}" method="GET" class="mb-4 w-100">
            <div class="row">
                <div class="col-form-label">
                    <label for="tipo_busqueda" class="form-label">Buscar por:</label>
                </div>
                <div class="col-md-2">
                    <select name="tipo_busqueda" id="tipo_busqueda" class="form-select form-control">
                        <option value="nombre" @if($tipoBusqueda == 'nombre') selected @endif>Nombre</option>
                        <option value="apellido" @if($tipoBusqueda == 'apellido') selected @endif>Apellido</option>
                        <option value="num_doc" @if($tipoBusqueda == 'num_doc') selected @endif>C.I</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="search" name="buscarpor" value="{{ $buscarPor }}" class="form-control" placeholder="Buscar...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Botón nuevo -->
        <div class="col-auto ml-auto">
            <button type="button" hidden id="bt-nuevaConsulta" data-toggle="modal" data-target="#nuevaConsultaModal"></button>
        </div>
    </div>

    <!-- Modal para crear nueva consulta -->
    @if($query)
    <div class="modal fade" id="nuevaConsultaModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="nuevaConsultaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevaConsultaModalLabel">Nueva Consulta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('consultas.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="sintomas">Síntomas</label>
                            <textarea type="text" class="form-control" id="sintomas" name="sintomas" required></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label for="diagnostico">Diagnóstico</label>
                            <textarea type="text" class="form-control" id="diagnostico" name="diagnostico" required></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label for="tratamiento">Tratamiento</label>
                            <textarea type="text" class="form-control" id="tratamiento" name="tratamiento" required></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label for="observaciones">Observaciones</label>
                            <textarea type="text" class="form-control" id="observaciones" name="observaciones"></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label for="paciente_id">Paciente</label>
                            <select class="custom-select" aria-label="Default select example" name="paciente_id" required>
                                <option value="{{ $cita->paciente->id_paciente }}">{{ $cita->paciente->nombre.' '.$cita->paciente->apellido }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="cita_id">Medico</label>
                            <select class="custom-select" aria-label="Default select example" name="medico_id" required>
                                <option value="{{ $cita->medico->id_medico }}">{{ $cita->medico->nombre.' '.$cita->medico->apellido }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="sala_id">Sala</label>
                            <select class="custom-select" aria-label="Default select example" name="sala_id" required>
                                <option value="{{ $cita->sala->id_sala }}">{{ $cita->sala->num_sala.' - '.$cita->sala->tipo_sala}}</option>
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="tipo_consulta_id">Tipo de Consulta</label>
                            <select class="custom-select" aria-label="Default select example" name="tipo_consulta_id" required>
                                <option value="{{ $cita->tipoConsulta->id_tipo_consulta }}">{{$cita->tipoConsulta->descripcion }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="cita_id">Cita</label>
                            <select class="custom-select" aria-label="Default select example" name="cita_id" required>
                                <option value="{{ $cita->id_cita }}">{{ $cita->id_cita.' - '.\Carbon\Carbon::parse($cita->fec_inicio)->format('d/m/Y H:i') }}</option>
                            </select>
                        </div>
                            
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-striped table-border" id="tabla">
            <thead>
                <tr>
                    <th scope="col">Paciente</th>
                    <th scope="col">Síntomas</th>
                    <th scope="col">Diagnóstico</th>
                    <th scope="col">Tratamiento</th>
                    <th scope="col">Observaciones</th>
                    <th scope="col">Medico</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @unless(empty($consultas))
                    @foreach ($consultas as $c)
                    <tr>
                        <td>{{ $c->paciente->nombre }}</td>
                        <td>{{ $c->sintomas }}</td>
                        <td>{{ $c->diagnostico }}</td>
                        <td>{{ $c->tratamiento }}</td>
                        <td>{{ $c->observaciones }}</td>
                        <td>{{ $c->medico->nombre }}</td>
                        <td>
                            <div class="btn-group">
                                <div class="mr-2">
                                    {{-- Botón editar --}}
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarConsultaModal{{ $c->id }}">Editar</button>

                                    <!-- Modal para editar consulta -->
                                    <div class="modal fade" id="editarConsultaModal{{ $c->id }}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="editarConsultaModalLabel{{ $c->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarConsultaModalLabel{{ $c->id }}">Editar Consulta</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('consultas.update', $c->id_consulta) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group mb-2">
                                                            <label for="sintomas">Síntomas</label>
                                                            <textarea type="text" class="form-control" name="sintomas" id="sintomas">{{ $c->sintomas }}</textarea>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label for="diagnostico">Diagnóstico</label>
                                                            <textarea type="text" class="form-control" name="diagnostico" id="diagnostico">{{ $c->diagnostico }}</textarea>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label for="tratamiento">Tratamiento</label>
                                                            <textarea type="text" class="form-control" name="tratamiento" id="tratamiento">{{ $c->tratamiento }}</textarea>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label for="observaciones">Observaciones</label>
                                                            <textarea type="text" class="form-control" name="observaciones" id="observaciones">{{ $c->observaciones }}</textarea>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label for="paciente_id">Paciente</label>
                                                            <select class="custom-select" name="paciente_id">
                                                                <option value="{{ $c->paciente_id }}" >{{ $c->paciente->nombre.' '.$c->paciente->apellido }}</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="form-group mb-2">
                                                            <label for="cita_id">Medico</label>
                                                            <select class="custom-select" name="cita_id">
                                                                <option value="{{ $c->medico_id }}">{{ $c->medico->nombre }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label for="sala_id">Sala</label>
                                                            <select class="custom-select" name="sala_id">
                                                                <option value="{{ $c->sala_id }}">{{ $c->sala->num_sala.' - '.$c->sala->tipo_sala }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label for="tipo_consulta_id">Tipo de Consulta</label>
                                                            <select class="custom-select" name="tipo_consulta_id">
                                                                <option value="{{ $c->tipo_consulta_id }}">{{ $c->tipoConsulta->descripcion  }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label for="cita_id">Cita</label>
                                                            <select class="custom-select" name="cita_id">
                                                                <option value="{{ $c->cita_id }}">{{ $c->cita_id.' - '.\Carbon\Carbon::parse($c->cita->fec_inicio)->format('d/m/Y') }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="d-flex justify-content-between mt-4">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">Actualizar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Botón eliminar --}}
                                <form action="{{ route('consultas.delete', $c) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta consulta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center">No hay consultas registradas</td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </div>
    <br>
    
</div>
@endsection

@section('js')
    @if($query)
        <script>
            $(document).ready(function () {
                $('#nuevaConsultaModal').modal('show');
            });
        </script>
    @endif
@endsection