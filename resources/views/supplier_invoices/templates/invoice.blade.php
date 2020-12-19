@extends('customer_orders.templates.master_invoice')


@section('content')

	@include('customer_orders.templates.invoice_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('customer_orders.templates.invoice_css')

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
