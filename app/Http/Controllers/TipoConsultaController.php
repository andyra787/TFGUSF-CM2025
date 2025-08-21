<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipoConsulta;
use Laracasts\Flash\Flash;

class TipoConsultaController extends Controller
{
    /**
     * Muestra una lista de las tipos_de_consulta.
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

            $tipos_de_consulta = TipoConsulta::whereRaw('LOWER(descripcion) like ?', "%{$buscarpor}%")->paginate(10);
        } else {
            $tipos_de_consulta = TipoConsulta::paginate(10);
        }

        return view('tipos-consulta.index', compact('tipos_de_consulta', 'buscarpor'));
    }

    public function store(Request $request)
    {
        $tipos_de_consulta = request()->except('_token');
        TipoConsulta::insert($tipos_de_consulta);
        Flash::success('Creado correctamente');
        return redirect(route('tipos-consulta.index'));
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
        $tipos_de_consulta = request()->except(['_token', '_method']);
        TipoConsulta::where('id_tipo_consulta', '=', $id)->update($tipos_de_consulta);
        Flash::success('Actualizado correctamente');
        return redirect(route('tipos-consulta.index'));
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
            TipoConsulta::destroy($id);
            Flash::error('Eliminado correctamente');
        } catch (\Illuminate\Database\QueryException $e) {
            Flash::error('Error al eliminar la sala. Detalles del error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Flash::error('No se puede eliminar este registro');
        }

        return redirect('tipos-consulta');
    }
}
