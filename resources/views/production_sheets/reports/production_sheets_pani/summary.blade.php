{{-- Production Sheet :: Production Summary --}}

@extends('production_sheets.reports.production_sheets_pani.master')


@section('content')

	@include('production_sheets.reports.production_sheets_pani.summary_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
	
{{-- --}}

	@include('production_sheets.reports.production_sheets_pani.styles_css')

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
