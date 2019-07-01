{{-- Production Sheet :: Production Summary --}}

@extends('production_sheets.reports.production_orders.master')


@section('content')

	@include('production_sheets.reports.production_orders.manufacturing_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
	
{{-- --}}

	@include('production_sheets.reports.production_orders.styles_css')

{{-- --}}

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
