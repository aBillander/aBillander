@extends('layouts.master')

@section('title') {{ l('Customer Invoices - Edit') }} @parent @stop


@section('content')
 
            @include('customer_invoices._form')

@stop

{{-- ***************************************************************************************************** --}}

@section('styles')

   {!! HTML::style('assets/plugins/AutoComplete/styles.css') !!}

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

@stop

{{-- ***************************************************************************************************** --}}


@section('scripts')

            @include('customer_invoices.js.manage_invoice_js')

@stop