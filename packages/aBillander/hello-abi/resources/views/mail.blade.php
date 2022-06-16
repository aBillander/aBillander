@extends('installer::layouts.installer')

@section('title')
    {{ __('installer::main.title') }}
@endsection

@section('panel')

    <form class="" action="{{ route('installer::mail') }}" method="post">
        <input type="hidden" name="APP_URL" value="{{ url('/') }}">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('installer::main.mail.title') }}</h3>
            </div>

            @include('installer::partials.notifications')

            @include('installer::partials.errors')

            <div class="panel-body">
                <p>{!! __('installer::main.mail.body') !!}</p>
                <hr>
                <div class="form-group">
                    <p class="lead">{{ __('installer::main.mail.mailhost') }}:</p>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.driver') }}</label><br>
                            <select class="form-control" name="MAIL_DRIVER">
                              <option value="smtp" {{ config('mail.driver') == 'smtp' ? 'selected' : '' }}>smtp</option>
                            </select>
                            <span class="help-block">{!! __('installer::main.mail.driver_help') !!}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.host') }}</label><br>
                            <input type="text" name="MAIL_HOST" class="form-control" value="{{ config('mail.host') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.port') }}</label><br>
                            <input type="text" name="MAIL_PORT" class="form-control" value="{{ config('mail.port') }}" required>
                            <span class="help-block">{{ __('installer::main.mail.port_help') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.encryption') }}</label><br>
                            <select class="form-control" name="MAIL_ENCRYPTION">
                              <option value="tls" {{ config('mail.encryption') == 'tls' ? 'selected' : '' }}>tls</option>
                              <option value="ssl" {{ config('mail.encryption') == 'ssl' ? 'selected' : '' }}>ssl</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.user') }}</label><br>
                            <input type="text" name="MAIL_USERNAME" class="form-control" value="{{ config('mail.username') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.password') }}</label><br>
                            <!--input type="password" name="DB_PASSWORD" class="form-control" value="{{ config('database.connections.mysql.password') }}" required -->
  <div class="input-group">
                            <input id="password-field" type="password" name="MAIL_PASSWORD" class="form-control" value="{{ config('mail.password') }}" required>
    <span class="input-group-btn">
      <button class="btn btn-grey" type="button"><span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span></button>
    </span>
  </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.from') }}</label><br>
                            <input type="text" name="MAIL_FROM_ADDRESS" class="form-control" value="{{ config('mail.from.address') }}" required>
                            <span class="help-block">{!! __('installer::main.mail.from_help') !!}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="inputDefault">{{ __('installer::main.mail.from_name') }}</label><br>
                            <input type="text" name="MAIL_FROM_NAME" class="form-control" value="{{ config('mail.from.name') }}" required>
                            <span class="help-block">{!! __('installer::main.mail.from_name_help') !!}</span>
                        </div>
                    </div>
                </div>
                <hr>
                <input type="hidden" name="action" id="action" value="continue">
                <button class="btn btn-success" type="submit" onclick="$('#action').val('check');this.disabled=true;this.form.submit()">
                    {{ __('installer::main.mail.check') }}
                </button>

            </div>
            <div class="panel-footer text-right">
                <a class="btn btn-link" href="{{ route('installer::requirements') }}">{!! __('pagination.previous') !!}</a>
                <button class="btn btn-primary" type="submit">
                    {!! __('pagination.next') !!}
                </button>
            </div>
        </div>

    </form>

@endsection

@include('installer::partials.toggle_password')
