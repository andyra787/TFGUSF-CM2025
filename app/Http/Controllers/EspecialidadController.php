<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Exception;

class EspecialidadController extends Controller
{
    /**
     * Muestra una lista de las especialidades.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $buscarpor = $request->buscarpor;
        if ($buscarpor) {
            $buscarpor = trim($buscarpor); // Eliminar espacios en blanco al inicio y al final
            $buscarpor = strtolower($buscarpor); // Convertir a minúsculas para evitar problemas de capitalización

            $especialidades = Especialidad::whereRaw('LOWER(nombre) like ?', "%{$buscarpor}%")->orderBy('id_especialidad', 'asc')->paginate(25);
        }else{
            $especialidades = Especialidad::orderBy('id_especialidad', 'asc')->paginate(25);
        }
        return view('especialidades.index', compact('especialidades', 'buscarpor'));
    }

    /**
     * Almacena una nueva especialidad en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /* $rules = [
            'nombre' => 'required|string',
        ];

        $mensaje = [
            'required' => 'El :attribute es requerido',
        ];
        $this->validate($request, $rules, $mensaje); */

        $especialidades = request()->except('_token');
        Especialidad::insert($especialidades);
        Flash::success('Creado correctamente');
        return redirect(route('especialidades.index'));
    }

    /**
     * Actualiza la especialidad especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $especialidades = request()->except(['_token', '_method']);
        Especialidad::where('id_especialidad', '=', $id)->update($especialidades);
        Flash::success('Actualizado correctamente');
        return redirect(route('especialidades.index'));
    }

    /**
     * Elimina la especialidad especificada de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $destroy = Especialidad::destroy($id);

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

        return redirect('especialidades');
    }
}
