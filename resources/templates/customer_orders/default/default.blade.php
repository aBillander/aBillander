{{-- File name after template name --}}

@extends('templates::customer_orders.default.master_order')


@section('content')

	@include('templates::customer_orders.default.order_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::customer_orders.default.order_css')

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
