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

            @include('installer::partials.notifications')

            @include('installer::partials.errors')

            <div class="panel-body">
                <p>{!! __('installer::main.config.body') !!}</p>
                <hr>
                <div class="form-group">
                    <p class="lead">{{ __('installer::main.config.database') }}:</p>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.config.host') }}</label><br>
                            <input type="text" name="DB_HOST" class="form-control" value="{{ config('database.connections.mysql.host') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.config.port') }}</label><br>
                            <input type="text" name="DB_PORT" class="form-control" value="{{ config('database.connections.mysql.port') }}" required>
                            <span class="help-block">{{ __('installer::main.config.port_help') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.config.name') }}</label><br>
                            <input type="text" name="DB_DATABASE" class="form-control" value="{{ config('database.connections.mysql.database') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.config.login') }}</label><br>
                            <input type="text" name="DB_USERNAME" class="form-control" value="{{ config('database.connections.mysql.username') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.config.password') }}</label><br>
                            <!--input type="password" name="DB_PASSWORD" class="form-control" value="{{ config('database.connections.mysql.password') }}" required -->
  <div class="input-group">
                            <input id="password-field" type="password" name="DB_PASSWORD" class="form-control" value="{{ config('database.connections.mysql.password') }}">
    <span class="input-group-btn">
      <button class="btn btn-grey" type="button"><span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span></button>
    </span>
  </div>

                        </div>
                    </div>
                </div>
                <hr>
                <input type="hidden" name="action" id="action" value="continue">
                <button class="btn btn-success" type="submit" onclick="$('#action').val('check');this.disabled=true;this.form.submit()">
                    {{ __('installer::main.config.check') }}
                </button>

            </div>
            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::requirements') }}">{{ __('pagination.previous') }}</a>
                <a class="btn btn-primary" href="{{ route('installer::mail') }}" {{ Session::get('error') ? 'disabled' : ''}}>
                    {{ __('pagination.next') }}
                </a>
            </div>
        </div>

    </form>

@endsection

@include('installer::partials.toggle_password')
