<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class DepartamentoController extends Controller
{
    /**
     * Muestra una lista de las departamentos.
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

            $departamentos = Departamento::whereRaw('LOWER(nombre) like ?', "%{$buscarpor}%")->orderBy('id_departamento', 'asc')->paginate(25);
        }else{
            $departamentos = Departamento::orderBy('id_departamento', 'asc')->paginate(25);
        }

        return view('departamentos.index', compact('departamentos', 'buscarpor'));
    }

    /**
     * Almacena una nueva departamento en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $departamentos = request()->except('_token');
        Departamento::insert($departamentos);
        Flash::success('Creado correctamente');
        return redirect(route('departamentos.index'));
    }

    /**
     * Actualiza la departamento especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $departamentos = request()->except(['_token', '_method']);
        Departamento::where('id_departamento', '=', $id)->update($departamentos);
        Flash::success('Actualizado correctamente');
        return redirect(route('departamentos.index'));
    }

    /**
     * Elimina la departamento especificada de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Departamento::destroy($id);
        Flash::error('Eliminado correctamente');
        return redirect('departamentos');
    }
}
