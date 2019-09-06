@extends('layouts.master')

@section('title') {{ l('Login', [], 'layouts') }} @parent @stop


@section('content')
<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">{{ l('Please, log-in', [], 'layouts') }}</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<!-- div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							{!! l('<strong>Whoops!</strong> There were some problems with your input.') !!}<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div -->
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">{{ l('E-Mail Address', [], 'layouts') }}</label>
							<div class="col-md-6">
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">{{ l('Password', [], 'layouts') }}</label>
							<div class="col-md-6">
								<input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ l('Remember Me', [], 'layouts') }}
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">{{l('Login', [], 'layouts')}}</button>



								<a class="btn btn-link" href="{{ route('password.request') }}">{{ l('Forgot Your Password?', [], 'layouts') }}</a>



							</div>
						</div>

					</form>
				</div>
		</div>
	</div>
</div>

@endsection
