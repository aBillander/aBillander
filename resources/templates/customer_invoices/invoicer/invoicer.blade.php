{{-- File name after template name --}}

@extends('templates::customer_invoices.invoicer.master_invoice')


@section('content')

	@include('templates::customer_invoices.invoicer.invoice_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::customer_invoices.invoicer.invoice_css')

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
