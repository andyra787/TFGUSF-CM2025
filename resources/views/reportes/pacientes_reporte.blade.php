<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pacientes</title>
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
            }
            thead {
                display: table-header-group;
            }
            .table {
                font-size: 12px;
            }
            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mt-4 mb-2">
            <div>
                <h1>Reporte de Pacientes</h1>
            </div>
            <div class="mx-4">
                <img src="{{asset('vendor/adminlte/dist/img/usf-Photoroom.png')}}" alt="Logo" style="width: 70px;">
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Código de paciente</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Nº Documento</th>
                    <th>Edad</th>
                    <th>Departamento</th>
                    <th>Ciudad</th>
                    <th>Diagnostico</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pacientes as $p)
                    <tr>
                        <td>{{ $p->cod_paciente}}</td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->apellido }}</td>
                        <td>{{ $p->num_doc}}</td>
                        <td>{{ $p->edad }}</td>
                        <td>
                            @foreach ($departamentos as $d)
                                @if ($d->id_departamento == $p->departamento ) 
                                    {{ $d->nombre}} 
                                @endif
                            @endforeach
                        </td>
                        <td>
                        @foreach ($ciudades as $c)
                                @if ($c->id_ciudad == $p->ciudad) 
                                    {{ $c->nombre}} 
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $p->diagnostico}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botón de impresión -->
    <button onclick="imprimirReporte()" class="btn btn-primary print-button no-print">
        <i class="fas fa-print"></i> Imprimir Reporte
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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