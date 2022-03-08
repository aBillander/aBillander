{{-- File name after template name --}}

@extends('production_sheets.reports.production_sheets.master_invoice')


@section('content')

	@include('production_sheets.reports.production_sheets.body_mdg')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>
{{--
	@include('production_sheets.reports.production_sheets.invoice_css')
--}}
</style>

@endsection



{{-- *************************************** --}}


{{--
@section('scripts') @parent

<script>

  $(function() {

  });
  
</script>

@endsection
--}}
