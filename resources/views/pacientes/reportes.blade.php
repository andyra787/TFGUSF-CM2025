@extends('adminlte::page')

@section('title', 'Pacientes')

@section('content')
    <div class="container">
        <!-- Gráfico por Mes -->
        <div class="row">
            <div class="col-10">
                <canvas id="graficoPorMes"></canvas>
            </div>
        </div>

        <!-- Gráfico por Ciudad -->
        <div class="row mt-4">
            <div class="col-10">
                <canvas id="graficoPorCiudad"></canvas>
            </div>
        </div>

        <!-- Gráfico de Dona por Género -->
        <div class="row mt-4">
            <div class="col-md-6" style="height: 230px;">
                <canvas id="graficoPorGenero"></canvas>
            </div>
        </div>
         <!-- Gráfico de Barras para Promedio de Edad -->
        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="graficoPromedioEdad"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var pacientes = @json($pacientes);

        // Gráfico por Mes
        var datosPorMes = {};
        pacientes.forEach(function (paciente) {
            var fechaCreacion = new Date(paciente.created_at);
            var mesNumerico = fechaCreacion.getMonth() + 1;
            var claveMes = `${mesNumerico}/${fechaCreacion.getFullYear()}`;
            datosPorMes[claveMes] = (datosPorMes[claveMes] || 0) + 1;
        });

        var etiquetasPorMes = Object.keys(datosPorMes).map(function (etiqueta) {
            var [mes, anio] = etiqueta.split('/');
            return new Date(anio, mes - 1).toLocaleDateString('es-ES', { year: 'numeric', month: 'long' });
        });

        var datosPorMesArray = Object.values(datosPorMes);

        var datosGraficoPorMes = {
            labels: etiquetasPorMes,
            datasets: [{
                label: 'Pacientes por Mes',
                data: datosPorMesArray,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var valorMaximoPorMes = Math.max(...datosPorMesArray);

        var opcionesGraficoPorMes = {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                    max: valorMaximoPorMes + 1
                }
            }
        };

        // Gráfico por Ciudad
        var datosPorCiudad = {};
        pacientes.forEach(function (paciente) {
            var claveCiudad = paciente.ciudad;
            datosPorCiudad[claveCiudad] = (datosPorCiudad[claveCiudad] || 0) + 1;
        });

        var etiquetasPorCiudad = Object.keys(datosPorCiudad);
        var datosPorCiudadArray = Object.values(datosPorCiudad);

        var datosGraficoPorCiudad = {
            labels: etiquetasPorCiudad,
            datasets: [{
                label: 'Pacientes por Ciudad',
                data: datosPorCiudadArray,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var valorMaximoPorCiudad = Math.max(...datosPorCiudadArray);

        var opcionesGraficoPorCiudad = {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                    max: valorMaximoPorCiudad + 1
                }
            }
        };

        // Gráfico de Dona por Género
        var datosPorGenero = {};
        pacientes.forEach(function (paciente) {
            var genero = paciente.sexo;
            datosPorGenero[genero] = (datosPorGenero[genero] || 0) + 1;
        });

        var etiquetasPorGenero = Object.keys(datosPorGenero);
        var datosPorGeneroArray = Object.values(datosPorGenero);

        var datosGraficoPorGenero = {
            labels: etiquetasPorGenero,
            datasets: [{
                data: datosPorGeneroArray,
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        };

        // Obtener el elemento canvas y renderizar los gráficos
        var contextoPorMes = document.getElementById('graficoPorMes').getContext('2d');
        var graficoPorMes = new Chart(contextoPorMes, {
            type: 'line',
            data: datosGraficoPorMes,
            options: opcionesGraficoPorMes
        });

        var contextoPorCiudad = document.getElementById('graficoPorCiudad').getContext('2d');
        var graficoPorCiudad = new Chart(contextoPorCiudad, {
            type: 'line',
            data: datosGraficoPorCiudad,
            options: opcionesGraficoPorCiudad
        });

        var contextoPorGenero = document.getElementById('graficoPorGenero').getContext('2d');
        var graficoPorGenero = new Chart(contextoPorGenero, {
            type: 'doughnut',
            data: datosGraficoPorGenero
        });

        // Gráfico de Barras para Promedio de Edad
        // Obtener las edades por género
        var edadesMasculinas = pacientes.filter(function (paciente) {
            return paciente.sexo === 'Masculino';
        }).map(function (paciente) {
            return +paciente.edad;
        });

        var edadesFemeninas = pacientes.filter(function (paciente) {
            return paciente.sexo === 'Femenino';
        }).map(function (paciente) {
            return +paciente.edad;
        });

        // Calcular los promedios de edad por género
        var promedioEdadMasculino = edadesMasculinas.reduce(function (acc, edad) {
            return acc + edad;
        }, 0) / edadesMasculinas.length;

        var promedioEdadFemenino = edadesFemeninas.reduce(function (acc, edad) {
            return acc + edad;
        }, 0) / edadesFemeninas.length;

        // Crear el gráfico de barras para promedio de edad
        var datosGraficoPromedioEdad = {
            labels: ['Promedio de Edad Masculino', 'Promedio de Edad Femenino'],
            datasets: [{
                label: 'Masculino',
                data: [promedioEdadMasculino, 0], // El segundo valor es 0 para alinear las barras correctamente
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Femenino',
                data: [0, promedioEdadFemenino], // El primer valor es 0 para alinear las barras correctamente
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var opcionesGraficoPromedioEdad = {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        };

        var contextoPromedioEdad = document.getElementById('graficoPromedioEdad').getContext('2d');
        var graficoPromedioEdad = new Chart(contextoPromedioEdad, {
            type: 'bar',
            data: datosGraficoPromedioEdad,
            options: opcionesGraficoPromedioEdad
        });

    </script>
@endsection
