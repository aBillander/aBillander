@extends('pos::layouts.auth2')

@section('title') {{ l('POS :: Login', [], 'pos/login') }} @parent @endsection

@section('content')
    <div class="login-form col-md-12 col-xs-12 right-col-content">
        <p class="form-header text-white">{{ l('Please, log-in', 'pos/login') }}</p>
        <form method="POST" action="{{ route('pos::cashier.login.submit') }}" id="login-form">
            {{ csrf_field() }}
            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required xautofocus placeholder="{{ l('E-Mail Address', 'pos/login') }}">
                <span class="fa fa-user form-control-feedback"></span>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" autocomplete="current-password"
                required placeholder="{{ l('Password', 'pos/login') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group  xhide ">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ l('Remember Me', 'pos/login') }}
                    </label>
                </div>
            </div>

            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-flat btn-login">{{l('Login', 'pos/login')}}</button>

                <a href="{ { route('cashier.password.request') } }" class="pull-right  hide ">
                    {{ l('Forgot Your Password?', 'pos/login') }}
                </a>

            </div>
        </form>
    </div>

@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('#change_lang').change( function(){
            window.location = "{{ route('login') }}?lang=" + $(this).val();
        });
    })
</script>
@endsection
