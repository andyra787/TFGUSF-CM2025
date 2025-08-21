<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Sala;
use App\Models\TipoConsulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function buscarCitasAjax(Request $request)
    {
        $tipo_busqueda = $request->tipo_busqueda;
        $buscarpor = $request->buscarpor;

        $query = Cita::with(['paciente', 'medico'])
            ->select('citas.*');

        if ($buscarpor) {
            switch ($tipo_busqueda) {
                case 'cod_reserva':
                    $query->where('id_cita', 'LIKE', "%{$buscarpor}%");
                    break;
                case 'paciente':
                    $query->whereHas('paciente', function($q) use ($buscarpor) {
                        $q->whereRaw("LOWER(CONCAT(nombre, ' ', apellido)) LIKE ?", ['%'.strtolower($buscarpor).'%']);
                    });
                    break;
                case 'medico':
                    $query->whereHas('medico', function($q) use ($buscarpor) {
                        $q->whereRaw("LOWER(nombre) LIKE ?", ['%'.strtolower($buscarpor).'%']);
                    });
                    break;
            }
        }

        $citas = $query->take(10)->get()->map(function($cita) {
            return [
                'id_cita' => $cita->id_cita,
                'paciente_id' => $cita->paciente_id,
                'paciente_nombre' => $cita->paciente->nombre . ' ' . $cita->paciente->apellido,
                'medico_id' => $cita->medico_id,
                'medico_nombre' => $cita->medico->nombre,
                'fec_inicio' => $cita->fec_inicio,
                'estado' => $cita->estado
            ];
        });

        return response()->json($citas);
    }
    public function index()
    {

        $medicos = Medico::orderBy('id_medico', 'asc')->get()->all();
        $pacientes = Paciente::orderBy('nombre', 'asc')->get()->all();
        $salas = Sala::all();
        $tipoConsultas = TipoConsulta::all();
        $especialidades = Especialidad::all();

        foreach ($medicos as $clave => $valor) {
            $medicos[$clave]->especialidad = Especialidad::find($valor->especialidad_id)->nombre;
        }

        return view('citas.index', compact('medicos', 'pacientes', 'salas', 'tipoConsultas'));
    }

   public function show(Request $request)
    {
        $medico_id = $request->query('medico_id');
        $paciente_id = $request->query('paciente_id');
        $tipo_consulta_id = $request->query('tipo_consulta_id');
        $sala_id = $request->query('sala_id');

        $query = Cita::query();

        if ($medico_id && $medico_id !== 'all') {
            $query->where('medico_id', $medico_id);
        }

        if ($paciente_id && $paciente_id !== 'all') {
            $query->where('paciente_id', $paciente_id);
        }

        if ($tipo_consulta_id && $tipo_consulta_id !== 'all') {
            $query->where('tipo_consulta_id', $tipo_consulta_id);
        }

        if ($sala_id && $sala_id !== 'all') {
            $query->where('sala_id', $sala_id);
        }

        $citas = $query->get();

        $citasNuevo = [];

        foreach ($citas as $clave => $valor) {
            $doctor = Medico::find($valor->medico_id);
            $paciente = Paciente::find($valor->paciente_id);
            $sala = Sala::find($valor->sala_id);
            $tipoConsulta = TipoConsulta::find($valor->tipo_consulta_id);

            $fec_ini = date_format(new \DateTime($valor->fec_inicio), 'd/m/Y H:i');
            $fec_fin = date_format(new \DateTime($valor->fec_fin), 'd/m/Y H:i');

            $color = '';
            switch ($valor->estado) {
                case 'Pendiente':
                case 'pendiente':
                    $color = '#FFA500';
                    break;
                case 'atendido':
                    $color = '#28a745';
                    break;
                case 'Cancelado':
                    $color = '#dc3545';
                    break;
                default:
                    $color = '#007bff';
                    break;
            }

            $citasNuevo[$clave] = [
                'title' => $valor->id_cita.' - '.$paciente->nombre.' '.$paciente->apellido,
                'start' => $valor->fec_inicio,
                'end' => $valor->fec_fin,
                'description' => "<span style='text-align: start;'>
                                    <b>Estado: </b>".$valor->estado.'<br><br>
                                    <b>Fecha inicio: </b>'.$fec_ini.'<br>
                                    <b>Fecha fin: </b>'.$fec_fin.'<br>
                                    <b>Doctor:</b> '.$doctor->nombre.'<br>
                                    <b>N° de Sala:</b> '.$sala->tipo_sala.' - '.$sala->num_sala.'<br>
                                    <b>Tipo de Consulta:</b> '.$tipoConsulta->descripcion.'<br>
                                    <b>Observaciones:</b> '.$valor->observaciones.'
                                </span>',
                'id' => $valor->id_cita,
                'estado' => $valor->estado,
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

        if ($citasNuevo == null) {
            $citasNuevo = [];
        }

        return response()->json($citasNuevo);
    }

    public function store(Request $request)
    {
        try {
            $status = 0;
            $result = '';
    
            // Validar si el paciente ya tiene una cita en el mismo día
            $fechaInicio = new \DateTime($request->fec_inicio);
            $fechaInicio->setTime(0, 0, 0); // Establecer inicio del día
            $fechaFin = new \DateTime($request->fec_inicio);
            $fechaFin->setTime(23, 59, 59); // Establecer fin del día
    
            $citaExistente = Cita::where('paciente_id', $request->paciente_id)
                ->whereBetween('fec_inicio', [$fechaInicio, $fechaFin])
                ->first();
    
            if ($citaExistente) {
                return response()->json('El paciente ya tiene una cita programada para este día.', 400);
            }
            // Validaciones
    $request->validate([
        'fec_inicio' => 'required|date|after_or_equal:today',
        'fec_fin' => 'required|date|after:fec_inicio',
        'observaciones' => 'nullable|string',
        'tipo_consulta_id' => 'required|exists:tipo_consultas,id_tipo_consulta',
        'paciente_id' => 'required|exists:pacientes,id_paciente',
        'medico_id' => 'required|exists:medicos,id_medico',
        'sala_id' => 'required|exists:salas,id_sala',
    ]);

    
            // Crear la cita
            $cita = Cita::create($request->all());
    
            if ($cita) {
                $status = 200;
                $result = 'Cita creada correctamente';
            } else {
                $status = 500;
                $result = 'Error al crear la cita';
            }
    
            return response()->json($result, $status);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
    
            return response()->json($th->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $cita = Cita::find($id);

        return response()->json($cita);
    }

    public function atendido($id)
    {
        try {
            $cita = Cita::find($id);
            
            if (!$cita) {
                return response()->json(['error' => 'Cita no encontrada'], 404);
            }

            $cita->estado = 'atendido';
            $cita->save();

            return response()->json('Cita actualizada correctamente', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la cita: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $status = 0;
            $result = '';

            $cita = Cita::find($request->id_cita);
            $cita->fill($request->all());

            if ($cita->save()) {
                $status = 200;
                $result = 'Cita actualizada correctamente';
            } else {
                $status = 500;
                $result = 'Error al actualizar la cita';
            }

            return response()->json($result, $status);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function destroy()
    {

    }

    public function listar(Request $request)
    {
        $medico_id = $request->query('medico_id');
        $paciente_id = $request->query('paciente_id');
        $estado = $request->query('estado');
        $fecha = $request->query('fecha');
        $cedula = $request->query('cedula');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $query = Cita::with(['paciente', 'medico', 'sala'])
                    ->orderBy('fec_inicio', 'desc'); // Ordenar por fecha más reciente

        if ($medico_id && $medico_id !== 'all') {
            $query->where('medico_id', $medico_id);
        }

        if ($paciente_id && $paciente_id !== 'all') {
            $query->where('paciente_id', $paciente_id);
        }

        if ($estado && $estado !== 'all') {
            $query->where('estado', $estado);
        }

        if ($fecha) {
            $query->whereDate('fec_inicio', $fecha);
        }

        // Filtro por cédula del paciente
        if ($cedula) {
            $query->whereHas('paciente', function ($q) use ($cedula) {
                $q->where('num_doc', 'LIKE', '%' . $cedula . '%');
            });
        }

        $citas = $query->paginate($perPage, ['*'], 'page', $page);

        $citasFormateadas = [
            'data' => [],
            'current_page' => $citas->currentPage(),
            'last_page' => $citas->lastPage(),
            'per_page' => $citas->perPage(),
            'total' => $citas->total(),
            'from' => $citas->firstItem(),
            'to' => $citas->lastItem(),
        ];

        foreach ($citas->items() as $cita) {
            $citasFormateadas['data'][] = [
                'id_cita' => $cita->id_cita,
                'paciente' => $cita->paciente->nombre . ' ' . $cita->paciente->apellido,
                'cedula' => $cita->paciente->num_doc,
                'doctor' => $cita->medico->nombre,
                'fec_inicio' => date('d/m/Y H:i', strtotime($cita->fec_inicio)),
                'fec_fin' => date('d/m/Y H:i', strtotime($cita->fec_fin)),
                'sala' => $cita->sala->tipo_sala . ' - ' . $cita->sala->num_sala,
                'estado' => $cita->estado,
            ];
        }

        return response()->json($citasFormateadas);
    }

    public function reportes()
    {

        $medicos = Medico::all();
        $pacientes = Paciente::all();

        return view('reportes.citas', compact('medicos', 'pacientes'));
    }

    public function generarReporte(Request $request)
    {

        $medico_id = $request->medico;
        $paciente_id = $request->paciente;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $estado = $request->estado;

        $query = Cita::query();

        // Condición para médico
        $query->when($medico_id !== 'all', function ($q) use ($medico_id, &$medico) {
            $medicoData = Medico::find($medico_id);
            if ($medicoData) {
                $q->where('medico_id', $medico_id);
                $medico = $medicoData->nombre;
            }
        });

        // Condición para paciente
        $query->when($paciente_id !== 'all', function ($q) use ($paciente_id, &$paciente) {
            $pacienteData = Paciente::find($paciente_id);
            if ($pacienteData) {
                $q->where('paciente_id', $paciente_id);
                $paciente = $pacienteData->nombre;
            }
        });

        // Condición para estado
        if ($estado !== 'all') {
            $query->where('estado', $estado);
        }

        // Filtro por rango de fechas
        $query->whereBetween('fec_inicio', [$fecha_inicio, $fecha_fin]);

        // Obtener las citas
        $citas = $query->get();

        return view('reportes.citas_reporte', compact('citas'));
    }
}
