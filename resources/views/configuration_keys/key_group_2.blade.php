@extends('layouts.master')

@section('title')
Configuración General
::@parent
@stop

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>Configuración</h2>
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


{!! Form::open(array('url' => 'configurationkeys', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}
  <input type="hidden" name="tab_index" value="{{$tab_index}}"/>
  <fieldset>
    <legend>Valores por Defecto</legend>

    <div class="form-group" {{ $errors->has('DEF_COUNTRY_NAME') ? 'has-error' : '' }}>
      <label for="DEF_COUNTRY_NAME" class="col-lg-4 control-label">País</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        {!! Form::text('DEF_COUNTRY_NAME', old('DEF_COUNTRY_NAME', isset($key_group) ? $key_group['DEF_COUNTRY_NAME'] : null), array('id' => 'DEF_COUNTRY_NAME', 'class' => 'form-control')) !!}
        {{ $errors->first('DEF_COUNTRY_NAME', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_ITEMS_PERPAGE') ? 'has-error' : '' }}>
      <label for="DEF_ITEMS_PERPAGE" class="col-lg-4 control-label">Items por Página</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_ITEMS_PERPAGE" name="DEF_ITEMS_PERPAGE" placeholder="" value="{{ Input::old('DEF_ITEMS_PERPAGE', isset($key_group) ? $key_group['DEF_ITEMS_PERPAGE'] : null) }}" />
        {{ $errors->first('DEF_ITEMS_PERPAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_ITEMS_PERAJAX') ? 'has-error' : '' }}>
      <label for="DEF_ITEMS_PERAJAX" class="col-lg-4 control-label">Items por llamada Ajax</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_ITEMS_PERAJAX" name="DEF_ITEMS_PERAJAX" placeholder="" value="{{ Input::old('DEF_ITEMS_PERAJAX', isset($key_group) ? $key_group['DEF_ITEMS_PERAJAX'] : null) }}" />
        {{ $errors->first('DEF_ITEMS_PERAJAX', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">Número de registros devuelto por una consulta Ajax.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_PERCENT_DECIMALS') ? 'has-error' : '' }}>
      <label for="DEF_PERCENT_DECIMALS" class="col-lg-4 control-label">Decimales en Porcentajes</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_PERCENT_DECIMALS" name="DEF_PERCENT_DECIMALS" placeholder="" value="{{ Input::old('DEF_PERCENT_DECIMALS', isset($key_group) ? $key_group['DEF_PERCENT_DECIMALS'] : null) }}" />
        {{ $errors->first('DEF_PERCENT_DECIMALS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_LANGUAGE') ? 'has-error' : '' }}>
      <label for="DEF_LANGUAGE" class="col-lg-4 control-label">Idioma</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_LANGUAGE" name="DEF_LANGUAGE" placeholder="" value="{{ Input::old('DEF_LANGUAGE', isset($key_group) ? $key_group['DEF_LANGUAGE'] : null) }}" />
        {{ $errors->first('DEF_LANGUAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_CURRENCY') ? 'has-error' : '' }}>
      <label for="DEF_CURRENCY" class="col-lg-4 control-label">Divisa</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_CURRENCY" name="DEF_CURRENCY" placeholder="" value="{{ Input::old('DEF_CURRENCY', isset($key_group) ? $key_group['DEF_CURRENCY'] : null) }}" />
        {{ $errors->first('DEF_CURRENCY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_WAREHOUSE') ? 'has-error' : '' }}>
      <label for="DEF_WAREHOUSE" class="col-lg-4 control-label">Almacén</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_WAREHOUSE', array('0' => '-- Seleccione --') + $warehouseList, Input::old('DEF_WAREHOUSE', isset($key_group) ? $key_group['DEF_WAREHOUSE'] : 0), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_WAREHOUSE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_CARRIER') ? 'has-error' : '' }}>
      <label for="DEF_CARRIER" class="col-lg-4 control-label">Transportista</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_CARRIER" name="DEF_CARRIER" placeholder="" value="{{ Input::old('DEF_CARRIER', isset($key_group) ? $key_group['DEF_CARRIER'] : null) }}" />
        {{ $errors->first('DEF_CARRIER', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_CUSTOMER_INVOICE_SEQUENCE') ? 'has-error' : '' }}>
      <label for="DEF_CUSTOMER_INVOICE_SEQUENCE" class="col-lg-4 control-label">Serie de Facturas Clientes</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_CUSTOMER_INVOICE_SEQUENCE" name="DEF_CUSTOMER_INVOICE_SEQUENCE" placeholder="" value="{{ Input::old('DEF_CUSTOMER_INVOICE_SEQUENCE', isset($key_group) ? $key_group['DEF_CUSTOMER_INVOICE_SEQUENCE'] : null) }}" />
        {{ $errors->first('DEF_CUSTOMER_INVOICE_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_CUSTOMER_INVOICE_TEMPLATE') ? 'has-error' : '' }}>
      <label for="DEF_CUSTOMER_INVOICE_TEMPLATE" class="col-lg-4 control-label">Plantilla Facturas Clientes</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_CUSTOMER_INVOICE_TEMPLATE" name="DEF_CUSTOMER_INVOICE_TEMPLATE" placeholder="" value="{{ Input::old('DEF_CUSTOMER_INVOICE_TEMPLATE', isset($key_group) ? $key_group['DEF_CUSTOMER_INVOICE_TEMPLATE'] : null) }}" />
        {{ $errors->first('DEF_CUSTOMER_INVOICE_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_CUSTOMER_PAYMENT_METHOD') ? 'has-error' : '' }}>
      <label for="DEF_CUSTOMER_PAYMENT_METHOD" class="col-lg-4 control-label">Forma de Pago Clientes</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_CUSTOMER_PAYMENT_METHOD" name="DEF_CUSTOMER_PAYMENT_METHOD" placeholder="" value="{{ Input::old('DEF_CUSTOMER_PAYMENT_METHOD', isset($key_group) ? $key_group['DEF_CUSTOMER_PAYMENT_METHOD'] : null) }}" />
        {{ $errors->first('DEF_CUSTOMER_PAYMENT_METHOD', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('DEF_CUSTOMER_PAYMENT_DAY') ? 'has-error' : '' }}>
      <label for="DEF_CUSTOMER_PAYMENT_DAY" class="col-lg-4 control-label">Día de Pago Clientes</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_CUSTOMER_PAYMENT_DAY" name="DEF_CUSTOMER_PAYMENT_DAY" placeholder="" value="{{ Input::old('DEF_CUSTOMER_PAYMENT_DAY', isset($key_group) ? $key_group['DEF_CUSTOMER_PAYMENT_DAY'] : null) }}" />
        {{ $errors->first('DEF_CUSTOMER_PAYMENT_DAY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>


    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <!-- button class="btn btn-default">Cancelar</button -->
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-hdd-o"></i>
                     &nbsp; Guardar
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
@stop