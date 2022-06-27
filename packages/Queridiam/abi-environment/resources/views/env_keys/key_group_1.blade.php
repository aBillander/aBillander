@extends('layouts.master')

@section('title') {{ l('aBi .env Keys') }} @parent @stop

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>{{ l('.env File Manager') }}</h2>
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>:_> </strong>
                        {{ l('Proceed with caution. If you enter inappropriate values, you can make the application unusable.') }}
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

        @include('envmanager::env_keys._key_groups')
      
      <div class="col-lg-10 col-md-10 col-sm-9">

            <!-- div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">Datos generales</h3>
               </div -->
               <div class="panel-body well">


{!! Form::open(array('url' => 'envmanager', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('SMTP Mail Settings') }} </legend>



@foreach( $key_group as $key => $value)

@php
$type="text";
if($key == 'MAIL_MAILER' && $value == 'smtp')
    $type="hidden";
@endphp

    <div class="form-group {{ $errors->has($key) ? 'has-error' : '' }}">
      <label for="{{ $key }}" class="col-lg-4 control-label">{!! l($key.'.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="{{ $type }}" id="{{ $key }}" name="{{ $key }}" placeholder="" value="{{ old($key, $value) }}" />
        {{ $errors->first($key, '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l($key.'.help') !!}</span>
      </div>
    </div>

@endforeach




    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <!-- button class="btn btn-default">Cancelar</button -->
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
          </button>
      </div>
    </div>
  </fieldset>
{!! Form::close() !!}



               </div>

               <!-- div class="panel-footer text-right">
               </div>

            </div -->

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>

@endsection