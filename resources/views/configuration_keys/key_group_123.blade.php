@extends('layouts.master')

@section('title') {{ l('System Tools') }} @parent @endsection

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>{{ l('Settings') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

        @include('configuration_keys.key_groups')
      
      <div class="col-lg-10 col-md-10 col-sm-9">

               <div class="panel-body well">







  <fieldset>
    <legend>{{ l('System Tools') }}</legend>
    


    <div class="form-group">
      <label for="" class="col-lg-4 control-label">{!! l('SYSTOOL_CLEAR_CACHE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-6">
                <a href="{{ route('clear-cache') }}" class="btn btn-sm btn-success" title="{!! l('SYSTOOL_CLEAR_CACHE.name') !!}"><i class="fa fa-cog"></i> &nbsp;{{ l('Process', 'layouts') }}</a>
            </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('SYSTOOL_CLEAR_CACHE.help') !!}</span>
      </div>
    </div>


    


    <div class="form-group">
      <label for="" class="col-lg-4 control-label">

        {{ l('.env File Manager', 'envmanager') }} &nbsp; 
        <a href="{{ URL::to('envmanager') }}" title="{{l('Go to', [], 'layouts')}}" class="btn btn-sm btn-navy">
            <i class="fa fa-dropbox"></i>&nbsp; .env
        </a>

      </label>
      <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-6">

            </div>
        <div class="col-lg-6"> </div>
        </div>

      </div>
    </div>
    


  </fieldset>


               </div>

               <!-- div class="panel-footer text-right">
               </div>

            </div -->

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>

@endsection