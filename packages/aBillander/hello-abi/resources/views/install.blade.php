@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <form class="" action="{{ route('installer::install') }}" method="post">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.install.title') }}</h3>
            </div>

            <div class="panel-body">
                <div class="alert alert-success">
                    {{ 'La configuración de la base de datos es correcta y ya está funcionando.' }}
                </div>
                <hr>

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>

            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::configuration') }}">{{ __('pagination.previous') }}</a>
                <button class="btn btn-primary" type="submit">
                    {{ 'Instalar' }}
                </button>
            </div>
        </div>

    </form>

@endsection
