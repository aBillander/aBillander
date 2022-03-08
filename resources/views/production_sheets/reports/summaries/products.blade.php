{{-- Production Sheet :: Products Summary --}}

@extends('production_sheets.reports.summaries.master')


@section('content')

	@include('production_sheets.reports.summaries.products_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
	
{{-- --}}

	@include('production_sheets.reports.summaries.styles_css')

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
