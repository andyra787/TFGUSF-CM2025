@extends('adminlte::page')

@section('title', 'Doctores')

@section('content')
<div class="container">
	<br>
	@include('flash::message')
	<h1>Lista de Doctores</h1>
	<div class="row g-2">
		<form action="{{ route('medicos.index') }}" method="GET">
			<div class="row g-2">
				<!--buscador -->
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
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevoMedicoModal">Nuevo</button>
		</div>
	</div>

	<!-- Modal para crear nuevo medico -->
	<div class="modal fade" id="nuevoMedicoModal" tabindex="-1" role="dialog" data-backdrop="static"
		aria-labelledby="nuevoMedicoModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="nuevoMedicoModalLabel">Nuevo Medico</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{{ route('medicos.store') }}" method="POST">
						@csrf
						<div class="form-group">
							<label for="nombre">Nombre y Apellido</label>
							<input type="text" class="form-control" id="nombre" name="nombre" required>
							<br>
							<label for="ci">Cédula</label>
							<input type="text" class="form-control" id="ci" name="ci" required>
							<br>
							<label for="email">Correo Electrónico</label>
							<input type="email" class="form-control" id="email" name="email" required>
							<br>
							<label for="telefono">Teléfono</label>
							<input type="text" class="form-control" id="telefono" name="telefono" required>
							<br>
							<label for="registro">Registro</label>
							<input type="text" class="form-control" id="registro" name="registro" required>
							<br>

							<div class="form-group col-md-13">
								<label for="especialidad_id">Especialidad</label><br>
								<select class="custom-select" aria-label="Default select example" name="especialidad_id"
									required>
									<option value="" selected>Seleccione una especialidad</option>
									@foreach ($especialidades as $e)
									<option value="{{ $e->id_especialidad }}">{{ $e->nombre }}</option>
									@endforeach
								</select>
							</div>
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

	<script>
		$(document).ready(function () {
			$('#nuevoMedicoModal').on('hidden.bs.modal', function () {
				$(this).find('form')[0].reset();
			});
		});
	</script>
	<br>
	<!-- tabla -->
	<div class="table-responsive">
		<table class="table table-striped table-border" id="tabla">
			<thead>
				<tr>
					<th scope="col">Nombre y Apellido</th>
					<th scope="col">Cedula</th>
					<th scope="col">Correo Electrónico</th>
					<th scope="col">Teléfono</th>
					<th scope="col">Registro</th>
					<th scope="col">Especialidad</th>
					<th scope="col">Estado</th>
					<th scope="col">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@unless(empty($medicos[0]))
					@foreach ($medicos as $m)
					<tr>
						<td>{{ $m->nombre }}</td>
						<td>{{ $m->ci }}</td>
						<td>{{ $m->email }}</td>
						<td>{{ $m->telefono }}</td>
						<td>{{ $m->registro }}</td>
						<td>{{ $m->especialidad->nombre }}</td>
						<td>
							@if ($m->estado == 1)
							<span class="badge badge-success">Activo</span>
							@else
							<span class="badge badge-danger">Inactivo</span>
							@endif
						<td>
							<div class="btn-group">
								<div class="mr-2">
									{{-- boton editar --}}
									<button type="button" class="btn btn-warning" data-toggle="modal"
										data-target="#editarMedicoModal{{ $m->id_medico }}">Editar</button>

									<!-- Modal para editar medico -->
									<div class="modal fade" id="editarMedicoModal{{ $m->id_medico }}" tabindex="-1"
										role="dialog" data-backdrop="static"
										aria-labelledby="editarMedicoModalLabel{{ $m->id_medico }}" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="editarMedicoModalLabel{{ $m->id_medico }}">
														Editar Doctor</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<form action="{{ route('medicos.update', $m) }}" method="POST">
														@csrf
														@method('PATCH')
														<label for="nombre">Nombre Completo</label>
														<input type="text" class="form-control" name="nombre" id="nombre"
															value="{{ $m->nombre }}">
														<br>

														<label for="ci">Cédula</label>
														<input type="text" class="form-control" name="ci" id="ci"
															value="{{ $m->ci }}">
														<br>

														<label for="email">Correo Electrónico</label>
														<input type="email" class="form-control" name="email" id="email"
															value="{{ $m->email }}">
														<br>

														<label for="telefono">Teléfono</label>
														<input type="text" class="form-control" name="telefono" id="telefono"
															value="{{ $m->telefono }}">
														<br>

														<label for="registro">Registro</label>
														<input type="text" class="form-control" name="registro" id="registro"
															value="{{ $m->registro }}">
														<br>

														<label for="especialidad_id">Especialidad</label><br>
														<select class="custom-select" aria-label="Default select example"
															name="especialidad_id">
															@foreach ($especialidades as $e)
															<option value="{{ $e->id_especialidad }}">
																{{ $e->nombre }}
															</option>
															@endforeach
														</select>
														<br><br>
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
										$(document).ready(function () {
											$('#editarMedicoModal').on('hidden.bs.modal', function () {
												$(this).find('form')[0].reset();
											});
										});
									</script>
								</div>

								<div class="mr-2">
									<form action="{{ route('medicos.update', $m->id_medico) }}" method="POST">
										@csrf
										@method('PUT')
										<input type="hidden" name="estado" value="{{ $m->estado ? '0' : '1' }}">
										<button type="submit" class="btn btn-{{ $m->estado ? 'danger' : 'success' }}">
											{{ $m->estado ? 'Desactivar' : 'Activar' }}
										</button>
									</form>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
				@else
					<tr class="text-center">
						<td colspan="8">No hay registros</td>
					</tr>
				@endunless
			</tbody>
		</table>
		<div class="d-flex justify-content-end">
			{{ $medicos->links() }}
		</div>
	</div>
</div>

@stop