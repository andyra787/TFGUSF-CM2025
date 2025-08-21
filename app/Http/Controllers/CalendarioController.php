<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Sala;
use App\Models\TipoConsulta;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
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

        return view('calendario.index', compact('medicos', 'pacientes', 'salas', 'tipoConsultas'));
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
}
