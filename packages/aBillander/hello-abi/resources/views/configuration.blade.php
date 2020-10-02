@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <form class="" action="{{ route('installer::configuration') }}" method="post">
        <input type="hidden" name="APP_URL" value="{{ url('/') }}">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.config.title') }}</h3>
            </div>
            <div class="panel-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <hr>
                <div class="form-group">
                    <p class="lead">{{ __('installer::main.config.database') }}</p>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">Database host</label><br>
                            <input type="text" name="DB_HOST" class="form-control" value="{{ config('database.connections.mysql.host') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">Database port</label><br>
                            <input type="text" name="DB_PORT" class="form-control" value="{{ config('database.connections.mysql.port') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">Database name</label><br>
                            <input type="text" name="DB_DATABASE" class="form-control" value="{{ config('database.connections.mysql.database') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">Database login</label><br>
                            <input type="text" name="DB_USERNAME" class="form-control" value="{{ config('database.connections.mysql.username') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">Database password</label><br>
                            <input type="password" name="DB_PASSWORD" class="form-control" value="{{ config('database.connections.mysql.password') }}" required>
                        </div>
                    </div>
                </div>

                @include('installer::partials.errors')
            </div>
            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::requirements') }}">{{ __('pagination.previous') }}</a>
                <button class="btn btn-primary" type="submit">
                    {{ __('pagination.next') }}
                </button>
            </div>
        </div>

    </form>

@endsection
