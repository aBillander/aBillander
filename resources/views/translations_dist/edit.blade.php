@extends('layouts.master')

@section('title') {{ l('Translations - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Translation') }} :: {{$id}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				<form method="POST" action="http://localhost/aBillander5/public/translations/{{$id}}" accept-charset="UTF-8">
					<input name="_method" value="PATCH" type="hidden">
					{{-- csrf_field() --}}
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('translations._form')

				</form>
			</div>
		</div>
	</div>
</div>

@stop