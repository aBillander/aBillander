{{-- Production Sheet :: Production Summary --}}

@extends('production_sheets.reports.preassemblies.master')


@section('content')

	@include('production_sheets.reports.preassemblies.preassemblies_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
	
{{-- --}}

	@include('production_sheets.reports.preassemblies.styles_css')

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
