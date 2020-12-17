{{-- File name after template name --}}

@extends('templates::supplier_shipping_slips.default.master_shipping_slip')


@section('content')

	@include('templates::supplier_shipping_slips.default.order_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::supplier_shipping_slips.default.order_css')

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
