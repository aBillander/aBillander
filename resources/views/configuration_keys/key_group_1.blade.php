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
    <legend>Mi Empresa</legend>

    <div class="form-group">
      <label class="col-lg-4 control-label">Método para calcular el Margen</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="MARGIN_METHOD" id="MARGIN_METHOD_1" value="CST" @if(old('MARGIN_METHOD', isset($key_group) ? $key_group['MARGIN_METHOD'] : 'CST')=='CST') checked="checked" @endif type="radio">
            Sobre el Precio de Coste
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MARGIN_METHOD" id="MARGIN_METHOD_2" value="PRC" @if(old('MARGIN_METHOD', isset($key_group) ? $key_group['MARGIN_METHOD'] : 'CST')!='CST') checked="checked" @endif type="radio">
            Sobre el Precio de Venta
          </label>
        </div>
        <span class="help-block">CST: Margen sobre el precio de coste; PRC: sobre el precio de venta.</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">Permitir Ventas sin Stock</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_WITHOUT_STOCK" id="ALLOW_SALES_WITHOUT_STOCK_1" value="1" @if(old('ALLOW_SALES_WITHOUT_STOCK', isset($key_group) ? $key_group['ALLOW_SALES_WITHOUT_STOCK'] : 0)>0) checked="checked" @endif type="radio">
            Sí
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_WITHOUT_STOCK" id="ALLOW_SALES_WITHOUT_STOCK_2" value="0" @if(old('ALLOW_SALES_WITHOUT_STOCK', isset($key_group) ? $key_group['ALLOW_SALES_WITHOUT_STOCK'] : 0)==0) checked="checked" @endif type="radio">
            No
          </label>
        </div>
        <span class="help-block"> </span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">Permitir Ventas con Riesgo excedido</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_RISK_EXCEEDED" id="ALLOW_SALES_RISK_EXCEEDED_1" value="1" @if(old('ALLOW_SALES_RISK_EXCEEDED', isset($key_group) ? $key_group['ALLOW_SALES_RISK_EXCEEDED'] : 0)>0) checked="checked" @endif type="radio">
            Sí
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_RISK_EXCEEDED" id="ALLOW_SALES_RISK_EXCEEDED_2" value="0" @if(old('ALLOW_SALES_RISK_EXCEEDED', isset($key_group) ? $key_group['ALLOW_SALES_RISK_EXCEEDED'] : 0)==0) checked="checked" @endif type="radio">
            No
          </label>
        </div>
        <span class="help-block">Permitir Ventas a Clientes con el Riesgo excedido.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('SUPPORT_CENTER_EMAIL') ? 'has-error' : '' }}>
      <label for="SUPPORT_CENTER_EMAIL" class="col-lg-4 control-label">Email de Soporte</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="SUPPORT_CENTER_EMAIL" name="SUPPORT_CENTER_EMAIL" placeholder="" value="{{ old('SUPPORT_CENTER_EMAIL', isset($key_group) ? $key_group['SUPPORT_CENTER_EMAIL'] : null) }}" />
        {{ $errors->first('SUPPORT_CENTER_EMAIL', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">Dirección de correo electrónico para obtener soporte.</span>
      </div>
    </div>

    <div class="form-group" {{ $errors->has('SUPPORT_CENTER_NAME') ? 'has-error' : '' }}>
      <label for="SUPPORT_CENTER_NAME" class="col-lg-4 control-label">Nombre para Email de Soporte</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="SUPPORT_CENTER_NAME" name="SUPPORT_CENTER_NAME" placeholder="" value="{{ old('SUPPORT_CENTER_NAME', isset($key_group) ? $key_group['SUPPORT_CENTER_NAME'] : null) }}" />
        {{ $errors->first('SUPPORT_CENTER_NAME', '<span class="help-block">:message</span>') }}
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