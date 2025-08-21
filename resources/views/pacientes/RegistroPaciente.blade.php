@extends('adminlte::page')

@section('title', 'Pacientes')

@section('plugins.Select2', true)

@section('content_header')
<h1>@unless(empty($paciente)) Editar Paciente @else Nuevo Paciente @endunless</h1>
@stop

@section('content')

<div class="mb-4 mt-2">
    <form @unless(empty($paciente)) method="POST" action="{{ route('pacientes.actualizar', $paciente->id_paciente) }}"
        @else method="POST" action="/guardar-paciente" @endunless>
        @csrf
        <div class="row">
            <div class="col-3 mb-4">
                <label for="cod_paciente">Código del Paciente:</label>
                <input type="text" name="cod_paciente" id="cod_paciente" placeholder="Codigo del paciente"
                    class="form-control" required readonly
                    value="{{$nuevo_codigo}}@unless(empty($paciente)){{$paciente->cod_paciente}}@endunless">
            </div>

            <div class="col-3"></div>

            @unless(!empty($paciente))
            <div class="col-3 mb-4">
                <span>Último código de paciente registrado: </span>
                <b>{{$cod_paciente ? $cod_paciente : 'No se encontaron registros' }}</b>
            </div>
            @endunless

            <div class="col-3"></div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="nombre">Nombres:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre(s)" class="form-control" required
                    value="@unless(empty($paciente)) {{$paciente->nombre}} @endunless"><br>
            </div>
            <div class="col-6">
                <label for="apellido">Apellidos:</label>
                <input type="text" name="apellido" id="apellido" placeholder="Apellido(s)" class="form-control" required
                    value="@unless(empty($paciente)) {{$paciente->apellido}} @endunless"><br>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <label for="num_doc">Num. Doc:</label>
                <input type="text" name="num_doc" id="num_doc" placeholder="Cedula de identidad" class="form-control"
                    required value="@unless(empty($paciente)) {{$paciente->num_doc}} @endunless"><br>
            </div>
            <div class="col-3">
                <label for="sexo">Sexo:</label>
                <select name="sexo" id="sexo" class="form-control" required>
                    <option value="">Seleccione una opción</option>
                    <option value="masculino" @unless(empty($paciente)) {{$paciente->sexo == 'masculino' ? 'selected' :
                        ''}}
                        @endunless>Masculino</option>
                    <option value="femenino" @unless(empty($paciente)) {{$paciente->sexo == 'femenino' ? 'selected' :
                        ''}}
                        @endunless>Femenino</option>
                </select>
            </div>
            <div class="col-6">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion" class="form-control" required
                    value="@unless(empty($paciente)) {{$paciente->direccion}} @endunless"> <br>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="url_maps">Url Maps:</label>
                <input type="text" name="url_maps" id="url_maps" placeholder="URL Maps" class="form-control"
                    value="@unless(empty($paciente)) {{$paciente->url_maps}} @endunless"><br>
            </div>
            <div class="col-6">
                <label for="edad">Edad:</label>
                <input type="text" name="edad" id="edad" placeholder="Edad" class="form-control" required
                    value="@unless(empty($paciente)) {{$paciente->edad}} @endunless"><br>
            </div>

        </div>

        <div class="row">
            <div class="col-6">
                <label for="departamento">Departamento:</label>
                <select name="departamento" id="departamento" class="form-control" required>
                    <option>Seleccione un Departamento</option>
                    @foreach($departamentos as $d)
                    <option value="{{ $d->id_departamento}}" @unless(empty($paciente)) {{$paciente->departamento ==
                        $d->id_departamento ? 'selected' : ''}} @endunless>{{ $d->nombre }}</option>
                    @endforeach
                </select><br>
            </div>
            <div class="col-6">
                <label for="ciudad">Ciudad:</label>
                <select name="ciudad" id="ciudad" class="form-control" @unless(!empty($paciente)) disabled @endunless>
                    <option>Seleccione una ciudad</option>
                    @unless(empty($ciudades))

                        @foreach($ciudades as $c)
                        <option value="{{ $c->id_ciudad}}" @unless(empty($paciente)) {{$paciente->ciudad ==
                            $c->id_ciudad ? 'selected' : ''}} @endunless> {{ $c->nombre }}</option>
                        @endforeach
                    @endunless
                </select><br>
            </div>
        </div>
        <div class="form-group">
            <label for="diagnostico">Diagnóstico: </label>
            <textarea name="diagnostico" id="diagnostico" cols="15" rows="5"
                class="form-control">@unless(empty($paciente)) {{$paciente->diagnostico}} @endunless</textarea>
        </div>

        <div class="form-group">
            <label for="comentario">Comentario (opcional):</label>
            <textarea name="comentario" id="comentario" cols="15" rows="5"
                class="form-control">@unless(empty($paciente)) {{$paciente->comentario}} @endunless</textarea>
        </div>
        <div class="d-flex justify-content-between">
            <a href="/pacientes" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>


    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@if(session('mensaje'))
<script>
    Swal.fire({
        title: "Se ha producido un error :(",
        text: "'{{session('mensaje')}}'",
        icon: "error"
    });
</script>
@endif
<script>

    $(document).ready(function () {
        $('.select2').select2();
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

@stop
