<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas Pendientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #343a40;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Evitar que la página principal tenga scroll */
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

        .table-container {
            flex-grow: 1;
            overflow-y: auto; /* Para el desplazamiento automático */
            padding: 0 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #343a40;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .status-pendiente {
            color: orange;
            font-weight: bold;
        }

        .status-en-proceso {
            color: blue;
            font-weight: bold;
        }

        .status-confirmada {
            color: green;
            font-weight: bold;
        }

        .status-cancelada {
            color: red;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Citas Pendientes del Día <strong>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</strong></h1>
        <div class="table-container" id="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Sala</th>
                        <th>Hora Inicio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="citas-tbody">
                    @php 
                        $orden = 1;
                    @endphp
                    @foreach($citas as $cita)
                        <tr data-hora-inicio="{{ \Carbon\Carbon::parse($cita->fec_inicio)->format('Y-m-d H:i') }}">
                            <td>{{ $orden++ }}</td>
                            <td>{{ $cita->paciente->nombre.' '.$cita->paciente->apellido }}</td>
                            <td>{{ $cita->medico->nombre }}</td>
                            <td>{{ $cita->sala->num_sala }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->fec_inicio)->format('H:i') }}</td>
                            <td class="status-{{ strtolower($cita->estado) }}">{{ ucfirst($cita->estado) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Actualizado a las {{ \Carbon\Carbon::now()->format('H:i:s') }}</p>
        </div>
    </div>

    <script>
        const container = document.getElementById('table-container');
        const citasTbody = document.getElementById('citas-tbody');
        let scrollSpeed = 1;
        let scrollInterval = 50;

        function autoScroll() {
            container.scrollBy(0, scrollSpeed);
            if (container.scrollTop + container.clientHeight >= container.scrollHeight) {
                container.scrollTop = 0; // Reiniciar el scroll cuando llegue al final
            }
        }

        function checkAndRemovePastCitas() {
            const rows = document.querySelectorAll('#citas-tbody tr');
            const now = new Date(); // Hora actual

            rows.forEach(row => {
                const horaInicio = new Date(row.getAttribute('data-hora-inicio'));

                // Si la hora de la cita ya pasó, elimina la fila
                if (horaInicio < now) {
                    row.remove();
                }
            });
        }

        // Iniciar el scroll automático
        let scrollTimer = setInterval(autoScroll, scrollInterval);

        // Chequear y eliminar filas con hora pasada cada minuto
        let removeTimer = setInterval(checkAndRemovePastCitas, 60000);

        // Chequear al inicio también
        checkAndRemovePastCitas();

        // Recargar la página cada 5 minutos (300000 milisegundos)
        setInterval(() => {
            window.location.reload();
        }, 300000); // 5 minutos
    </script>
</body>
</html>
