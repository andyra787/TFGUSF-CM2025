@extends('adminlte::page')
@section('title', 'Salas')
@section('content')
<div class="container">
	<br>
	@include('flash::message')
	<h1>Lista de Salas</h1>

	<div class="row g-2">
		<!--buscador -->
		<form action="{{ route('salas.index') }}" method="GET">
			<div class="row g-2">
				<div class="col-auto">
					<input type="search" name="buscarpor" class="form-control" value="{{$buscarpor ? $buscarpor : ''}}" placeholder="Buscar por nombre"
						aria-label="search">
				</div>
				<div class="col-auto">
					<button class="btn btn-info mb-2" type="submit">Buscar</button>
				</div>
			</div>
		</form>

		<!-- boton nuevo -->
		<div class="col-auto ml-auto">
			<button type="button" class="btn btn-primary" data-toggle="modal"
				data-target="#nuevaSalaModal">Nuevo</button>
		</div>
	</div>

	<!-- Modal para crear nueva sala -->
	<div class="modal fade" id="nuevaSalaModal" tabindex="-1" role="dialog" data-backdrop="static"
		aria-labelledby="nuevaSalaModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="nuevaSalaModalLabel">Nuevo Medico</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{{ route('salas.store') }}" method="POST">
						@csrf
						<div class="form-group">
							<label for="tipo_sala">Tipo Sala</label>
							<input type="text" class="form-control" id="tipo_sala" name="tipo_sala" required>
							<br>
							<label for="num_sala">Número de Sala</label>
							<input type="text" class="form-control" id="num_sala" name="num_sala" required>
							<br>

							<div class="d-flex justify-content-between">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
								<button type="submit" class="btn btn-success">Guardar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<br>

	<div class="row">
		<!-- <a href="{{ route('salas.create') }}" class="btn btn-primary mb-3">Nueva Sala</a> -->
		<div class="table-responsive">
			<table class="table table-striped table-border" id="tabla">
				<thead>
					<tr>
						<th>ID</th>
						<th>Tipo de Sala</th>
						<th>Numero de Sala</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@unless(empty($salas[0]))
						@foreach($salas as $sala)
						<tr>
							<td>{{ $sala->id_sala }}</td>
							<td>{{ $sala->tipo_sala }}</td>
							<td>{{ $sala->num_sala }}</td>
							<td>
								
								{{-- boton editar --}}
								<button type="button" class="btn btn-warning" data-toggle="modal"
									data-target="#editarSala{{ $sala->id_sala }}">Editar</button>

								<!-- Modal para editar medico -->
								<div class="modal fade" id="editarSala{{ $sala->id_sala }}" tabindex="-1"
									role="dialog" data-backdrop="static"
									aria-labelledby="editarSalaLabel{{ $sala->id_sala }}" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="editarSalaLabel{{ $sala->id_sala }}">
													Editar Sala</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<form action="{{ route('salas.update', $sala) }}" method="POST">
													@csrf
													@method('PATCH')
													<div class="form-group">
														<label for="tipo_sala">Tipo Sala</label>
														<input type="text" class="form-control" id="tipo_sala" name="tipo_sala" value="{{ $sala->tipo_sala }}" required>
														<br>
														<label for="num_sala">Número de Sala</label>
														<input type="text" class="form-control" id="num_sala" name="num_sala" value="{{ $sala->num_sala }}" required>
														<br>

														<div class="d-flex justify-content-between">
															<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
															<button type="submit" class="btn btn-success">Guardar</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>

								{{-- boton eliminar --}}
								<form action="{{ route('salas.destroy', $sala->id_sala) }}" method="POST"
									style="display: inline-block;">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-danger" id="bt-eliminar">Eliminar</button>
								</form>

							</td>
						</tr>
						@endforeach
					@else
						<tr class="text-center">
							<td colspan="4">No hay registros que mostrar</td>
						</tr>
					@endunless
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

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

	$(document).ready(function () {
			$('#editarSala').on('hidden.bs.modal', function () {
				$(this).find('form')[0].reset();
			});
		});
</script>
@endsection