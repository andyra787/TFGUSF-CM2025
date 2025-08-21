@extends('adminlte::page')

@section('title', 'Reporte de Citas')

@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Reporte de Citas</h1>

        <div class="container card p-4">
            <div class="row mb-4">
                <div class="col-auto">
                    <label for="tipo_busqueda" class="col-form-label">Buscar por: </label>
                </div>
                <div class="col-md-3">
                    <select name="tipo_busqueda" id="tipo_busqueda" class="form-select form-control">
                        <option value="cod_reserva">Código de Reserva</option>
                        <option value="paciente">Nombre del Paciente</option>
                        <option value="medico">Nombre del Médico</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="search" id="busqueda_dinamica" class="form-control" placeholder="Escriba para buscar..." aria-label="search">
                </div>
            </div>

            <!-- Tabla de resultados de búsqueda -->
            <div id="resultados_busqueda" class="table-responsive mb-4" style="display: none;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="contenido_resultados">
                    </tbody>
                </table>
            </div>

            <!-- Formulario de filtros -->
            <form action="{{ route('reportes.citas.generar') }}" target="_blank" method="post" id="form_reporte">
                @csrf
                <input type="hidden" name="cita_id" id="cita_id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="medico">Médico</label>
                            <select name="medico" id="medico" class="form-control select2">
                                <option value="all">Todos</option>
                                @foreach($medicos as $medico)
                                    <option value="{{ $medico->id_medico }}">{{ $medico->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="paciente">Paciente</label>
                            <select name="paciente" id="paciente" class="form-control select2">
                                <option value="all">Todos</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente->id_paciente }}">{{ $paciente->nombre }} {{ $paciente->apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado">Estado de la Cita</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="all">Todos los estados</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Confirmada">Confirmada</option>
                                <option value="Cancelada">Cancelada</option>
                                <option value="Realizada">Realizada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-right mt-4">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success" title="Solo generar el reporte">
                                <i class="fas fa-file-alt"></i> Generar Reporte
                            </button>
                            <button type="submit" name="imprimir_auto" value="1" class="btn btn-primary" title="Generar e imprimir automáticamente">
                                <i class="fas fa-print"></i> Generar e Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        $('.select2').select2();
        
        let typingTimer;
        const doneTypingInterval = 300;

        // Manejar cambio en tipo de búsqueda
        $('#tipo_busqueda').on('change', function() {
            const tipoBusqueda = $(this).val();
            
            // Actualizar placeholder según tipo de búsqueda
            const placeholders = {
                'cod_reserva': 'Escriba el código de reserva...',
                'paciente': 'Escriba el nombre del paciente...',
                'medico': 'Escriba el nombre del médico...'
            };
            $('#busqueda_dinamica').attr('placeholder', placeholders[tipoBusqueda] || 'Escriba para buscar...');

            // Realizar búsqueda si hay texto
            if ($('#busqueda_dinamica').val().length > 0) {
                realizarBusqueda();
            }
        });

        // Función para realizar la búsqueda
        function realizarBusqueda() {
            const parametros = {
                tipo_busqueda: $('#tipo_busqueda').val(),
                buscarpor: $('#busqueda_dinamica').val()
            };

            if ((parametros.buscarpor && parametros.buscarpor.length > 0) || tipoBusqueda === 'fecha') {
                $.ajax({
                    url: '/buscar-citas-ajax',
                    method: 'GET',
                    data: parametros,
                    success: function(response) {
                        let html = '';
                        response.forEach(function(cita) {
                            html += `
                                <tr>
                                    <td>${cita.id_cita}</td>
                                    <td>${cita.paciente_nombre}</td>
                                    <td>${cita.medico_nombre}</td>
                                    <td>${formatearFecha(cita.fec_inicio)}</td>
                                    <td>
                                        <span class="badge badge-${getEstadoClass(cita.estado)}">
                                            ${cita.estado}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success seleccionar-cita" 
                                                data-id="${cita.id_cita}"
                                                data-paciente="${cita.paciente_id}"
                                                data-medico="${cita.medico_id}">
                                            Seleccionar
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#contenido_resultados').html(html);
                        $('#resultados_busqueda').show();
                    },
                    error: function(error) {
                        console.error('Error en la búsqueda:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al realizar la búsqueda'
                        });
                    }
                });
            } else {
                $('#resultados_busqueda').hide();
            }
        }

        // Evento al escribir en el campo de búsqueda
        $('#busqueda_dinamica').on('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(realizarBusqueda, doneTypingInterval);
        });

        // Evento al cambiar el tipo de búsqueda
        $('#tipo_busqueda').on('change', function() {
            if ($('#busqueda_dinamica').val().length > 0) {
                realizarBusqueda();
            }
        });

        // Evento al seleccionar una cita
        $(document).on('click', '.seleccionar-cita', function() {
            const citaId = $(this).data('id');
            const pacienteId = $(this).data('paciente');
            const medicoId = $(this).data('medico');
            
            // Actualizar los campos del formulario
            $('#cita_id').val(citaId);
            $('#paciente').val(pacienteId).trigger('change');
            $('#medico').val(medicoId).trigger('change');
            
            // Mostrar mensaje de éxito
            Swal.fire({
                title: 'Cita seleccionada',
                text: 'Se han actualizado los filtros con la cita seleccionada',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Funciones auxiliares
        function formatearFecha(fecha) {
            return new Date(fecha).toLocaleString('es-ES', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getEstadoClass(estado) {
            switch(estado.toLowerCase()) {
                case 'pendiente': return 'warning';
                case 'confirmada': return 'success';
                case 'cancelada': return 'danger';
                default: return 'info';
            }
        }

        // Establecer fecha actual por defecto
        const hoy = new Date().toISOString().split('T')[0];
        $('#fecha_inicio').val(hoy);
        $('#fecha_fin').val(hoy);
    });
</script>
@endsection