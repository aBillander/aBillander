@extends('layouts.master')

@section('title') {{ l('WooCommerce Connect - Configuration') }} @parent @stop

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>{{ l('WooCommerce Connect - Configuration') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

        @include('woo_connect::woo_configuration_keys._key_groups')
      
      <div class="col-lg-10 col-md-10 col-sm-9">

            <!-- div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">Datos generales</h3>
               </div -->
               <div class="panel-body well">


{!! Form::open(array('route' => 'wooconnect.configuration.taxes.update', 'class' => 'form' )) !!}


  {{-- !! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !! --}}

  <fieldset>
    <legend>{{ l('WooCommerce Connect - Taxes Dictionary') }}</legend>



@foreach ( $wootaxes as $tax )
<div class="row">

  <div class="form-group col-lg-6 col-md-6 col-sm-6">
      {{-- !! Form::label($tax['slug'], $tax['name']) !! --}}
      <div class="text-right" title="{{' ['.$tax['slug'].'] '}}"><label>{{ $tax['name'] }}</label></div>
      {{-- !! Form::text($tax['slug'], null, array('class' => 'form-control')) !! --}}
  </div>
  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('dic.'.$dic[$tax['slug']]) ? 'has-error' : '' }}">
        {!! Form::select('dic['.$dic[$tax['slug']].']', array('' => l('-- Please, select --', [], 'layouts')) + $taxList, $dic_val[$tax['slug']], array('class' => 'form-control')) !!}
      {!! $errors->first('dic.'.$dic[$tax['slug']], '<span class="help-block">:message</span>') !!}
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