<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 1cm;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                padding: 8px !important;
                border: 1px solid #ddd !important;
                font-size: 12px !important;
            }
            thead {
                display: table-header-group !important;
            }
            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .estado-badge {
                border: 1px solid #000;
                padding: 2px 6px;
                border-radius: 3px;
            }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }
        .estado-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .estado-pendiente {
            background-color: #ffc107;
            color: #000;
        }
        .estado-confirmada {
            background-color: #28a745;
            color: #fff;
        }
        .estado-cancelada {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mt-4 mb-2">
            <div>
                <h1>Reporte de Citas</h1>
            </div>
            <div class="mx-4">
                <img src="{{asset('vendor/adminlte/dist/img/usf-Photoroom.png')}}" alt="Logo" style="width: 70px;">
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <td>Código reserva</td>
                    <th>Paciente</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Doctor</th>
                    <th>Consultorio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $cita)
                    <tr>
                        <td>{{ $cita->id_cita }}</td>
                        <td>{{ $cita->paciente->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->fec_inicio)->format('d-m-Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->fec_fin)->format('d-m-Y H:i')  }}</td>
                        <td>
                            <span class="estado-badge estado-{{ strtolower($cita->estado) }}">
                                {{ $cita->estado }}
                            </span>
                        </td>
                        <td>{{ $cita->medico->nombre }}</td>
                        <td>{{ $cita->sala->tipo_sala }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botón de impresión -->
    <button onclick="imprimirReporte()" class="btn btn-primary print-button no-print">
        <i class="fas fa-print"></i> Imprimir Reporte
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
    
    <script>
        function imprimirReporte() {
            window.print();
        }

        // Verificar si el reporte debe imprimirse automáticamente
        @if(request()->has('imprimir_auto'))
        window.onload = function() {
            // Pequeño retraso para asegurar que todo el contenido esté cargado
            setTimeout(function() {
                window.print();
            }, 800);
        };
        @endif
    </script>
</body>
</html>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>