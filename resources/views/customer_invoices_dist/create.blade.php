@extends('layouts.master')

@section('title') {{ l('Customer Invoices - Create') }} @parent @stop


@section('content')
 
            @if ( isset($customer->name_fiscal) )
              @include('customer_invoices._form')
            @else
              @include('customer_invoices.search_customer')
            @endif

@stop

{{-- ***************************************************************************************************** --}}

@section('styles')

   {!! HTML::style('assets/plugins/AutoComplete/styles.css') !!}

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

@stop

{{-- ***************************************************************************************************** --}}


@section('scripts')

            @if ( isset($customer->name_fiscal) )
              @include('customer_invoices.js.manage_invoice_js')
            @else
              @include('customer_invoices.js.search_customer_js')
            @endif

@stop