@extends('adminlte::page')

@section('title', 'Activity Log')

@section('content_header')
    <h1>Registro de Actividad</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Modelo</th>
                        <th>Fecha</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->causer ? $log->causer->name : 'Sistema' }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ class_basename($log->subject_type) }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($log->properties)
                                    <button class="btn btn-xs btn-info" data-toggle="collapse" data-target="#details-{{ $log->id }}">Ver</button>
                                    <div id="details-{{ $log->id }}" class="collapse">
                                        <pre class="bg-light p-2">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay registros</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
