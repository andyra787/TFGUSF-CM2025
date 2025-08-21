@extends('adminlte::page')

@section('title', 'Pacientes')

@section('content_header')
<h1>Paciente</h1>
@stop

@section('content')
            
    <form method="POST" action="/guardar-paciente">
            @csrf
            <div class="row">
                <div class="col-3 mb-4">
                    <input type="text" name="cod_paciente" id="cod_paciente"  value="{{$datos['cod_paciente']}}" placeholder="Codigo del paciente" class="form-control" required> <br>
                </div>
                <div class="col-3 mb-4">
                    <span>Último codigo usado: </span>
                    <b>@if($paciente !=null){{$paciente->cod_paciente}} @else {no se encontaron registros} @endif</b>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <input type="text" name="nombre" id="nombre" value="{{$datos['nombre']}}" placeholder="Nombre(s)" class="form-control" required><br>
                </div>
                <div class="col-6">
                    <input type="text" name="apellido" id="apellido" value="{{$datos['apellido']}}" placeholder="Apellido(s)" class="form-control" required><br>
                </div >
            </div>

            <div class="row">
                <div class="col-3">
                    <input type="text" name="num_doc" id="num_doc" value="{{$datos['num_doc']}}" placeholder="Cedula de identidad" class="form-control"required><br>
                </div>
                <div class="col-3">
                    <select name="sexo" id="sexo" class="form-control" required>
                        <option value="">Sexo</option>
                        <option value="masculino" <?php if($datos['sexo'] == 'masculino'){print('selected');} ?>>Masculino</option>
                        <option value="femenino" <?php if($datos['sexo'] == 'femenino'){print('selected');} ?>>Femenino</option>
                    </select>
                </div>
                <div class="col-6">
                    <input type="text" name="direccion" id="direccion" value="{{$datos['direccion']}}" placeholder="Direccion" class="form-control" required> <br>
                </div>
            </div>

            <div class="row">
                <div class="col-6"> 
                    <input type="text" name="url_maps" id="url_maps" value="{{$datos['url_maps']}}" placeholder="URL Maps" class="form-control"><br>
                </div>
                <div class="col-6"> 
                    <input type="text" name="edad" id="edad" value="{{$datos['edad']}}" placeholder="Edad" class="form-control" required><br>
                </div>

            </div>

            <div class="row">
                <div class="col-6">
                    <select name="departamento" id="departamento" class="form-control" required>
                        <option value="">Departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_departamento}}" <?php if($datos['departamento'] == $departamento->id_departamento){print('selected');} ?> >{{ $departamento->nombre }}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="col-6">
                    <select name="ciudad" id="ciudad" class="form-control">
                        @foreach($ciudad as $c)
                        <option value="{{ $c->id_ciudad}}" <?php if($datos['ciudad'] == $c->id_ciudad){print('selected');} ?>>{{ $c->nombre }}</option>
                        @endforeach
                    </select><br>
                </div>
            </div>
                <input type="hidden" name="estado" id="estado" value="activo">
            <div class="form-group">
                <label for="diagnostico">Diagnostico: </label>
                <textarea name="diagnostico" id="diagnostico" cols="15" rows="5" class="form-control">{{$datos['diagnostico']}}</textarea>
            </div>

            <div class="form-group">
                <label for="comentario">Comentario: </label>
                <textarea name="comentario" id="comentario"  cols="15" rows="5" class="form-control">{{$datos['comentario']}}</textarea>
            </div>
            <div class="form-group mb-4">
                <input type="submit" class="btn btn-primary" value="Guardar">
                <a href="/pacientes" class="btn btn-danger">Cancelar</a>
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
                        });</script>
        @endif
<script>
    $(document).ready(function () {
        $('#departamento').change(function () {
            var departamentoId = $(this).val();
            
            if (departamentoId) {
                $.ajax({
                    type: 'GET',
                    url: '/obtener-ciudades/' + departamentoId,
                    success: function (data) {
                        var selectCiudad = $('#ciudad');
                        console.log("datos:", data);
                        $('#ciudad').html('<option value="">Seleccione una ciudad</option>');
                        $.each(data, function (ciudad_id, value) {
                            $('#ciudad').append('<option  value="' + value.id_ciudad + '">'+ value.nombre + '</option>');
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