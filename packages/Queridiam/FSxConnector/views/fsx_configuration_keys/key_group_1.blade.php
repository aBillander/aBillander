@extends('layouts.master')

@section('title') {{ l('FSx-Connector Settings') }} @parent @stop

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>{{ l('FactuSOL link Settings') }}</h2>
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


{!! Form::open(array('url' => 'fsx/fsxconfigurationkeys', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('General') }}</legend>



@if (\App\Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
    <div class="form-group {{ $errors->has('FSOL_WEB_CUSTOMER_CODE_BASE') ? 'has-error' : '' }}">
      <label for="FSOL_WEB_CUSTOMER_CODE_BASE" class="col-lg-4 control-label">{!! l('FSOL_WEB_CUSTOMER_CODE_BASE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_WEB_CUSTOMER_CODE_BASE" name="FSOL_WEB_CUSTOMER_CODE_BASE" placeholder="" value="{{ old('FSOL_WEB_CUSTOMER_CODE_BASE', $key_group['FSOL_WEB_CUSTOMER_CODE_BASE']) }}" />
        {{ $errors->first('FSOL_WEB_CUSTOMER_CODE_BASE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_WEB_CUSTOMER_CODE_BASE.help') !!}</span>
      </div>
    </div>
@endif

    <div class="form-group {{ $errors->has('FSOL_ABI_CUSTOMER_CODE_BASE') ? 'has-error' : '' }}">
      <label for="FSOL_ABI_CUSTOMER_CODE_BASE" class="col-lg-4 control-label">{!! l('FSOL_ABI_CUSTOMER_CODE_BASE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_ABI_CUSTOMER_CODE_BASE" name="FSOL_ABI_CUSTOMER_CODE_BASE" placeholder="" value="{{ old('FSOL_ABI_CUSTOMER_CODE_BASE', $key_group['FSOL_ABI_CUSTOMER_CODE_BASE']) }}" />
        {{ $errors->first('FSOL_ABI_CUSTOMER_CODE_BASE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_ABI_CUSTOMER_CODE_BASE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('FSOL_CBDCFG') ? 'has-error' : '' }}">
      <label for="FSOL_CBDCFG" class="col-lg-4 control-label">{!! l('FSOL_CBDCFG.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_CBDCFG" name="FSOL_CBDCFG" placeholder="" value="{{ old('FSOL_CBDCFG', $key_group['FSOL_CBDCFG']) }}" />
        {{ $errors->first('FSOL_CBDCFG', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_CBDCFG.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('FSOL_CIACFG') ? 'has-error' : '' }}">
      <label for="FSOL_CIACFG" class="col-lg-4 control-label">{!! l('FSOL_CIACFG.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_CIACFG" name="FSOL_CIACFG" placeholder="" value="{{ old('FSOL_CIACFG', $key_group['FSOL_CIACFG']) }}" />
        {{ $errors->first('FSOL_CIACFG', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_CIACFG.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('FSOL_CPVCFG') ? 'has-error' : '' }}">
      <label for="FSOL_CPVCFG" class="col-lg-4 control-label">{!! l('FSOL_CPVCFG.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_CPVCFG" name="FSOL_CPVCFG" placeholder="" value="{{ old('FSOL_CPVCFG', $key_group['FSOL_CPVCFG']) }}" />
        {{ $errors->first('FSOL_CPVCFG', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_CPVCFG.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('FSOL_CCLCFG') ? 'has-error' : '' }}">
      <label for="FSOL_CCLCFG" class="col-lg-4 control-label">{!! l('FSOL_CCLCFG.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_CCLCFG" name="FSOL_CCLCFG" placeholder="" value="{{ old('FSOL_CCLCFG', $key_group['FSOL_CCLCFG']) }}" />
        {{ $errors->first('FSOL_CCLCFG', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_CCLCFG.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('FSOL_CBRCFG') ? 'has-error' : '' }}">
      <label for="FSOL_CBRCFG" class="col-lg-4 control-label">{!! l('FSOL_CBRCFG.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_CBRCFG" name="FSOL_CBRCFG" placeholder="" value="{{ old('FSOL_CBRCFG', $key_group['FSOL_CBRCFG']) }}" />
        {{ $errors->first('FSOL_CBRCFG', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_CBRCFG.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_USE_LOCAL_DATABASE.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_USE_LOCAL_DATABASE" id="FSX_USE_LOCAL_DATABASE_on" value="1" @if( old('FSX_USE_LOCAL_DATABASE', $key_group['FSX_USE_LOCAL_DATABASE']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_USE_LOCAL_DATABASE" id="FSX_USE_LOCAL_DATABASE_off" value="0" @if( !old('FSX_USE_LOCAL_DATABASE', $key_group['FSX_USE_LOCAL_DATABASE']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_USE_LOCAL_DATABASE.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_FORCE_CUSTOMERS_DOWNLOAD.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_FORCE_CUSTOMERS_DOWNLOAD" id="FSX_FORCE_CUSTOMERS_DOWNLOAD_on" value="1" @if( old('FSX_FORCE_CUSTOMERS_DOWNLOAD', $key_group['FSX_FORCE_CUSTOMERS_DOWNLOAD']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_FORCE_CUSTOMERS_DOWNLOAD" id="FSX_FORCE_CUSTOMERS_DOWNLOAD_off" value="0" @if( !old('FSX_FORCE_CUSTOMERS_DOWNLOAD', $key_group['FSX_FORCE_CUSTOMERS_DOWNLOAD']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_FORCE_CUSTOMERS_DOWNLOAD.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS" id="FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS_on" value="1" @if( old('FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS', $key_group['FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS" id="FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS_off" value="0" @if( !old('FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS', $key_group['FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_ORDER_LINES_REFERENCE_CHECK.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_ORDER_LINES_REFERENCE_CHECK" id="FSX_ORDER_LINES_REFERENCE_CHECK_on" value="1" @if( old('FSX_ORDER_LINES_REFERENCE_CHECK', $key_group['FSX_ORDER_LINES_REFERENCE_CHECK']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_ORDER_LINES_REFERENCE_CHECK" id="FSX_ORDER_LINES_REFERENCE_CHECK_off" value="0" @if( !old('FSX_ORDER_LINES_REFERENCE_CHECK', $key_group['FSX_ORDER_LINES_REFERENCE_CHECK']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_ORDER_LINES_REFERENCE_CHECK.help') !!}</span>
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



               </div>

               <!-- div class="panel-footer text-right">
               </div>

            </div -->

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>

@endsection