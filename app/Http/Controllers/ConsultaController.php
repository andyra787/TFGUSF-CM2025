<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Sala;
use App\Models\TipoConsulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;

class ConsultaController extends Controller
{
    public function index(Request $request)
{
    $query = $request->get('cita');
    $cita = [];
    if ($query) {
        $cita = Cita::where('id_cita', $query)->first();
        if ($cita && $cita->medico_id != auth()->user()->doc_id) {
            Flash::error('No tienes permiso para ver esta cita');
            return redirect()->route('citas.index');
        }
        if ($cita->estado == 'atendido') {
            Flash::error('La cita ya fue atendida');
            return redirect()->route('citas.index');
        }
    }

    // Obtener parámetros de búsqueda
    $tipoBusqueda = $request->get('tipo_busqueda', 'nombre'); // Por defecto, buscar por nombre
    $buscarPor = $request->get('buscarpor', '');

    // Filtrar consultas
    $pacientesIds = Paciente::when($buscarPor, function ($query) use ($tipoBusqueda, $buscarPor) {
        $query->where($tipoBusqueda, 'LIKE', "%{$buscarPor}%");
    })
    ->pluck('id_paciente');

$consultas = Consulta::where('medico_id', auth()->user()->doc_id)
    ->whereIn('paciente_id', $pacientesIds)
    ->get();


    return view('consultas.index', compact('cita', 'query', 'consultas', 'tipoBusqueda', 'buscarPor'));
}


    // Mostrar consultas por algún criterio (ejemplo: por médico o por paciente)
    public function show(Request $request)
    {
        $id = $request->query('medico_id'); // Puedes cambiar la condición si es necesario

        if ($id) {
            $consultas = Consulta::where('medico_id', $id)->get();
        } else {
            $consultas = Consulta::where('medico_id', 1)->get(); // Condición por defecto
        }

        $consultasNuevo = [];

        foreach ($consultas as $clave => $valor) {
            $paciente = Paciente::find($valor->paciente_id);
            $medico = Medico::find($valor->medico_id);
            $sala = Sala::find($valor->sala_id);
            $tipoConsulta = TipoConsulta::find($valor->tipo_consulta_id);
            $cita = Cita::find($valor->cita_id); // Relación con cita

            $consultasNuevo[$clave] = [
                'title' => $valor->id_consulta.' - '.$paciente->nombre.' '.$paciente->apellido,
                'description' => '<b>Síntomas: </b>'.$valor->sintomas.'<br>
                <b>Diagnóstico: </b>'.$valor->diagnostico.'<br>
                <b>Tratamiento: </b>'.$valor->tratamiento.'<br>
                <b>Doctor:</b> '.$medico->nombre.'<br>
                <b>N° de Sala:</b> '.$sala->num_sala.'<br>
                <b>Tipo de Consulta:</b> '.$tipoConsulta->descripcion.'<br>
                <b>Cita asociada:</b> '.$cita->id_cita,
            ];
        }

        return response()->json($consultasNuevo);
    }

    // Guardar nueva consulta
    public function store(Request $request)
    {

        try {
            // Obtener los datos del request excepto el token
            $consultaData = $request->except('_token');

            // Crear la nueva consulta
            $consulta = Consulta::create($consultaData);

            $cita = Cita::find($consulta->cita_id);
            $cita->estado = 'atendido';
            $cita->save();

            // Mensaje de éxito
            Flash::success('Consulta creada correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            Log::error('Error al crear la consulta: '.$e->getMessage());
            Flash::error('Error al crear la consulta');
        }

        // Redirigir a la lista de consultas
        return redirect()->route('consultas.index');
    }

    // Editar consulta
    public function edit($id)
    {
        $consulta = Consulta::find($id);

        return response()->json($consulta);
    }

    // Actualizar consulta
    public function update(Request $request, $id)
    {
        try {
            // Mostrar los datos recibidos por el request para verificar si están correctos
            // dd($request->all());

            // Buscar la consulta por ID
            $consulta = Consulta::find($id);

            // Verificar si la consulta existe
            if (! $consulta) {
                Flash::error('Consulta no encontrada');

                return redirect(route('consultas.index'));
            }

            // Mostrar los datos actuales de la consulta antes de actualizar
            // dd($consulta);

            // Rellenar los campos con los datos recibidos del formulario
            $consulta->fill($request->all());

            $cita = Cita::find($consulta->cita_id);
            $cita->estado = 'atendido';
            $cita->save();

            // Guardar los cambios
            if ($consulta->save()) {
                Flash::success('Consulta actualizada correctamente');
            } else {
                Flash::error('Error al actualizar la consulta');
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar la consulta: '.$e->getMessage());
            Flash::error('Error al actualizar la consulta');
        }

        // Redirigir al índice de consultas
        return redirect(route('consultas.index'));
    }

    // Eliminar consulta (puedes implementar si es necesario)
    public function destroy($id)
    {
        try {
            $consulta = Consulta::find($id);
            $consulta->delete();
            // Mensaje de éxito
            Flash::success('Consulta Eliminada correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            Log::error('Error al eliminar la consulta: '.$e->getMessage());
            Flash::error('Error al eliminar la consulta');
        }

        return redirect('consultas'); // al eliminar, redirecciona a la pantalla de inicio.
    }

    // Reporte de consultas (puedes filtrar por criterio)
    public function reportes()
    {
        $pacientes = Paciente::all();
        $medicos = Medico::all();

        return view('reportes.consultas', compact('pacientes', 'medicos'));
    }

    public function generarReporte(Request $request)
    {
        $medico_id = $request->medico;
        $paciente_id = $request->paciente;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $query = Consulta::query();

        $query->when($medico_id != 'all', function ($q) use ($medico_id) {
            $q->where('medico_id', $medico_id);
        });

        $query->when($paciente_id != 'all', function ($q) use ($paciente_id) {
            $q->where('paciente_id', $paciente_id);
        });

        $query->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);

        $consultas = $query->get();

        return view('reportes.consultas_reporte', compact('consultas'));
    }
}
