@extends('layouts.master')

@section('title') {{ l('Settings') }} @parent @stop

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

            <!-- div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">Datos generales</h3>
               </div -->
               <div class="panel-body well">


@if (\App\Configuration::isTrue('ENABLE_CUSTOMER_CENTER') )


{!! Form::open(array('url' => 'configurationkeys', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('Customer Center') }}</legend>
    


    <div class="form-group {{ $errors->has('ABCC_HEADER_TITLE') ? 'has-error' : '' }}">
      <label for="ABCC_HEADER_TITLE" class="col-lg-4 control-label">{!! l('ABCC_HEADER_TITLE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_HEADER_TITLE" name="ABCC_HEADER_TITLE" placeholder="" value="{{ old('ABCC_HEADER_TITLE', $key_group['ABCC_HEADER_TITLE']) }}" />
        {{ $errors->first('ABCC_HEADER_TITLE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_HEADER_TITLE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABCC_STOCK_THRESHOLD') ? 'has-error' : '' }}">
      <label for="ABCC_STOCK_THRESHOLD" class="col-lg-4 control-label">{!! l('ABCC_STOCK_THRESHOLD.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_STOCK_THRESHOLD" name="ABCC_STOCK_THRESHOLD" placeholder="" value="{{ old('ABCC_STOCK_THRESHOLD', $key_group['ABCC_STOCK_THRESHOLD']) }}" />
        {{ $errors->first('ABCC_STOCK_THRESHOLD', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_STOCK_THRESHOLD.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABCC_DEFAULT_PASSWORD') ? 'has-error' : '' }}">
      <label for="ABCC_DEFAULT_PASSWORD" class="col-lg-4 control-label">{!! l('ABCC_DEFAULT_PASSWORD.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_DEFAULT_PASSWORD" name="ABCC_DEFAULT_PASSWORD" placeholder="" value="{{ old('ABCC_DEFAULT_PASSWORD', $key_group['ABCC_DEFAULT_PASSWORD']) }}" />
        {{ $errors->first('ABCC_DEFAULT_PASSWORD', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_DEFAULT_PASSWORD.help') !!}</span>
      </div>
    </div>
    


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


@endif


               </div>

               <!-- div class="panel-footer text-right">
               </div>

            </div -->

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>

@endsection