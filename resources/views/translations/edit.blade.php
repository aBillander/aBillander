@extends('layouts.master')

@section('title') {{ l('Translations - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-10 col-md-offset-1" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Translation') }} :: {{$id}} ({{$language->name}})</h3></div>
			<div class="panel-body">

				@include('errors.list')

				<form method="POST" action="{{ URL::to('translations/'.$id) }}" accept-charset="UTF-8">
					<input name="_method" value="PATCH" type="hidden">
					{{-- csrf_field() --}}
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('translations._form')

				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">
	// https://stackoverflow.com/questions/23249130/delete-table-row-using-jquery

	$(document).on('click', 'button.removebutton', function () {
	     // alert("aa");
	     $(this).closest('tr').remove();
	     return false;
	 });

</script>

@endsection