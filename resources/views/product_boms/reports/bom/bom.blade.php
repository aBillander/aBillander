{{-- Production Sheet :: Production Summary --}}

@extends('product_boms.reports.bom.master')


@section('content')

	@include('product_boms.reports.bom.bom_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent
{{--
<style>

	@include('product_boms.reports.bom.bom_css')

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
