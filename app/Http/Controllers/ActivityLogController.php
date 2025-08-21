<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $logs = Activity::with('causer')
            ->orderByDesc('created_at')
            ->paginate($perPage);
        return view('activitylog.index', compact('logs'));
    }
}
