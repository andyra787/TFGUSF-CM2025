@extends('adminlte::page')

@section('title', 'Gestión de Citas')

@section('plugins.Select2', true)

@section('content_header')
    <div class="row">
        <div class="col-6">
            <h1>Gestión de Citas</h1>
        </div>
        <div class="col-4"></div>
        <div class="col-2">
            @can('crear citas')
            <!-- Button trigger modal -->
                <button type="button" class="btn btn-success col-12" style="background-color: #3d9970;" data-toggle="modal" data-target="#cargarCita">
                    <i class='fa fa-plus'> </i> Nueva Cita
                </button>
            @endcan
        </div>
    </div>

    <!-- Modal Nueva Cita -->
    <div class="modal fade" id="cargarCita" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="nuevaCita" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevaCita">Cargar Cita</h5>
                </div>
                <div class="modal-body d-flex justify-content-center position-relative">
                    <div class="position-absolute justify-content-center" style="top: 50%; left: 50%; z-index:5; display:none;" id="cargando">
                        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <form id="formNuevo">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="idx" value="-1" id="idx">

                            <div class="form-group col-6">
                                <label for="paciente">Paciente</label>
                                <select class="form-control" name="paciente" id="paciente" required>
                                    @unless(empty($pacientes))
                                        <option value="nn">  Seleccione un paciente </option>
                                        @foreach($pacientes as $paciente)
                                            <option value="{{$paciente->id_paciente}}">{{$paciente->nombre}} {{$paciente->apellido}} - {{$paciente->num_doc}}</option>
                                        @endforeach
                                    @else
                                        <option> No hay pacientes para seleccionar </option>
                                    @endunless
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <label for="tipoConsulta">Tipo de consulta</label>
                                <select class="form-control" name="tipo_consulta_id" id="tipoConsulta" required>
                                    @unless(empty($tipoConsultas))
                                        <option>-- Seleccione un tipo de consulta --</option>
                                        @foreach($tipoConsultas as $t)
                                            <option value="{{$t->id_tipo_consulta}}-{{$t->duracion}}">{{$t->descripcion}} - {{$t->duracion}}</option>
                                        @endforeach
                                    @else
                                        <option>-- No hay tipo de consultas para seleccionar --</option>
                                    @endunless
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <label for="doctor">Doctor</label>
                                <select class="form-control" name="doctor" id="doctor" required>
                                    @unless(empty($medicos))
                                        <option>-- Seleccione un Doctor --</option>
                                        @foreach($medicos as $medico)
                                            <option value="{{$medico->id_medico}}">{{$medico->nombre}} - {{$medico->especialidad}}</option>
                                        @endforeach
                                    @else
                                        <option>-- No hay doctores para seleccionar --</option>
                                    @endunless
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <label for="fechaHoraIni">Fecha y Hora de Inicio</label>
                                <input type="datetime-local" class="form-control" name="fechaHoraIni" id="fechaHoraIni" required>
                            </div>

                            <div class="form-group col-6">
                                <label for="sala">Sala</label>
                                <select class="form-control" name="sala" id="sala" required>
                                    @unless(empty($salas))
                                        <option>-- Seleccione una sala --</option>
                                        @foreach($salas as $sala)
                                            <option value="{{$sala->id_sala}}">{{$sala->tipo_sala}} - {{$sala->num_sala}}</option>
                                        @endforeach
                                    @else
                                        <option>-- No hay salas para seleccionar --</option>
                                    @endunless
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <label for="fechaHoraFin">Fecha y Hora de Fin</label>
                                <input type="datetime-local" class="form-control" name="fechaHoraFin" id="fechaHoraFin" required disabled>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" name="entreCitas" id="entreCitas">
                                    <label class="form-check-label" for="entreCitas">Es consulta entre citas</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="notas">Notas</label>
                                <textarea class="form-control" name="notas" id="notas" cols="30" rows="5" required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnCancelar" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnGuardar" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @include('flash::message')
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Citas</h3>
            <div class="card-tools">
                <a href="{{ route('calendario.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-calendar-alt"></i> Ver Calendario
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <!-- FILTRO DE DOCTOR -->
                <div class="col-md-2">
                    <label>Doctor</label>
                    <select class="form-control select2" name="doctorFilter" id="doctorFilter">
                        @cannot('ver solo cita de medico')
                            <option value="all" selected>-- Todos los doctores --</option>
                        @endcannot
                        @unless(empty($medicos))
                            @foreach($medicos as $medico)
                                @can('ver solo cita de medico')
                                    @if(auth()->user()->doc_id == $medico->id_medico)
                                        <option value="{{$medico->id_medico}}" selected>{{$medico->nombre}} - {{$medico->especialidad}}</option>
                                    @endif
                                @else
                                    <option value="{{$medico->id_medico}}">{{$medico->nombre}} - {{$medico->especialidad}}</option>
                                @endcan
                            @endforeach
                        @endunless
                    </select>
                </div>
                <!-- FILTRO DE PACIENTE -->
                <div class="col-md-2">
                    <label>Paciente</label>
                    <select class="form-control select2" name="pacienteFilter" id="pacienteFilter">
                        <option value="all">-- Todos los pacientes --</option>
                        @unless(empty($pacientes))
                            @foreach($pacientes as $paciente)
                                <option value="{{$paciente->id_paciente}}">{{$paciente->nombre}} {{$paciente->apellido}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                <!-- FILTRO DE CÉDULA -->
                <div class="col-md-2">
                    <label>Cédula</label>
                    <input type="text" class="form-control" name="cedulaFilter" id="cedulaFilter" placeholder="Buscar por cédula...">
                </div>
                <!-- FILTRO DE ESTADO -->
                <div class="col-md-2">
                    <label>Estado</label>
                    <select class="form-control" name="estadoFilter" id="estadoFilter">
                        <option value="all">-- Todos los estados --</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="atendido">Atendido</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
                <!-- FILTRO DE FECHA -->
                <div class="col-md-2">
                    <label>Fecha</label>
                    <input type="date" class="form-control" name="fechaFilter" id="fechaFilter">
                </div>
                <!-- SELECTOR DE REGISTROS POR PÁGINA -->
                <div class="col-md-2">
                    <label>Mostrar</label>
                    <select class="form-control" name="perPageFilter" id="perPageFilter">
                        <option value="10">10 registros</option>
                        <option value="25">25 registros</option>
                        <option value="50">50 registros</option>
                        <option value="100">100 registros</option>
                    </select>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="citasTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Cédula</th>
                            <th>Doctor</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Sala</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las citas se cargarán aquí via AJAX -->
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div id="paginationInfo" class="pagination-info"></div>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Page navigation">
                        <ul id="pagination" class="pagination justify-content-end"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            color: #495057;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
        }

        .badge {
            font-size: 0.85em;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }
    </style>
