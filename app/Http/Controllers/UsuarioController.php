<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medico;
use DB;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $buscarpor = $request->buscarpor;
        if ($buscarpor) {
            $buscarpor = trim($buscarpor); // Eliminar espacios en blanco al inicio y al final
            $buscarpor = strtolower($buscarpor); // Convertir a minúsculas para evitar problemas de capitalización

            $usuarios = User::whereRaw('LOWER(name) like ?', "%{$buscarpor}%")->orderBy('id', 'asc')->paginate(25);
        } else {
            $usuarios = User::orderBy('id', 'asc')->paginate(25);
        }

        $medicos = Medico::all();
        return view('usuarios.index', compact('usuarios', 'medicos', 'buscarpor'));
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|string|max:255',
            'medico' => 'nullable|string|max:255',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->rol = $request->rol;
        if($request->medico != "nn"){
            $user->doc_id = $request->medico;
        }
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->estado = 'activo';
        $user->save();

        $user->assignRole($request->rol);

        return redirect()->route('usuarios.index')->with('userCreateUpdate', 'Usuario creado correctamente');
    }

    public function update(Request $request)
    {
        $usuario = User::find($request->id);

        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'doc_id' => 'nullable|string|max:255',
            'rol' => 'required|string|max:255'
        ]);

        $usuario->name = $request->name;
        $usuario->rol = $request->rol;
        $usuario->email = $request->email;
        $usuario->estado = 'activo';
        if($request->medico != "nn"){
            $usuario->doc_id = $request->medico;
        }
        $usuario->save();

        $usuario->syncRoles($request->rol);

        return redirect()->route('usuarios.index')->with('userCreateUpdate', 'Usuario actualizado correctamente');
    }

    public function passChange(Request $request) {
        $password = $request->changePassword;
        $password_confirmation = $request->password_changeConfirmation;

        if($password != $password_confirmation || $password == null || $password_confirmation == null) {
            return redirect()->route('usuarios.index')->with('passChangeError', 'Las contraseñas no coinciden');
        }
        
        if(strlen($password) < 8) {
            return redirect()->route('usuarios.index')->with('passChangeError', 'La contraseña debe tener al menos 8 caracteres');
        }
        
        $usuario = User::find($request->id);
        $usuario->password = bcrypt($password);
        $usuario->save();

        return redirect()->route('usuarios.index')->with('passChangeSuccess', 'Contraseña actualizada correctamente');
    }

    public function cambiarEstado(Request $request)
    {
        $usuario = User::find($request->id);
        $msg = '';
        
        if($usuario->estado == 'inactivo') {
            $msg = 'El usuario ' . $usuario->name . ' ha sido activado';
            $usuario->estado = 'activo';
            $usuario->save();
        }else if($usuario->estado == 'activo') {
            $msg = 'El usuario ' . $usuario->name . ' ha sido desactivado';
            $usuario->estado = 'inactivo';
            $usuario->save();
        }

        return redirect()->route('usuarios.index')->with('cambioEstado', $msg);
    }

    public function reportes()
    {
        $usuarios = User::all();
        return view('usuarios.reportes', compact('usuarios'));
    }
}
