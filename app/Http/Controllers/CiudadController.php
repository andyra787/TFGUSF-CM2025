<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class CiudadController extends Controller
{
    /**
     * Muestra una lista de las ciudades.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $buscarpor = $request->buscarpor;
        if ($request->buscarpor) {
            $buscarpor = trim($buscarpor); // Eliminar espacios en blanco al inicio y al final
            $buscarpor = strtolower($buscarpor); // Convertir a minúsculas para evitar problemas de capitalización

            $ciudades = Ciudad::whereRaw('LOWER(nombre) like ?', "%{$buscarpor}%")->orderBy('id_ciudad', 'asc')->paginate(25);
        } else {
            $ciudades = Ciudad::orderBy('id_ciudad', 'asc')->paginate(25);
        }

        $departamentos = Departamento::all();

        return view('ciudades.index', compact('ciudades', 'departamentos', 'buscarpor'));
    }

    /**
     * Almacena una nueva ciudad en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $ciudades = request()->except('_token');
        Ciudad::insert($ciudades);
        Flash::success('Creado correctamente');
        return redirect(route('ciudades.index'));
    }

    /**
     * Actualiza la ciudad especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $ciudades = request()->except(['_token', '_method']);
        Ciudad::where('id_ciudad', '=', $id)->update($ciudades);
        Flash::success('Actualizado correctamente');
        return redirect(route('ciudades.index'));
    }

    /**
     * Elimina la Ciudad especificada de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $destroy = Ciudad::destroy($id);

            if ($destroy) {
                Flash::success('Se eliminó correctamente!');
            } else {
                Flash::error('No se pudo eliminar');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Flash::error('Error al eliminar la sala. Detalles del error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Flash::error('No se pudo eliminar');
        }

        return redirect('ciudades');
    }
}
