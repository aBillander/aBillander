{{-- File name after template name --}}

@extends('templates::customer_invoices.xtranat.master_invoice')


@section('content')

	@include('templates::customer_invoices.xtranat.invoice_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::customer_invoices.xtranat.invoice_css')

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
