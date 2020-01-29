{{-- Production Sheet :: Products Summary --}}

@extends('production_sheet_delivery_routes.reports.delivery_sheet.master')


@section('content')

	@include('production_sheet_delivery_routes.reports.delivery_sheet.delivery_sheet_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
	
{{-- --}}

	@include('production_sheet_delivery_routes.reports.delivery_sheet.styles_css')

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
