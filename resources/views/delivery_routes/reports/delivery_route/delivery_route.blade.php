{{-- Production Sheet :: Products Summary --}}

@extends('delivery_routes.reports.delivery_route.master')


@section('content')

	@include('delivery_routes.reports.delivery_route.delivery_route_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
	
{{-- --}}

	@include('delivery_routes.reports.delivery_route.styles_css')

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
