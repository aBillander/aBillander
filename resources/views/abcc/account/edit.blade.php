@extends('abcc.layouts.master')

@section('title') {{ l('My Account', 'abcc/account') }} @parent @endsection

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2><a href="{{ route('abcc.account.edit') }}">{{ l('My Account', 'abcc/account') }}</a> <span style="color: #cccccc;">/</span> {{ $customer->name_fiscal }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

        @include('abcc.account._left_menu')
      
      <div class="col-lg-10 col-md-10 col-sm-9">

        @include('abcc.account._panel_'.$tab_index)

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>

@endsection