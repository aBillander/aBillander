@extends('layouts.master')

@section('title') {{ l('Production Sheets - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Production Sheet') }} :: ({{$sheet->id}}) {{$sheet->name}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($sheet, array('method' => 'PATCH', 'route' => array('productionsheets.update', $sheet->id))) !!}

					@include('production_sheets._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop


@section('scripts') @parent 

{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#due_date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@stop




@section('styles') @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@stop