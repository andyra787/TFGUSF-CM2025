@extends('adminlte::page')

@section('title', 'Departametos')

@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Lista de Departamentos</h1>
        <form action="{{ route('departamentos.index') }}" method="GET">
            <div class="row g-2">
                <!--buscador -->
                <div class="col-auto">
                    <input type="search" name="buscarpor" value="{{$buscarpor ? $buscarpor : ''}}" class="form-control" placeholder="Buscar por nombre"
                        aria-label="search">
                </div>
                <div class="col-auto">
                    <button class="btn btn-info mb-2" type="submit">Buscar</button>
                </div>
        </form>

        <!-- boton nuevo -->
        <div class="col-auto ml-auto">
            <button type="button" class="btn btn-primary" data-toggle="modal"
                data-target="#nuevoDepartamentoModal">Nuevo</button>
        </div>

        <!-- Modal para crear nuevo departamento -->
        <div class="modal fade" id="nuevoDepartamentoModal" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="nuevoDepartamentoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoDepartamentoModalLabel">Nuevo Departamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('departamentos.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
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

        <script>
            $(document).ready(function() {
                $('#VerDepartamentoModal').on('hidden.bs.modal', function() {
                    $(this).find('form')[0].reset();
                });
            });
        </script>
    </div>

    <br>
    <!--tabla -->
    <div class="table-responsive">
        <table class="table table-striped table-border" id="tabla">
            <thead>
                <tr>
                    <th scope="col">Departamento</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @unless(empty($departamentos[0]))
                    @foreach ($departamentos as $d)
                        <tr>
                            <td>{{ $d->nombre }}</td>
                            <td>
                                <div class="btn-group">
                                    <!-- <div class="mr-2"> -->
                                        {{-- boton ver --}}
                                        <!-- <button type="button" class="btn btn-info" data-toggle="modal"
                                            data-target="#verDepartamentoModal{{ $d->id_departamento }}">Ver</button> -->

                                        <!-- Modal para ver especialidad -->
                                        <!-- <div class="modal fade" id="verDepartamentoModal{{ $d->id_departamento }}"
                                            tabindex="-1" role="dialog" data-backdrop="static"
                                            aria-labelledby="verDepartamentoModalLabel{{ $d->id_departamento }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="verDepartamentoModalLabel{{ $d->id_departamento }}">Detalle del
                                                            Departamento</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="name">Nombre</label>
                                                        <input type="text" class="form-control" name="nombre" id="nombre"
                                                            value="{{ $d->nombre }}" disabled>
                                                        <br>
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- <script>
                                            $(document).ready(function() {
                                                $('#verDepartamentoModal').on('hidden.bs.modal', function() {
                                                    $(this).find('form')[0].reset();
                                                });
                                            });
                                        </script> -->
                                    <!-- </div> -->

                                    <div class="mr-2">
                                        {{-- boton editar --}}
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#editarDepartamentoModal{{ $d->id_departamento }}">Editar</button>

                                        <!-- Modal para editar especialidad -->
                                        <div class="modal fade" id="editarDepartamentoModal{{ $d->id_departamento }}"
                                            tabindex="-1" role="dialog" data-backdrop="static"
                                            aria-labelledby="editarDepartamentoModalLabel{{ $d->id_departamento }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editarDepartamentoModalLabel{{ $d->id_departamento }}">Editar
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('departamentos.update', $d) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre"
                                                                id="nombre" value="{{ $d->nombre }}">
                                                            <br>
                                                            <div class="d-flex justify-content-between">
                                                                <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancelar</button>
                                                                <input type="submit" class="btn btn-success"
                                                                    value="Guardar">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('#editarDepartamentoModal').on('hidden.bs.modal', function() {
                                                    $(this).find('form')[0].reset();
                                                });
                                            });
                                        </script>
                                    </div>
                                    {{-- <div class="mr-2">
                                        <form action="{{ route('departamentos.destroy', $d->id_departamento) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="2">No hay departamentos registrados</td>
                    </tr>
                @endunless
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $departamentos->links() }}
        </div>
    </div>
@endsection
