{{-- File name after template name --}}

@extends('templates::customer_invoices.gmdis.master_invoice')


@section('content')

	@include('templates::customer_invoices.gmdis.invoice_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::customer_invoices.gmdis.invoice_css')

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
