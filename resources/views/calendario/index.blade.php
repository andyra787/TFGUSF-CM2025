@extends('adminlte::page')

@section('title', 'Calendario de Citas')

@section('plugins.Select2', true)

@section('content_header')
    <div class="row">
        <div class="col-12">
            <h1>Calendario de Citas</h1>
        </div>
    </div>

    <div class="row">
        <!-- FILTRO DE DOCTOR -->
        <div class="col-6 mb-3">
            <span>Seleccione un doctor</span>
            <select class="form-control select2" name="doctorSelect" id="doctorSelect">
                @cannot('ver solo cita de medico')
                    <option value="all" selected>-- Ver todos los doctores --</option>
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
                @else
                    <option value='no'>-- No hay medicos para seleccionar --</option>
                @endunless
            </select>
        </div>
        <!-- FILTRO DE PACIENTE-->
        <div class="col-6 mb-3">
            <span>Seleccione un paciente</span>
            <select class="form-control select2" name="pacienteSelect" id="pacienteSelect">
                <option value="all">-- Ver todos los pacientes --</option>
                @unless(empty($pacientes))
                    @foreach($pacientes as $paciente)
                        <option value="{{$paciente->id_paciente}}">{{$paciente->nombre}} {{$paciente->apellido}} - {{$paciente->num_doc}}</option>
                    @endforeach
                @else
                    <option value='no'>-- No hay pacientes para seleccionar --</option>
                @endunless
            </select>
        </div>
        <!--FILTRO DE TIPO DE CONSULTA-->
        <div class="col-6 mb-3">
            <span>Seleccione un tipo de consulta</span>
            <select class="form-control select2" name="tipoConsultaSelect" id="tipoConsultaSelect">
                <option value="all">-- Ver todos los tipos de consulta --</option>
                @unless(empty($tipoConsultas))
                    @foreach($tipoConsultas as $tipoConsulta)
                        <option value="{{$tipoConsulta->id_tipo_consulta}}">{{$tipoConsulta->descripcion}}</option>
                    @endforeach
                @else
                    <option value='no'>-- No hay tipos de consulta para seleccionar --</option>
                @endunless
            </select>
        </div>
        <!--FILTRO DE SALA-->
        <div class="col-6 mb-3">
            <span>Seleccione una sala</span>
            <select class="form-control select2" name="salaSelect" id="salaSelect">
                <option value="all"> Ver todas las salas </option>
                @unless(empty($salas))
                    @foreach($salas as $sala)
                        <option value="{{$sala->id_sala}}">{{$sala->tipo_sala}} - {{$sala->num_sala}}</option>
                    @endforeach
                @else
                    <option value='no'> No hay salas para seleccionar </option>
                @endunless
            </select>
        </div>
    </div>
    @include('flash::message')
@stop
@section('content')
    <div id="calendar" style="background-color: #f0f8ff; padding: 20px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
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

        /* Custom styles for FullCalendar */
        .fc-toolbar {
            background-color: #3d9970;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .fc-button {
            background-color: #007bff;
            border: none;
            color: white;
            margin: 0 5px;
        }

        .fc-button:hover {
            background-color: #0056b3;
        }

        .fc-button:focus {
            box-shadow: none;
        }

        .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .fc-daygrid-event {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 5px;
            border-radius: 3px;
        }

        .fc-daygrid-event:hover {
            background-color: #0056b3;
        }

        .fc-daygrid-event-dot {
            border-color: white;
        }

        .fc-daygrid-day-number {
            color: #007bff;
        }

        .fc-day-today {
            background-color: #e9f7fe;
        }
    </style>
@stop

@section('js')
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- full calendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2();
        });
    </script>

    <!-- Script del Calendario-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendar;
            var medico_id = $('#doctorSelect').val();
            let urlCitas = '/calendario/ver';
            if (medico_id !== 'all') {
                urlCitas += '?medico_id=' + medico_id;
            }

            function updateCalendarEvents() {
                var medico_id = $('#doctorSelect').val();
                var paciente_id = $('#pacienteSelect').val();
                var tipoConsulta_id = $('#tipoConsultaSelect').val();
                var sala_id = $('#salaSelect').val();
                var url = '/calendario/ver?';

                if (medico_id !== 'all') {
                    url += 'medico_id=' + medico_id + '&';
                }

                if (paciente_id !== 'all') {
                    url += 'paciente_id=' + paciente_id + '&';
                }

                if (tipoConsulta_id !== 'all') {
                    url += 'tipo_consulta_id=' + tipoConsulta_id + '&';
                }

                if (sala_id !== 'all') {
                    url += 'sala_id=' + sala_id + '&';
                }

                // Remove the trailing '&' or '?' if no parameters were added
                url = url.slice(0, -1);

                var eventSource = calendar.getEventSourceById('mySource');
                if (eventSource) {
                    eventSource.remove(); // remove old event source
                }

                calendar.addEventSource({ // add new event source
                    url: url,
                    id: 'mySource'
                });

                calendar.refetchEvents(); // refresh events
            }

            $('#doctorSelect, #pacienteSelect, #tipoConsultaSelect, #salaSelect').on('select2:select', function() {
                updateCalendarEvents();
            });

            let calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                themeSystem: 'bootstrap',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay listWeek',
                },

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista',
                },

                allDayText: 'Todos',

                events: {
                    url: urlCitas,
                    id: 'mySource',
                },

                // Solo permitir ver detalles de citas existentes, no crear nuevas
                eventClick:function(info) {
                    let event = info.event;
                    let description = event._def.extendedProps.description;

                    Swal.fire({
                        title: "<strong>"+event._def.title+"</strong>",
                        html: description,
                        icon: "info",
                        showCloseButton: true,
                        confirmButtonText: `Cerrar`,
                    });
                }
            });

            calendar.render();
        });
    </script>
@stop
