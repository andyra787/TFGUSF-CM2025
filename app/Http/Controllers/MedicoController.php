<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Exception;

class MedicoController extends Controller
{
    /**
     * Muestra una lista de recursos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscarpor = $request->buscarpor;
        if ($buscarpor) {
            $buscarpor = trim($buscarpor); // Eliminar espacios en blanco al inicio y al final
            $buscarpor = strtolower($buscarpor); // Convertir a minúsculas para evitar problemas de capitalización

            $medicos = Medico::whereRaw('LOWER(nombre) like ?', "%{$buscarpor}%")->orderBy('nombre', 'asc')->paginate(25);
        } else {
            $medicos = Medico::orderBy('nombre', 'asc')->paginate(25);
        }
        $especialidades = Especialidad::all();
        return view('medicos.index', compact('medicos', 'especialidades', 'buscarpor'));
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $medicos = request()->except('_token');
        $insert = Medico::insert($medicos);
        if($insert){
            Flash::success('Creado correctamente');
        }else{  
            Flash::error('Error al crear');
        }
        return redirect(route('medicos.index'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //si el id existe lo actualiza y muestra un mensaje de que el proceso se completo correctamente
        $medicos = request()->except(['_token', '_method']);
        $especialidades = Especialidad::all();
        $update = Medico::where('id_medico', '=', $id)->update($medicos);
        if($update){
            Flash::success('Editado correctamente');
        }else{
            Flash::error('Error al editar');
        }
        return redirect(route('medicos.index'));
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $destroy = Medico::destroy($id); //eliminar registro de la base de datos.
            
            if($destroy){
                Flash::success('Eliminado correctamente');
            }else{
                Flash::error('Eliminado correctamente');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturamos el error de la base de datos
            Flash::error('Error al eliminar la sala. Detalles del error: ' . $e->getMessage());
        } catch (Exception $e) {
            Flash::error('Se produjo un error: ' . $e->getMessage());
        }

        return redirect('medicos'); // al eliminar, redirecciona a la pantalla de inicio.
    }
}
