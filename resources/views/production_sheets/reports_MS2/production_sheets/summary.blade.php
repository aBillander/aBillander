{{-- Production Sheet :: Production Summary --}}

@extends('production_sheets.reports.production_sheets.master')


@section('content')

	@include('production_sheets.reports.production_sheets.summary_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
{{--
<style>

	@include('production_sheets.reports.production_sheets.invoice_css')

</style>
--}}
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
