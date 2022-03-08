{{-- File name after template name --}}

@extends('templates::supplier_invoices.default.master_invoice')


@section('content')

	@include('templates::supplier_invoices.default.invoice_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::supplier_invoices.default.invoice_css')

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