@stop

@section('js')
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2();
            
            // Configurar Select2 en el modal
            $('#cargarCita').on('shown.bs.modal', function() {
                $(this).removeAttr('tabindex');
                $('#doctor').select2();
                $('#sala').select2();
                $('#paciente').select2();
                $('#tipoConsulta').select2();
            });

            // Cargar citas al iniciar
            cargarCitas(1);

            // Event listeners para filtros
            $('#doctorFilter, #pacienteFilter, #estadoFilter, #fechaFilter, #perPageFilter').on('change', function() {
                cargarCitas(1);
            });

            // Event listener para búsqueda por cédula con delay
            let timeoutId;
            $('#cedulaFilter').on('input', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(function() {
                    cargarCitas(1);
                }, 500); // Esperar 500ms después de que el usuario deje de escribir
            });
        });

        let currentPage = 1;

        function cargarCitas(page = 1) {
            currentPage = page;
            
            var medico_id = $('#doctorFilter').val() || 'all';
            var paciente_id = $('#pacienteFilter').val() || 'all';
            var estado = $('#estadoFilter').val() || 'all';
            var fecha = $('#fechaFilter').val() || '';
            var cedula = $('#cedulaFilter').val() || '';
            var perPage = $('#perPageFilter').val() || 10;

            var params = {
                medico_id: medico_id,
                paciente_id: paciente_id,
                estado: estado,
                fecha: fecha,
                cedula: cedula,
                page: page,
                per_page: perPage
            };

            $.ajax({
                url: '/citas/listar',
                method: 'GET',
                data: params,
                success: function(response) {
                    var tbody = $('#citasTable tbody');
                    tbody.empty();

                    if (response && response.data && Array.isArray(response.data)) {
                        response.data.forEach(function(cita) {
                            var estadoBadge = '';
                            switch(cita.estado) {
                                case 'Pendiente':
                                    estadoBadge = '<span class="badge badge-warning">Pendiente</span>';
                                    break;
                                case 'atendido':
                                    estadoBadge = '<span class="badge badge-success">Atendido</span>';
                                    break;
                                case 'Cancelado':
                                    estadoBadge = '<span class="badge badge-danger">Cancelado</span>';
                                    break;
                                default:
                                    estadoBadge = '<span class="badge badge-info">' + cita.estado + '</span>';
                                    break;
                            }

                            var acciones = '';
                            @can('modificar citas')
                            if(cita.estado !== 'atendido') {
                                acciones += '<button class="btn btn-sm btn-primary me-1" onclick="editarCita(' + cita.id_cita + ')" title="Editar cita"><i class="fas fa-edit"></i></button>';
                            }
                            @endcan
                            
                            if(cita.estado === 'Pendiente') {
                                acciones += '<button class="btn btn-sm btn-success me-1" onclick="marcarAtendido(' + cita.id_cita + ')" title="Marcar como atendido"><i class="fas fa-check"></i></button>';
                            }

                            var row = '<tr>' +
                                '<td>' + cita.id_cita + '</td>' +
                                '<td>' + cita.paciente + '</td>' +
                                '<td>' + cita.cedula + '</td>' +
                                '<td>' + cita.doctor + '</td>' +
                                '<td>' + cita.fec_inicio + '</td>' +
                                '<td>' + cita.fec_fin + '</td>' +
                                '<td>' + cita.sala + '</td>' +
                                '<td>' + estadoBadge + '</td>' +
                                '<td>' + acciones + '</td>' +
                            '</tr>';
                            
                            tbody.append(row);
                        });

                        // Actualizar información de paginación
                        updatePaginationInfo(response);
                        // Generar controles de paginación
                        generatePagination(response);
                    } else {
                        tbody.append('<tr><td colspan="9" class="text-center">No se encontraron citas</td></tr>');
                        $('#paginationInfo').html('');
                        $('#pagination').html('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar citas:', error);
                    var tbody = $('#citasTable tbody');
                    tbody.empty();
                    tbody.append('<tr><td colspan="9" class="text-center text-danger">Error al cargar las citas: ' + error + '</td></tr>');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar las citas: ' + error
                    });
                }
            });
        }

        function updatePaginationInfo(response) {
            var info = '';
            if (response.total > 0) {
                info = 'Mostrando ' + response.from + ' a ' + response.to + ' de ' + response.total + ' registros';
            } else {
                info = 'No se encontraron registros';
            }
            $('#paginationInfo').html(info);
        }

        function generatePagination(response) {
            var pagination = $('#pagination');
            pagination.empty();
            
            if (response.last_page <= 1) {
                return; // No mostrar paginación si solo hay una página
            }

            // Botón Anterior
            if (response.current_page > 1) {
                pagination.append('<li class="page-item"><a class="page-link" href="#" onclick="cargarCitas(' + (response.current_page - 1) + ')">Anterior</a></li>');
            }

            // Números de página
            var startPage = Math.max(1, response.current_page - 2);
            var endPage = Math.min(response.last_page, response.current_page + 2);

            if (startPage > 1) {
                pagination.append('<li class="page-item"><a class="page-link" href="#" onclick="cargarCitas(1)">1</a></li>');
                if (startPage > 2) {
                    pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
                }
            }

            for (var i = startPage; i <= endPage; i++) {
                var activeClass = (i === response.current_page) ? ' active' : '';
                pagination.append('<li class="page-item' + activeClass + '"><a class="page-link" href="#" onclick="cargarCitas(' + i + ')">' + i + '</a></li>');
            }

            if (endPage < response.last_page) {
                if (endPage < response.last_page - 1) {
                    pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
                }
                pagination.append('<li class="page-item"><a class="page-link" href="#" onclick="cargarCitas(' + response.last_page + ')">' + response.last_page + '</a></li>');
            }

            // Botón Siguiente
            if (response.current_page < response.last_page) {
                pagination.append('<li class="page-item"><a class="page-link" href="#" onclick="cargarCitas(' + (response.current_page + 1) + ')">Siguiente</a></li>');
            }
        }

        function editarCita(id) {
            fetch('/citas/editar/' + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('idx').value = data.id_cita;
                document.getElementById('doctor').value = data.medico_id;
                document.getElementById('fechaHoraIni').value = data.fec_inicio.slice(0, 16);
                document.getElementById('fechaHoraFin').value = data.fec_fin.slice(0, 16);
                document.getElementById('sala').value = data.sala_id;
                document.getElementById('paciente').value = data.paciente_id;
                
                let tipoConsultaOptions = document.getElementById('tipoConsulta').options;
                for (let i = 0; i < tipoConsultaOptions.length; i++) {
                    let optionValue = tipoConsultaOptions[i].value.split('-')[0];
                    if (optionValue == data.tipo_consulta_id) {
                        document.getElementById('tipoConsulta').selectedIndex = i;
                        break;
                    }
                }
                document.getElementById('entreCitas').checked = data.entreCitas;
                document.getElementById('notas').value = data.observaciones;
                
                $('#cargarCita').modal('show');
            })
            .catch(err => console.log(err));
        }

        function marcarAtendido(id) {
            Swal.fire({
                title: '¿Marcar como atendido?',
                text: "Esta acción marcará la cita como atendida",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, marcar como atendido',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/citas/atendido/' + id, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: data || "Cita actualizada correctamente",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        cargarCitas();
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar la cita: ' + err.message
                        });
                    });
                }
            });
        }

        // Configuración de fecha mínima
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        
        document.getElementById('fechaHoraIni').min = minDateTime;

        // Validar fechas pasadas
        document.getElementById('fechaHoraIni').addEventListener('change', function (e) {
            if (this.value < this.min) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hubo un Error',
                    text: 'No puedes seleccionar fechas pasadas',
                });
                this.value = '';
            } else {
                // Actualizar fecha fin basada en el tipo de consulta
                let tipoConsulta = document.getElementById('tipoConsulta').value;
                document.getElementById('fechaHoraFin').value = sumMinutes(this.value, tipoConsulta);
            }
        });

        // Actualizar fecha fin cuando cambia el tipo de consulta
        document.getElementById('tipoConsulta').addEventListener('change', function() {
            let fechaIni = document.getElementById('fechaHoraIni').value;
            if (fechaIni) {
                document.getElementById('fechaHoraFin').value = sumMinutes(fechaIni, this.value);
            }
        });

        function sumMinutes(fecha, duracion) {
            let fechaDate = new Date(fecha);
            let nuevaDuracion;

            if (duracion === '-- Seleccione un tipo de consulta --') {
                nuevaDuracion = 30;
            } else {
                nuevaDuracion = parseInt(duracion.split('-')[1]);
            }

            fechaDate.setMinutes(fechaDate.getMinutes() + nuevaDuracion);

            let anio = fechaDate.getFullYear();
            let mes = ("0" + (fechaDate.getMonth() + 1)).slice(-2);
            let dia = ("0" + fechaDate.getDate()).slice(-2);
            let horas = ("0" + fechaDate.getHours()).slice(-2);
            let minutos = ("0" + fechaDate.getMinutes()).slice(-2);

            return `${anio}-${mes}-${dia}T${horas}:${minutos}`;
        }

        // Guardar cita
        document.getElementById('btnGuardar').addEventListener('click', function() {
            let id = document.getElementById('idx').value;
            let doctor = document.getElementById('doctor').value;
            let fechaIni = document.getElementById('fechaHoraIni').value;
            let fechaFin = document.getElementById('fechaHoraFin').value;
            let sala = document.getElementById('sala').value;
            let paciente = document.getElementById('paciente').value;
            let tipo = document.getElementById('tipoConsulta').value.split('-')[0];
            let entreCitas = document.getElementById('entreCitas').checked;
            let notas = document.getElementById('notas').value;

            let datos = {
                medico_id: doctor,
                paciente_id: paciente,
                fec_inicio: fechaIni,
                fec_fin: fechaFin,
                estado: 'Pendiente',
                sala_id: sala,
                tipo_consulta_id: tipo,
                entreCitas: entreCitas,
                observaciones: notas,
            };

            let url = '/citas';
            if(id !== '-1') {
                url = '/citas/actualizar';
                datos.id_cita = id;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(datos),
            })
            .then(res => res.json())
            .then(data => {
                if(data === 'Cita creada correctamente' || data === 'Cita actualizada correctamente') {
                    $('#cargarCita').modal('hide');
                    document.getElementById('formNuevo').reset();
                    document.getElementById('idx').value = '-1';
                    cargarCitas();
                    
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: data,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la solicitud'
                });
            });
        });

        // Cancelar edición
        document.getElementById('btnCancelar').addEventListener('click', function() {
            document.getElementById('formNuevo').reset();
            document.getElementById('idx').value = '-1';
        });
    </script>
@stop
