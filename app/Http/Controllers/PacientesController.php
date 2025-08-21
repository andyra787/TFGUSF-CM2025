<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Paciente;
use App\Models\Ciudad;
use App\Models\Medico;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;
use Exception;

class PacientesController extends Controller
{
    public function index(Request $request)
    {
        $tipo_busqueda = $request->tipo_busqueda;
        $buscarpor = $request->buscarpor;
        if ($buscarpor) {
            $tipo_busqueda = trim($tipo_busqueda); // Eliminar espacios en blanco al inicio y al final
            $tipo_busqueda = strtolower($tipo_busqueda); // Convertir a minúsculas para evitar problemas de capitalización

            $buscarpor = trim($buscarpor); // Eliminar espacios en blanco al inicio y al final
            $buscarpor = strtolower($buscarpor); // Convertir a minúsculas para evitar problemas de capitalización

            $pacientes = Paciente::whereRaw("LOWER({$tipo_busqueda}) like ?", "%{$buscarpor}%")->orderBy('cod_paciente', 'asc')->paginate(25);
        }else{
            $pacientes = Paciente::orderBy('cod_paciente', 'asc')->paginate(25);
        }

        foreach ($pacientes as $clave => $valor){
            $ciudad = Ciudad::where('id_ciudad', $valor->ciudad)->first();
            $departamento = Departamento::where('id_departamento', $valor->departamento)->first();

            $pacientes[$clave]->ciudad = $ciudad->nombre;
            $pacientes[$clave]->departamento = $departamento->nombre;
        }


        return view('pacientes.index', compact('pacientes', 'buscarpor', 'tipo_busqueda'));
    }

    public function nuevo(){
        $cod_paciente = Paciente::latest('cod_paciente')->value('cod_paciente');
        $departamentos = Departamento::all();

        // Dividir el codigo
        $parts = explode('-', $cod_paciente);

        // Extraer el código numérico y convertirlo a entero
        if($cod_paciente != null){
            $numero = (int)$parts[1];
        }else{
            $numero = 0;
        }

        // Sumar 1 al número
        $nuevo_numero = $numero + 1;

        // Formatear el número con ceros a la izquierda
        $formato = str_pad($nuevo_numero, 3, '0', STR_PAD_LEFT);

        // Crear el nuevo código
        $nuevo_codigo = Date('Y') . '-' . $formato;

        return view('pacientes.RegistroPaciente',compact('cod_paciente','departamentos', 'nuevo_codigo'));
    }

    public function error(){
        $datos = session('datos');
        $ciudad = Ciudad::where('departamento_id',$datos['departamento'])->get();
        $depa= $datos["departamento" ];
        $departamentos = Departamento::all();
        $paciente = DB::table("pacientes")->latest()->first();

        return view('pacientes.Registroerror',compact('paciente', 'datos','departamentos','depa','ciudad'));
    }

    public function obtenerCiudades($departamentoId)
    {
        try {
            $ciudades = Ciudad::where('departamento_id',$departamentoId)->get();
            return response()->json($ciudades);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener ciudades: ' . $e->getMessage()], 500);
        }
    }

    public function crear(Request $request){
        $datos = $request->all();
        $paciente = DB::table("pacientes")->latest()->first();
        $docu = $request->input('num_doc');
        $codi = $request->input('cod_paciente');
        $doc = DB::table("pacientes")->where('num_doc', '=', $docu)->first();
        $cod = DB::table("pacientes")->where('cod_paciente', '=', $codi)->first();
        if($doc != null && $cod != null){
            return redirect()->route('registro-paciente-e')->with(['mensaje' => 'El número de documento y el código ya existen, por favor revíselo', 'datos'=>$datos,]);
            //return view('departamentos.RegistroCiudades', compact('doc', 'cod'));
        }else if($doc){
            return redirect()->route('registro-paciente-e')->with(['mensaje' =>'El número de documento ya existe, por favor revíselo', 'datos'=>$datos,]);
        }else if($cod){
            return redirect()->route('registro-paciente-e')->with(['mensaje'=>'El código de paciente ya existe, por favor revíselo', 'datos'=>$datos,]);
        }else{
            Paciente::create([
                'cod_paciente'=> $request->input('cod_paciente'),
                'nombre'=> $request->input('nombre'),
                'apellido'=> $request->input('apellido'),
                'num_doc'=> $request->input('num_doc'),
                'ciudad'=> $request->input('ciudad'),
                'departamento'=> $request->input('departamento'),
                'direccion'=> $request->input('direccion'),
                'edad'=> $request->input('edad'),
                'sexo'=> $request->input('sexo'),
                'url_maps'=> $request->input('url_maps') ?? null,
                'diagnostico'=> $request->input('diagnostico'),
                'comentario'=> $request->input('comentario'),

            ]);


            return redirect()->route('pacientes.index')->with('mensaje','¡Se guardó correctamente!');

        }
    }

