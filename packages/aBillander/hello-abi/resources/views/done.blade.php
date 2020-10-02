@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ __('installer::main.done.title') }}</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-success">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
            </div>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        </div>
        <div class="panel-footer text-right">
            <a class="btn btn-primary" href="{{ url('/') }}">
                {{ 'Ir a la página principal' }}
            </a>
        </div>
    </div>

@endsection
