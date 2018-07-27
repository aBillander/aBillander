@extends('layouts.master')

@section('title') {{ l('FSx-Connector - Configuration') }} @parent @stop

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>{{ l('FSx-Connector - Configuration') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

        @include('fsx_connector::fsx_configuration_keys._key_groups')
      
      <div class="col-lg-10 col-md-10 col-sm-9">

            <!-- div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">Datos generales</h3>
               </div -->
               <div class="panel-body well">


{!! Form::open(array('route' => 'fsx.configuration.taxes.update', 'class' => 'form' )) !!}


  {{-- !! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !! --}}

  <fieldset>
    <legend>{{ l('FSx-Connector - Taxes Dictionary') }}</legend>



@foreach ( $key_group as $key => $val )

<div class="row">

  <div class="form-group col-lg-6 col-md-6 col-sm-6">

      <div class="text-right"><label for="{{ $key }}">{!! l($key.'.name') !!}</label></div>

  </div>
  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('dic.'.$key) ? 'has-error' : '' }}">
        {!! Form::select($key, array('' => l('-- Please, select --', [], 'layouts')) + $taxList, old($key, $val), array('class' => 'form-control', 'id' => $key)) !!}
      {!! $errors->first($key, '<span class="help-block">:message</span>') !!}
        <span class="help-block">{!! l($key.'.help',['fsol_value' => $key_percent[$key]]) !!}</span>
    </div>

</div>

@endforeach


    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <!-- button class="btn btn-default">Cancelar</button -->
        <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.form.submit();">
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