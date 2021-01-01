{{-- File name after template name --}}

@extends('templates::supplier_invoices.default.master_invoice')


@section('content')

	@include('templates::supplier_invoices.default.order_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::supplier_invoices.default.order_css')

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
