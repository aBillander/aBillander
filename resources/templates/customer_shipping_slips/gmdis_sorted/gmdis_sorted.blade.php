{{-- File name after template name --}}

@extends('templates::customer_shipping_slips.gmdis_sorted.master_shipping_slip')


@section('content')

	@include('templates::customer_shipping_slips.gmdis_sorted.shipping_slip_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::customer_shipping_slips.gmdis_sorted.shipping_slip_css')

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
