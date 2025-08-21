@extends('adminlte::page')

@section('title', 'Citas')

@section('content_header')
@stop

@section('content')
<div class="container d-flex justify-content-center">
    <div class="card mt-4" style="width: 50%;">
        <form method="POST" action="{{ route('register') }}" class="white-background p-4">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="form-group mt-4">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="form-group mt-4">
                <label for="rol">{{ __('Rol') }}</label>
                <select id="rol" name="rol" class="form-control white-background" required autocomplete="rol">
                    <option value="">--Seleccionar--</option>
                    <option value="admin">Admin</option>
                    <option value="administrativo">Administrador</option>
                    <option value="recepcionista">Recepcionista</option>
                    <option value="medico">Doctor</option>
                </select>
                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form-group mt-4">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group mt-4">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="form-group justify-content-center mt-4 d-flex">
                <button type="submit" class="btn btn-primary btn-lg">{{ __('Register') }}</button>
            </div>
        </form>
    </div>
</div>

@stop
