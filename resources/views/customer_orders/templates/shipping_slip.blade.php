@extends('customer_orders.templates.master_shipping_slip')


@section('content')

	@include('customer_orders.templates.shipping_slip_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('customer_orders.templates.shipping_slip_css')

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
