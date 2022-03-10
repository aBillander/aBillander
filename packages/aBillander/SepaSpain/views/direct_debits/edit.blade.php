@extends('layouts.master')

@section('title') {{ l('SEPA Direct Debits - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit SEPA Direct Debit') }} :: <span class="label label-success">{{$directdebit->document_reference}}</span> - ({{$directdebit->id}}) </h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($directdebit, array('method' => 'PATCH', 'route' => array('sepasp.directdebits.update', $directdebit->id))) !!}

					@include('sepa_es::direct_debits._form_edit')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop


@section('scripts') @parent 

{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
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