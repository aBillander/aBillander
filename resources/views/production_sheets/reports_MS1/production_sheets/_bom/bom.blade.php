{{-- Production Sheet :: Production Summary --}}

@extends('production_sheets.reports.production_sheets.master')


@section('content')

	@include('production_sheets.reports.production_sheets.bom_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
{{-- --}}
<style>

	@include('production_sheets.reports.production_sheets.bom_css')

</style>
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
