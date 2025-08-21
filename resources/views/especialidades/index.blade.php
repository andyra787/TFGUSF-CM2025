@extends('adminlte::page')

@section('title', 'Especialidades')

@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Lista de Especialidades</h1>
        <form action="{{ route('especialidades.index') }}" method="GET">
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
                data-target="#nuevaEspecialidadModal">Nuevo</button>
        </div>

        <!-- Modal para crear nueva especialidad -->
        <div class="modal fade" id="nuevaEspecialidadModal" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="nuevaEspecialidadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaEspecialidadModalLabel">Nueva Especialidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('especialidades.store') }}" method="POST">
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
                $('#nuevaEspecialidadModal').on('hidden.bs.modal', function() {
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
                    <th scope="col">Especialidad</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @unless(empty($especialidades[0]))
                    @foreach ($especialidades as $e)
                        <tr>
                            <td>{{ $e->nombre }}</td>

                            <td>
                                <div class="btn-group">
                                    <!-- <div class="mr-2"> -->
                                        {{-- boton ver --}}
                                        <!-- <button type="button" class="btn btn-info" data-toggle="modal"
                                            data-target="#verEspecialidadModal{{ $e->id_especialidad }}">Ver</button> -->

                                        <!-- Modal para ver especialidad -->
                                        <!-- <div class="modal fade" id="verEspecialidadModal{{ $e->id_especialidad }}"
                                            tabindex="-1" role="dialog" data-backdrop="static"
                                            aria-labelledby="verEspecialidadModalLabel{{ $e->id_especialidad }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="verEspecialidadModalLabel{{ $e->id_especialidad }}">Detalle de
                                                            la Especialidad</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="name">Especialidad</label>
                                                        <input type="text" class="form-control" name="nombre" id="nombre"
                                                            value="{{ $e->nombre }}" disabled>
                                                        <br>
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- <script>
                                            $(document).ready(function() {
                                                $('#verEspecialidadModal').on('hidden.bs.modal', function() {
                                                    $(this).find('form')[0].reset();
                                                });
                                            });
                                        </script> -->
                                    <!-- </div> -->

                                    <div class="mr-2">
                                        {{-- boton editar --}}
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#editarEspecialidadModal{{ $e->id_especialidad }}">Editar</button>

                                        <!-- Modal para editar especialidad -->
                                        <div class="modal fade" id="editarEspecialidadModal{{ $e->id_especialidad }}"
                                            tabindex="-1" role="dialog" data-backdrop="static"
                                            aria-labelledby="editarEspecialidadModalLabel{{ $e->id_especialidad }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editarEspecialidadModalLabel{{ $e->id_especialidad }}">Editar
                                                            Especialidad</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('especialidades.update', $e) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre"
                                                                id="nombre" value="{{ $e->nombre }}">
                                                            <br>
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
                                                $('#editarEspecialidadModal').on('hidden.bs.modal', function() {
                                                    $(this).find('form')[0].reset();
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="mr-2">
                                        <form action="{{ route('especialidades.destroy', $e->id_especialidad) }}"
                                            method="post" id="bt-eliminar">
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
                        <td colspan="2">No hay especialidades registradas</td>
                    </tr>
                @endunless
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $especialidades->links() }}
        </div>
    </div>
@stop

@section('js')
<script>
	let botonesEliminar = document.querySelectorAll('#bt-eliminar');
	botonesEliminar.forEach(boton => {
		boton.addEventListener('click', (e) => {
			let confirmar = confirm('¿Estas seguro de eliminar este registro?');
			if (!confirmar) {
				e.preventDefault();
			}
		});
	});
</script>
@endsection
