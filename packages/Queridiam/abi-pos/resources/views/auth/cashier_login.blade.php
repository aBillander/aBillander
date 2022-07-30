@extends('pos::layouts.auth2')

@section('title') {{ l('POS &#58&#58 Login', 'pos/login') }} @parent @endsection

@section('content')
    <div class="login-form col-md-12 col-xs-12 right-col-content">
        <p class="form-header text-white">{{ l('Please, log-in', 'pos/login') }}</p>
        <form method="POST" action="{{ route('pos::cashier.login') }}" id="login-form">
            {{ csrf_field() }}
            <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                @php
                    $username = old('username');
                    $password = null;
                    if(config('app.env') == 'demo'){
                        $username = 'admin';
                        $password = '123456';

                        $demo_types = array(
                            'all_in_one' => 'admin',
                        );

                        if( !empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types) ){
                            $username = $demo_types[$_GET['demo_type']];
                        }
                    }
                @endphp
                <input id="username" type="text" class="form-control" name="username" value="{{ $username }}" required xautofocus placeholder="{{ l('E-Mail Address', 'pos/login') }}">
                <span class="fa fa-user form-control-feedback"></span>
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password"
                value="{{ $password }}" required placeholder="{{ l('Password', 'pos/login') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        @if(0)
            <div class="form-group">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ l('Remember Me', 'pos/login') }}
                    </label>
                </div>
            </div>
        @endif
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-flat btn-login">{{l('Login', 'pos/login')}}</button>
            @if(0)
                <a href="{{ route('password.request') }}" class="pull-right">
                    {{ l('Forgot Your Password?', 'layouts') }}
                </a>
            @endif
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

        $('a.demo-login').click( function (e) {
           e.preventDefault();
           $('#username').val($(this).data('admin'));
           $('#password').val("{{$password}}");
           $('form#login-form').submit();
        });
    })
</script>
@endsection
