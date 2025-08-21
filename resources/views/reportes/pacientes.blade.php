@extends('adminlte::page')

@section('title', 'Reporte de Pacientes')

@section('content')
    <div class="container">
        <br>
        @include('flash::message')
        <h1>Reporte de Pacientes</h1>

        <div class="container card p-4">
            <div class="row mb-3">
                <div class="col-auto">
                    <label for="tipo_busqueda" class="col-form-label">Buscar por: </label>
                </div>
                <div class="col-md-3">
                    <select name="tipo_busqueda" id="tipo_busqueda" class="form-select form-control">
                        <option value="nombre" @if($tipo_busqueda == 'nombre') selected @endif>Nombre</option>
                        <option value="apellido" @if($tipo_busqueda == 'apellido') selected @endif>Apellido</option>
                        <option value="num_doc" @if($tipo_busqueda == 'num_doc') selected @endif>C.I</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="search" id="busqueda_dinamica" class="form-control" placeholder="Escriba para buscar..." aria-label="search">
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="row mb-3">
                <div class="col-12 text-right">
                    <form action="{{ route('reportes.pacientes.generar') }}" target="_blank" method="post" class="d-inline">
                        @csrf
                        <input type="hidden" name="todos" value="1">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success" title="Solo generar el reporte">
                                <i class="fas fa-file-alt"></i> Generar Reporte
                            </button>
                            <button type="submit" name="imprimir_auto" value="1" class="btn btn-primary" title="Generar e imprimir automáticamente">
                                <i class="fas fa-print"></i> Generar e Imprimir
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados de la búsqueda -->
            <div id="resultados_busqueda" class="table-responsive mb-4" style="display: none;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>C.I</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="contenido_resultados">
                    </tbody>
                </table>
            </div>

            <!-- Formulario para generar reporte -->
            <form id="form_reporte" action="{{ route('reportes.pacientes.generar') }}" target="_blank" method="post">
                @csrf
                <input type="hidden" name="tipo_busqueda" id="tipo_busqueda_hidden">
                <input type="hidden" name="buscarpor" id="buscarpor_hidden">
                <div class="text-right">
                    <button type="submit" class="btn btn-primary" disabled id="btn_generar">Generar Reporte</button>
                </div>
            </form>
        </div>
    </div>

    

@stop
@section('js')
<script>
    $(document).ready(function () {
        let timeoutId;

        // Función para realizar la búsqueda
        function realizarBusqueda() {
            const tipoBusqueda = $('#tipo_busqueda').val();
            const textoBusqueda = $('#busqueda_dinamica').val();

            if (textoBusqueda.length > 0) {
                $.ajax({
                    url: '/buscar-pacientes-ajax',
                    method: 'GET',
                    data: {
                        tipo_busqueda: tipoBusqueda,
                        buscarpor: textoBusqueda
                    },
                    success: function(response) {
                        let html = '';
                        response.forEach(function(paciente) {
                            html += `
                                <tr>
                                    <td>${paciente.cod_paciente}</td>
                                    <td>${paciente.nombre}</td>
                                    <td>${paciente.apellido}</td>
                                    <td>${paciente.num_doc}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success seleccionar-paciente" 
                                                data-id="${paciente.id_paciente}"
                                                data-nombre="${paciente.nombre}"
                                                data-apellido="${paciente.apellido}">
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
                    }
                });
            } else {
                $('#resultados_busqueda').hide();
                $('#btn_generar').prop('disabled', true);
            }
        }

        // Evento al escribir en el campo de búsqueda
        $('#busqueda_dinamica').on('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(realizarBusqueda, 300); // Espera 300ms después de que el usuario deja de escribir
        });

        // Evento al cambiar el tipo de búsqueda
        $('#tipo_busqueda').on('change', function() {
            if ($('#busqueda_dinamica').val().length > 0) {
                realizarBusqueda();
            }
        });

        // Evento al seleccionar un paciente
        $(document).on('click', '.seleccionar-paciente', function() {
            const pacienteId = $(this).data('id');
            const pacienteNombre = $(this).data('nombre');
            const pacienteApellido = $(this).data('apellido');
            
            // Actualizar los campos ocultos del formulario
            $('#tipo_busqueda_hidden').val($('#tipo_busqueda').val());
            $('#buscarpor_hidden').val($('#busqueda_dinamica').val());
            
            // Habilitar el botón de generar reporte
            $('#btn_generar').prop('disabled', false);
            
            // Opcional: Mostrar un mensaje de selección
            Swal.fire({
                title: 'Paciente seleccionado',
                text: `Has seleccionado a ${pacienteNombre} ${pacienteApellido}`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    });

    $(document).ready(function () {
        $('#departamento').change(function () {
            @unless(empty($paciente))
            var departamentoId = {{ $paciente-> departamento
        }};
    @else
    var departamentoId = $(this).val();
    @endunless

    if (departamentoId) {
        $.ajax({
            type: 'GET',
            url: '/obtener-ciudades/' + departamentoId,
            success: function (data) {
                console.log("datos:", data);
                $('#ciudad').html('<option value="">Seleccione una ciudad</option>');
                $.each(data, function (ciudad_id, value) {
                    $('#ciudad').append('<option value="' + value.id_ciudad + '">' + value.nombre + '</option>');
                    console.log("Valor:", value);
                });
                $('#ciudad').prop('disabled', false);
            }
        });
    } else {
        $('#ciudad').html('<option value="">Seleccione una ciudad</option>');
        $('#ciudad').prop('disabled', true);
    }
        });
    });
</script>
@endsection