    public function edit($id)
    {
        $nuevo_codigo = '';
        $paciente = Paciente::findOrFail($id);
        $departamentos = Departamento::all();
        $ciudades = Ciudad::all();

        return view('pacientes.RegistroPaciente', compact('paciente', 'departamentos', 'ciudades','nuevo_codigo'));
    }


    public function actualizar(Request $request, $id)
    {
        $paciente = Paciente::find($id);
        $docu = $request->input('num_doc');
        $doc = DB::table("pacientes")->where('num_doc', '=', $docu)->where('id_paciente', '!=', $id)->first();
        if($doc){
            return redirect()->route('pacientes.edit', $id)->with(['mensaje' =>'El número de documento ya existe, por favor revíselo', '$id'=>$id]);
        }else{
        $paciente->update([
            'cod_paciente' => $request->input('cod_paciente'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'num_doc' => $request->input('num_doc'),
            'ciudad' => $request->input('ciudad'),
            'departamento' => $request->input('departamento'),
            'direccion' => $request->input('direccion'),
            'edad' => $request->input('edad'),
            'sexo' => $request->input('sexo'),
            'url_maps' => $request->input('url_maps'),
            'comentario' => $request->input('comentario'),
        ]);}


        return redirect()->route('pacientes.index')->with('mensaje', '¡Se guardó correctamente!');
    }

    public function eliminar($id)
    {
        try {
            $destroy = Paciente::destroy($id);

            if ($destroy) {
                Flash::success('Se eliminó correctamente!');
            } else {
                Flash::error('Error al eliminar!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturamos el error de la base de datos
            Flash::error('Error al eliminar la sala. Detalles del error: ' . $e->getMessage());
        } catch (Exception $e) {
            Flash::error('Se produjo un error: ' . $e->getMessage());
        }

        return redirect()->route('pacientes.index');
    }

    //reportes
    public function reportes(){
        $departamentos = Departamento::all();
        $pacientes = Paciente::all();
        $tipo_busqueda = ''; 
        $buscarpor = '';
        return view('reportes.pacientes', compact('pacientes','departamentos', 'tipo_busqueda', 'buscarpor'));
    }

    public function buscarPacientesAjax(Request $request) {
        $tipo_busqueda = $request->tipo_busqueda;
        $buscarpor = $request->buscarpor;

        $query = Paciente::query();

        if ($buscarpor) {
            // Búsqueda que contiene el texto (case-insensitive)
            $query->whereRaw("LOWER({$tipo_busqueda}) LIKE LOWER(?)", ["%{$buscarpor}%"]);
        }

        // Limitar resultados para mejor rendimiento
        $pacientes = $query->take(10)->get();

        return response()->json($pacientes);
    }

    public function generarReporte(Request $request) {
        $departamentos = Departamento::all();
        $ciudades = Ciudad::all();

        $query = Paciente::query();

        // Si se solicita el reporte de todos los pacientes
        if ($request->has('todos')) {
            $pacientes = $query->get();
            return view('reportes.pacientes_reporte', compact('pacientes', 'departamentos', 'ciudades'));
        }

        $tipo_busqueda = $request->tipo_busqueda;
        $buscarpor = $request->buscarpor;
        
        $paciente_id = $request->paciente;
        $edad = $request->edad;
        $depa = $request->departamento;
        $ciud = $request->ciudad;

        $query = Paciente::query();
        if ($buscarpor) {
            $tipo_busqueda = trim($tipo_busqueda); // Eliminar espacios en blanco al inicio y al final
            $tipo_busqueda = strtolower($tipo_busqueda); // Convertir a minúsculas para evitar problemas de capitalización

            $buscarpor = trim($buscarpor); // Eliminar espacios en blanco al inicio y al final
            $buscarpor = strtolower($buscarpor); // Convertir a minúsculas para evitar problemas de capitalización

            $query->whereRaw("LOWER({$tipo_busqueda}) like ?", ["%{$buscarpor}%"]);
        }

        // Condición para paciente
        $query->when($paciente_id != 'all', function ($q) use ($paciente_id, &$paciente) {
            $pacienteData = Paciente::find($paciente_id);
            if ($pacienteData) {
                $q->where('id_paciente', $paciente_id);
                $paciente = $pacienteData->nombre;
            }
        });

        //Condicion para estado
        if($edad != NULL){
            $query->where('edad', $edad);
        }

        if($depa != ''){
            $query->where('departamento', $depa);
        }
        if($ciud != ''){
            $query->where('ciudad', $ciud);
        }



        // Obtener las citas
        $pacientes = $query->get();

        return view('reportes.pacientes_reporte', compact('pacientes', 'departamentos', 'ciudades'));
    }
}
