{{-- File name after template name --}}

@extends('templates::customer_quotations.default.master_quotation')


@section('content')

	@include('templates::customer_quotations.default.quotation_body')

@endsection



{{-- *************************************** --}}



@section('styles') @parent

<style>

	@include('templates::customer_quotations.default.quotation_css')

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
