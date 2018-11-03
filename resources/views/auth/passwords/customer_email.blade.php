@extends('abcc.layouts.master')

@section('title') {{ l('Forgot Your Password?', [], 'layouts') }} @parent @stop


@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
        <div class="panel panel-info">
            <div class="panel-heading">{{ l('Forgot Your Password?', [], 'layouts') }}</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('customer.password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1 xalert alert-warning" style="padding: 15px;">
                                <span class="help-block">{{ l('Send Password Reset Link.', [], 'layouts') }}</span>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{ l('E-Mail Address', [], 'layouts') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ l('Continue', [], 'layouts') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
