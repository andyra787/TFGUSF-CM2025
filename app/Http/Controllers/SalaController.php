<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Sala;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Exception;

class SalaController extends Controller
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

            $salas = Sala::whereRaw('LOWER(tipo_sala) like ?', "%{$buscarpor}%")->orderBy('id_sala', 'asc')->paginate(25);
        }else{
            $salas = Sala::orderBy('id_sala', 'asc')->paginate(25);
        }
        return view('salas.index', compact('salas', 'buscarpor'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('salas.create');
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Sala::create([
            'tipo_sala' => $request->input('tipo_sala'),
            'num_sala' => $request->input('num_sala'),
        ]);

        Flash::success('Se guardó correctamente!');
        return redirect()->route('salas.index');
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sala = Sala::find($id);
        return view('salas.edit', compact('sala'));
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
        $salas = request()->except(['_token', '_method']);
        $update = Sala::where('id_sala', '=', $id)->update($salas);
        
        if($update){
            Flash::success('Editado correctamente');
        }else{
            Flash::error('Error al editar');
        }
        return redirect()->route('salas.index');

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
            $destroy = Sala::destroy($id);

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

        return redirect()->route('salas.index');
    }
}

