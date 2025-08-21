@extends('adminlte::page')

@section('title', 'Tipos de Consulta')

@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Tipos de Consultas</h1>
        <form action="{{ route('tipos-consulta.index') }}" method="GET">
            <div class="row g-2">
                <!--buscador -->
                <div class="col-auto">
                    <input type="search" name="buscarpor" class="form-control" value="{{$buscarpor ? $buscarpor : ''}}" placeholder="Buscar por tipo" aria-label="search">
                </div>
                <div class="col-auto">
                    <button class="btn btn-info mb-2" type="submit">Buscar</button>
                </div>
        </form>

        <!-- boton nuevo -->
        <div class="col-auto ml-auto">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevoTipoConsulta">Nuevo</button>
        </div>

        <!-- Modal para crear nuevo tipo  -->
        <div class="modal fade" id="nuevoTipoConsulta" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="nuevoTipoConsultaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoTipoConsultaLabel">Nuevo Tipo de Consulta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('tipos-consulta.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nombre">Tipo de Consulta</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Duracion Consulta (en minutos)</label>
                                <input type="text" class="form-control" id="duracion" name="duracion" required>
                            </div>
                            <div class="d-flex justify-content-between">
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
                    <th scope="col">Tipo de Consulta</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @unless(empty($tipos_de_consulta[0]))
                    @foreach ($tipos_de_consulta as $e)
                        <tr>
                            <td>{{ $e->descripcion }}</td>
                            <td>{{ $e->duracion }}</td>
                            <td>
                                <div class="btn-group">
                                        {{-- boton ver --}}
                                    <div class="mr-2">
                                        {{-- boton editar --}}
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarTipoConsultadModal{{ $e->id_tipo_consulta }}">Editar</button>

                                        <!-- Modal para editar tipo de colsulta -->
                                        <div class="modal fade" id="editarTipoConsultadModal{{ $e->id_tipo_consulta }}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="editarTipoConsultadModalLabel{{ $e->id_tipo_consulta }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editarTipoConsultadModalLabel{{ $e->id_especialidad }}">Editar Tipo de Conculta</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('tipos-consulta.update', $e) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <label for="descripcion">Tipo de Consulta</label>
                                                            <input type="text" class="form-control" name="descripcion" id="descripcion" value="{{ $e->descripcion }}">
                                                            <br>
                                                            <label for="duracion">Duracion Consulta (en minutos)</label>
                                                            <input type="text" class="form-control" name="duracion" id="duracion" value="{{ $e->duracion }}">
                                                            <br>
                                                            <div class="d-flex justify-content-between">
                                                                <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-success">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('#editarTipoConsultadModal').on('hidden.bs.modal', function() {
                                                    $(this).find('form')[0].reset();
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="mr-2">
                                        <form action="{{ route('tipos-consulta.destroy', $e->id_tipo_consulta) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </td>                                
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="3">No hay tipos de consulta registrados</td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </div>

    @stop

