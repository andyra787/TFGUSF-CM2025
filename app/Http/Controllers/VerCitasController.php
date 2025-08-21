<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cita;

class VerCitasController extends Controller
{
    public function index()
    {
        $fecha_actual = Carbon::now();

        $citas = Cita::whereDate('fec_inicio', $fecha_actual->toDateString())
            ->orderBy('fec_inicio', 'asc')
            ->get();

        return view('front.citas', compact('citas'));
    }
}
