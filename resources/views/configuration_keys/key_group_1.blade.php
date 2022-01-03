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


{!! Form::open(array('url' => 'configurationkeys', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('My Company') }}</legend>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ALLOW_PRODUCT_SUBCATEGORIES.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ALLOW_PRODUCT_SUBCATEGORIES" id="ALLOW_PRODUCT_SUBCATEGORIES_on" value="1" @if( old('ALLOW_PRODUCT_SUBCATEGORIES', $key_group['ALLOW_PRODUCT_SUBCATEGORIES']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ALLOW_PRODUCT_SUBCATEGORIES" id="ALLOW_PRODUCT_SUBCATEGORIES_off" value="0" @if( !old('ALLOW_PRODUCT_SUBCATEGORIES', $key_group['ALLOW_PRODUCT_SUBCATEGORIES']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ALLOW_PRODUCT_SUBCATEGORIES.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ALLOW_SALES_RISK_EXCEEDED.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_RISK_EXCEEDED" id="ALLOW_SALES_RISK_EXCEEDED_on" value="1" @if( old('ALLOW_SALES_RISK_EXCEEDED', $key_group['ALLOW_SALES_RISK_EXCEEDED']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_RISK_EXCEEDED" id="ALLOW_SALES_RISK_EXCEEDED_off" value="0" @if( !old('ALLOW_SALES_RISK_EXCEEDED', $key_group['ALLOW_SALES_RISK_EXCEEDED']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ALLOW_SALES_RISK_EXCEEDED.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ALLOW_SALES_WITHOUT_STOCK.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_WITHOUT_STOCK" id="ALLOW_SALES_WITHOUT_STOCK_on" value="1" @if( old('ALLOW_SALES_WITHOUT_STOCK', $key_group['ALLOW_SALES_WITHOUT_STOCK']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ALLOW_SALES_WITHOUT_STOCK" id="ALLOW_SALES_WITHOUT_STOCK_off" value="0" @if( !old('ALLOW_SALES_WITHOUT_STOCK', $key_group['ALLOW_SALES_WITHOUT_STOCK']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ALLOW_SALES_WITHOUT_STOCK.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('CUSTOMER_ORDERS_NEED_VALIDATION.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="CUSTOMER_ORDERS_NEED_VALIDATION" id="CUSTOMER_ORDERS_NEED_VALIDATION_on" value="1" @if( old('CUSTOMER_ORDERS_NEED_VALIDATION', $key_group['CUSTOMER_ORDERS_NEED_VALIDATION']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="CUSTOMER_ORDERS_NEED_VALIDATION" id="CUSTOMER_ORDERS_NEED_VALIDATION_off" value="0" @if( !old('CUSTOMER_ORDERS_NEED_VALIDATION', $key_group['CUSTOMER_ORDERS_NEED_VALIDATION']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('CUSTOMER_ORDERS_NEED_VALIDATION.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ALLOW_CUSTOMER_BACKORDERS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ALLOW_CUSTOMER_BACKORDERS" id="ALLOW_CUSTOMER_BACKORDERS_on" value="1" @if( old('ALLOW_CUSTOMER_BACKORDERS', $key_group['ALLOW_CUSTOMER_BACKORDERS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ALLOW_CUSTOMER_BACKORDERS" id="ALLOW_CUSTOMER_BACKORDERS_off" value="0" @if( !old('ALLOW_CUSTOMER_BACKORDERS', $key_group['ALLOW_CUSTOMER_BACKORDERS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ALLOW_CUSTOMER_BACKORDERS.help') !!}</span>
      </div>
    </div>

    <div class="form-group  hide ">
      <label class="col-lg-4 control-label">{!! l('ENABLE_COMBINATIONS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_COMBINATIONS" id="ENABLE_COMBINATIONS_on" value="1" @if( old('ENABLE_COMBINATIONS', $key_group['ENABLE_COMBINATIONS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_COMBINATIONS" id="ENABLE_COMBINATIONS_off" value="0" @if( !old('ENABLE_COMBINATIONS', $key_group['ENABLE_COMBINATIONS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_COMBINATIONS.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_ECOTAXES.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_ECOTAXES" id="ENABLE_ECOTAXES_on" value="1" @if( old('ENABLE_ECOTAXES', $key_group['ENABLE_ECOTAXES']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_ECOTAXES" id="ENABLE_ECOTAXES_off" value="0" @if( !old('ENABLE_ECOTAXES', $key_group['ENABLE_ECOTAXES']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_ECOTAXES.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('PRICES_ENTERED_WITH_ECOTAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="PRICES_ENTERED_WITH_ECOTAX" id="PRICES_ENTERED_WITH_ECOTAX_on" value="1" @if( old('PRICES_ENTERED_WITH_ECOTAX', $key_group['PRICES_ENTERED_WITH_ECOTAX']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="PRICES_ENTERED_WITH_ECOTAX" id="PRICES_ENTERED_WITH_ECOTAX_off" value="0" @if( !old('PRICES_ENTERED_WITH_ECOTAX', $key_group['PRICES_ENTERED_WITH_ECOTAX']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('PRICES_ENTERED_WITH_ECOTAX.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_CUSTOMER_CENTER.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_CUSTOMER_CENTER" id="ENABLE_CUSTOMER_CENTER_on" value="1" @if( old('ENABLE_CUSTOMER_CENTER', $key_group['ENABLE_CUSTOMER_CENTER']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_CUSTOMER_CENTER" id="ENABLE_CUSTOMER_CENTER_off" value="0" @if( !old('ENABLE_CUSTOMER_CENTER', $key_group['ENABLE_CUSTOMER_CENTER']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_CUSTOMER_CENTER.help') !!}</span>
      </div>
    </div>



    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_SALESREP_CENTER.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_SALESREP_CENTER" id="ENABLE_SALESREP_CENTER_on" value="1" @if( old('ENABLE_SALESREP_CENTER', $key_group['ENABLE_SALESREP_CENTER']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_SALESREP_CENTER" id="ENABLE_SALESREP_CENTER_off" value="0" @if( !old('ENABLE_SALESREP_CENTER', $key_group['ENABLE_SALESREP_CENTER']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_SALESREP_CENTER.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('SALESREP_COMMISSION_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="SALESREP_COMMISSION_METHOD" id="SALESREP_COMMISSION_METHOD_TAXEXC" value="TAXEXC" @if( old('SALESREP_COMMISSION_METHOD', $key_group['SALESREP_COMMISSION_METHOD']) == 'TAXEXC' ) checked="checked" @endif type="radio">
            {!! l('SALESREP_COMMISSION_METHOD.option.TAXEXC') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="SALESREP_COMMISSION_METHOD" id="SALESREP_COMMISSION_METHOD_TAXINC" value="TAXINC" @if( old('SALESREP_COMMISSION_METHOD', $key_group['SALESREP_COMMISSION_METHOD']) == 'TAXINC' ) checked="checked" @endif type="radio">
            {!! l('SALESREP_COMMISSION_METHOD.option.TAXINC') !!}
          </label>
        </div>
        <span class="help-block">{!! l('SALESREP_COMMISSION_METHOD.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_MCRM.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_MCRM" id="ENABLE_MCRM_on" value="1" @if( old('ENABLE_MCRM', $key_group['ENABLE_MCRM']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_MCRM" id="ENABLE_MCRM_off" value="0" @if( !old('ENABLE_MCRM', $key_group['ENABLE_MCRM']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_MCRM.help') !!}</span>
      </div>
    </div>


    
@if ( config('tenants.enable') )

        <input id="ENABLE_MANUFACTURING" name="ENABLE_MANUFACTURING" type="hidden" value="0">

@else

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_MANUFACTURING.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_MANUFACTURING" id="ENABLE_MANUFACTURING_on" value="1" @if( old('ENABLE_MANUFACTURING', $key_group['ENABLE_MANUFACTURING']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_MANUFACTURING" id="ENABLE_MANUFACTURING_off" value="0" @if( !old('ENABLE_MANUFACTURING', $key_group['ENABLE_MANUFACTURING']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_MANUFACTURING.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('MRP_WITH_STOCK.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="MRP_WITH_STOCK" id="MRP_WITH_STOCK_on" value="1" @if( old('MRP_WITH_STOCK', $key_group['MRP_WITH_STOCK']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MRP_WITH_STOCK" id="MRP_WITH_STOCK_off" value="0" @if( !old('MRP_WITH_STOCK', $key_group['MRP_WITH_STOCK']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('MRP_WITH_STOCK.help') !!}</span>
      </div>
    </div>

    <div class="form-group  hide ">
      <label class="col-lg-4 control-label">{!! l('MRP_WITH_ZERO_ORDERS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="MRP_WITH_ZERO_ORDERS" id="MRP_WITH_ZERO_ORDERS_on" value="1" @if( old('MRP_WITH_ZERO_ORDERS', $key_group['MRP_WITH_ZERO_ORDERS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MRP_WITH_ZERO_ORDERS" id="MRP_WITH_ZERO_ORDERS_off" value="0" @if( !old('MRP_WITH_ZERO_ORDERS', $key_group['MRP_WITH_ZERO_ORDERS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('MRP_WITH_ZERO_ORDERS.help') !!}</span>
      </div>
    </div>

@endif


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_LOTS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_LOTS" id="ENABLE_LOTS_on" value="1" @if( old('ENABLE_LOTS', $key_group['ENABLE_LOTS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_LOTS" id="ENABLE_LOTS_off" value="0" @if( !old('ENABLE_LOTS', $key_group['ENABLE_LOTS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_LOTS.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('PRINT_LOT_NUMBER_ON_DOCUMENTS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="PRINT_LOT_NUMBER_ON_DOCUMENTS" id="PRINT_LOT_NUMBER_ON_DOCUMENTS_on" value="1" @if( old('PRINT_LOT_NUMBER_ON_DOCUMENTS', $key_group['PRINT_LOT_NUMBER_ON_DOCUMENTS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="PRINT_LOT_NUMBER_ON_DOCUMENTS" id="PRINT_LOT_NUMBER_ON_DOCUMENTS_off" value="0" @if( !old('PRINT_LOT_NUMBER_ON_DOCUMENTS', $key_group['PRINT_LOT_NUMBER_ON_DOCUMENTS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('PRINT_LOT_NUMBER_ON_DOCUMENTS.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_WEBSHOP_CONNECTOR.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_WEBSHOP_CONNECTOR" id="ENABLE_WEBSHOP_CONNECTOR_on" value="1" @if( old('ENABLE_WEBSHOP_CONNECTOR', $key_group['ENABLE_WEBSHOP_CONNECTOR']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_WEBSHOP_CONNECTOR" id="ENABLE_WEBSHOP_CONNECTOR_off" value="0" @if( !old('ENABLE_WEBSHOP_CONNECTOR', $key_group['ENABLE_WEBSHOP_CONNECTOR']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_WEBSHOP_CONNECTOR.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_FSOL_CONNECTOR.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_FSOL_CONNECTOR" id="ENABLE_FSOL_CONNECTOR_on" value="1" @if( old('ENABLE_FSOL_CONNECTOR', $key_group['ENABLE_FSOL_CONNECTOR']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_FSOL_CONNECTOR" id="ENABLE_FSOL_CONNECTOR_off" value="0" @if( !old('ENABLE_FSOL_CONNECTOR', $key_group['ENABLE_FSOL_CONNECTOR']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_FSOL_CONNECTOR.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('SELL_ONLY_MANUFACTURED.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="SELL_ONLY_MANUFACTURED" id="SELL_ONLY_MANUFACTURED_on" value="1" @if( old('SELL_ONLY_MANUFACTURED', $key_group['SELL_ONLY_MANUFACTURED']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="SELL_ONLY_MANUFACTURED" id="SELL_ONLY_MANUFACTURED_off" value="0" @if( !old('SELL_ONLY_MANUFACTURED', $key_group['SELL_ONLY_MANUFACTURED']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('SELL_ONLY_MANUFACTURED.help') !!}</span>
      </div>
    </div>




    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('MARGIN_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="MARGIN_METHOD" id="MARGIN_METHOD_CST" value="CST" @if( old('MARGIN_METHOD', $key_group['MARGIN_METHOD']) == 'CST' ) checked="checked" @endif type="radio">
            {!! l('MARGIN_METHOD.option.CST') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MARGIN_METHOD" id="MARGIN_METHOD_PRC" value="PRC" @if( old('MARGIN_METHOD', $key_group['MARGIN_METHOD']) == 'PRC' ) checked="checked" @endif type="radio">
            {!! l('MARGIN_METHOD.option.PRC') !!}
          </label>
        </div>
        <span class="help-block">{!! l('MARGIN_METHOD.help') !!}</span>
      </div>
    </div>




    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('MARGIN_PRICE.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="MARGIN_PRICE" id="MARGIN_PRICE_STANDARD" value="STANDARD" @if( old('MARGIN_PRICE', $key_group['MARGIN_PRICE']) == 'STANDARD' ) checked="checked" @endif type="radio">
            {!! l('MARGIN_PRICE.option.STANDARD') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MARGIN_PRICE" id="MARGIN_PRICE_AVERAGE" value="AVERAGE" @if( old('MARGIN_PRICE', $key_group['MARGIN_PRICE']) == 'AVERAGE' ) checked="checked" @endif type="radio">
            {!! l('MARGIN_PRICE.option.AVERAGE') !!}
          </label>
        </div>
{{--
        <div class="radio">
          <label>
            <input name="MARGIN_PRICE" id="MARGIN_PRICE_CURRENT" value="CURRENT" @if( old('MARGIN_PRICE', $key_group['MARGIN_PRICE']) == 'CURRENT' ) checked="checked" @endif type="radio">
            {!! l('MARGIN_PRICE.option.CURRENT') !!}
          </label>
        </div>
--}}
        <span class="help-block">{!! l('MARGIN_PRICE.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('INCLUDE_SERVICE_LINES_IN_PROFIT.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="INCLUDE_SERVICE_LINES_IN_PROFIT" id="INCLUDE_SERVICE_LINES_IN_PROFIT_on" value="1" @if( old('INCLUDE_SERVICE_LINES_IN_PROFIT', $key_group['INCLUDE_SERVICE_LINES_IN_PROFIT']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="INCLUDE_SERVICE_LINES_IN_PROFIT" id="INCLUDE_SERVICE_LINES_IN_PROFIT_off" value="0" @if( !old('INCLUDE_SERVICE_LINES_IN_PROFIT', $key_group['INCLUDE_SERVICE_LINES_IN_PROFIT']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('INCLUDE_SERVICE_LINES_IN_PROFIT.help') !!}</span>
      </div>
    </div>



    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('INCLUDE_SHIPPING_COST_IN_PROFIT.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="INCLUDE_SHIPPING_COST_IN_PROFIT" id="INCLUDE_SHIPPING_COST_IN_PROFIT_on" value="1" @if( old('INCLUDE_SHIPPING_COST_IN_PROFIT', $key_group['INCLUDE_SHIPPING_COST_IN_PROFIT']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="INCLUDE_SHIPPING_COST_IN_PROFIT" id="INCLUDE_SHIPPING_COST_IN_PROFIT_off" value="0" @if( !old('INCLUDE_SHIPPING_COST_IN_PROFIT', $key_group['INCLUDE_SHIPPING_COST_IN_PROFIT']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('INCLUDE_SHIPPING_COST_IN_PROFIT.help') !!}</span>
      </div>
    </div>




    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('INVENTORY_VALUATION_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="INVENTORY_VALUATION_METHOD" id="INVENTORY_VALUATION_METHOD_STANDARD" value="STANDARD" @if( old('INVENTORY_VALUATION_METHOD', $key_group['INVENTORY_VALUATION_METHOD']) == 'STANDARD' ) checked="checked" @endif type="radio">
            {!! l('INVENTORY_VALUATION_METHOD.option.STANDARD') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="INVENTORY_VALUATION_METHOD" id="INVENTORY_VALUATION_METHOD_AVERAGE" value="AVERAGE" @if( old('INVENTORY_VALUATION_METHOD', $key_group['INVENTORY_VALUATION_METHOD']) == 'AVERAGE' ) checked="checked" @endif type="radio">
            {!! l('INVENTORY_VALUATION_METHOD.option.AVERAGE') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="INVENTORY_VALUATION_METHOD" id="INVENTORY_VALUATION_METHOD_CURRENT" value="CURRENT" @if( old('INVENTORY_VALUATION_METHOD', $key_group['INVENTORY_VALUATION_METHOD']) == 'CURRENT' ) checked="checked" @endif type="radio">
            {!! l('INVENTORY_VALUATION_METHOD.option.CURRENT') !!}
          </label>
        </div>
        <span class="help-block">{!! l('INVENTORY_VALUATION_METHOD.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('NEW_PRICE_LIST_POPULATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="NEW_PRICE_LIST_POPULATE" id="NEW_PRICE_LIST_POPULATE_on" value="1" @if( old('NEW_PRICE_LIST_POPULATE', $key_group['NEW_PRICE_LIST_POPULATE']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="NEW_PRICE_LIST_POPULATE" id="NEW_PRICE_LIST_POPULATE_off" value="0" @if( !old('NEW_PRICE_LIST_POPULATE', $key_group['NEW_PRICE_LIST_POPULATE']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('NEW_PRICE_LIST_POPULATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('NEW_PRODUCT_TO_ALL_PRICELISTS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="NEW_PRODUCT_TO_ALL_PRICELISTS" id="NEW_PRODUCT_TO_ALL_PRICELISTS_on" value="1" @if( old('NEW_PRODUCT_TO_ALL_PRICELISTS', $key_group['NEW_PRODUCT_TO_ALL_PRICELISTS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="NEW_PRODUCT_TO_ALL_PRICELISTS" id="NEW_PRODUCT_TO_ALL_PRICELISTS_off" value="0" @if( !old('NEW_PRODUCT_TO_ALL_PRICELISTS', $key_group['NEW_PRODUCT_TO_ALL_PRICELISTS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('NEW_PRODUCT_TO_ALL_PRICELISTS.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('PRICES_ENTERED_WITH_TAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="PRICES_ENTERED_WITH_TAX" id="PRICES_ENTERED_WITH_TAX_on" value="1" @if( old('PRICES_ENTERED_WITH_TAX', $key_group['PRICES_ENTERED_WITH_TAX']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="PRICES_ENTERED_WITH_TAX" id="PRICES_ENTERED_WITH_TAX_off" value="0" @if( !old('PRICES_ENTERED_WITH_TAX', $key_group['PRICES_ENTERED_WITH_TAX']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('PRICES_ENTERED_WITH_TAX.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('PRODUCT_NOT_IN_PRICELIST.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="PRODUCT_NOT_IN_PRICELIST" id="PRODUCT_NOT_IN_PRICELIST_block" value="block" @if( old('PRODUCT_NOT_IN_PRICELIST', $key_group['PRODUCT_NOT_IN_PRICELIST']) == 'block' ) checked="checked" @endif type="radio">
            {!! l('PRODUCT_NOT_IN_PRICELIST.option.block') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="PRODUCT_NOT_IN_PRICELIST" id="PRODUCT_NOT_IN_PRICELIST_pricelist" value="pricelist" @if( old('PRODUCT_NOT_IN_PRICELIST', $key_group['PRODUCT_NOT_IN_PRICELIST']) == 'pricelist' ) checked="checked" @endif type="radio">
            {!! l('PRODUCT_NOT_IN_PRICELIST.option.pricelist') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="PRODUCT_NOT_IN_PRICELIST" id="PRODUCT_NOT_IN_PRICELIST_product" value="product" @if( old('PRODUCT_NOT_IN_PRICELIST', $key_group['PRODUCT_NOT_IN_PRICELIST']) == 'product' ) checked="checked" @endif type="radio">
            {!! l('PRODUCT_NOT_IN_PRICELIST.option.product') !!}
          </label>
        </div>
        <span class="help-block">{!! l('PRODUCT_NOT_IN_PRICELIST.help') !!}</span>
      </div>
    </div>




    <div class="form-group {{ $errors->has('QUOTES_EXPIRE_AFTER') ? 'has-error' : '' }}">
      <label for="QUOTES_EXPIRE_AFTER" class="col-lg-4 control-label">{!! l('QUOTES_EXPIRE_AFTER.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="QUOTES_EXPIRE_AFTER" name="QUOTES_EXPIRE_AFTER" placeholder="" value="{{ old('QUOTES_EXPIRE_AFTER', $key_group['QUOTES_EXPIRE_AFTER']) }}" />
        {{ $errors->first('QUOTES_EXPIRE_AFTER', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('QUOTES_EXPIRE_AFTER.help') !!}</span>
      </div>
    </div>



    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ROUND_PRICES_WITH_TAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ROUND_PRICES_WITH_TAX" id="ROUND_PRICES_WITH_TAX_on" value="1" @if( old('ROUND_PRICES_WITH_TAX', $key_group['ROUND_PRICES_WITH_TAX']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ROUND_PRICES_WITH_TAX" id="ROUND_PRICES_WITH_TAX_off" value="0" @if( !old('ROUND_PRICES_WITH_TAX', $key_group['ROUND_PRICES_WITH_TAX']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ROUND_PRICES_WITH_TAX.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('DOCUMENT_ROUNDING_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="DOCUMENT_ROUNDING_METHOD" id="DOCUMENT_ROUNDING_METHOD_line" value="line" @if( old('DOCUMENT_ROUNDING_METHOD', $key_group['DOCUMENT_ROUNDING_METHOD']) == 'line' ) checked="checked" @endif type="radio">
            {!! l('DOCUMENT_ROUNDING_METHOD.option.line') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="DOCUMENT_ROUNDING_METHOD" id="DOCUMENT_ROUNDING_METHOD_total" value="total" @if( old('DOCUMENT_ROUNDING_METHOD', $key_group['DOCUMENT_ROUNDING_METHOD']) == 'total' ) checked="checked" @endif type="radio">
            {!! l('DOCUMENT_ROUNDING_METHOD.option.total') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="DOCUMENT_ROUNDING_METHOD" id="DOCUMENT_ROUNDING_METHOD_none" value="none" @if( old('DOCUMENT_ROUNDING_METHOD', $key_group['DOCUMENT_ROUNDING_METHOD']) == 'none' ) checked="checked" @endif type="radio">
            {!! l('DOCUMENT_ROUNDING_METHOD.option.none') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="DOCUMENT_ROUNDING_METHOD" id="DOCUMENT_ROUNDING_METHOD_custom" value="custom" @if( old('DOCUMENT_ROUNDING_METHOD', $key_group['DOCUMENT_ROUNDING_METHOD']) == 'custom' ) checked="checked" @endif type="radio">
            {!! l('DOCUMENT_ROUNDING_METHOD.option.custom') !!}
          </label>
        </div>
        <span class="help-block">{!! l('DOCUMENT_ROUNDING_METHOD.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('SKU_AUTOGENERATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="SKU_AUTOGENERATE" id="SKU_AUTOGENERATE_on" value="1" @if( old('SKU_AUTOGENERATE', $key_group['SKU_AUTOGENERATE']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="SKU_AUTOGENERATE" id="SKU_AUTOGENERATE_off" value="0" @if( !old('SKU_AUTOGENERATE', $key_group['SKU_AUTOGENERATE']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('SKU_AUTOGENERATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('TAX_BASED_ON_SHIPPING_ADDRESS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="TAX_BASED_ON_SHIPPING_ADDRESS" id="TAX_BASED_ON_SHIPPING_ADDRESS_on" value="1" @if( old('TAX_BASED_ON_SHIPPING_ADDRESS', $key_group['TAX_BASED_ON_SHIPPING_ADDRESS']) ) checked="checked" @endif type="radio">
            {!! l('TAX_BASED_ON_SHIPPING_ADDRESS.option.1') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="TAX_BASED_ON_SHIPPING_ADDRESS" id="TAX_BASED_ON_SHIPPING_ADDRESS_off" value="0" @if( !old('TAX_BASED_ON_SHIPPING_ADDRESS', $key_group['TAX_BASED_ON_SHIPPING_ADDRESS']) ) checked="checked" @endif type="radio">
            {!! l('TAX_BASED_ON_SHIPPING_ADDRESS.option.0') !!}
          </label>
        </div>
        <span class="help-block">{!! l('TAX_BASED_ON_SHIPPING_ADDRESS.help') !!}</span>
      </div>
    </div>{{-- woocommerce_tax_based_on :: 
    Customer billing address - shipping
    Customer shipping address (default) - billing
    Store/Company base address - base/company
 --}}


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('SUPPLIER_PRICES_ENTERED_WITH_TAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="SUPPLIER_PRICES_ENTERED_WITH_TAX" id="SUPPLIER_PRICES_ENTERED_WITH_TAX_on" value="1" @if( old('SUPPLIER_PRICES_ENTERED_WITH_TAX', $key_group['SUPPLIER_PRICES_ENTERED_WITH_TAX']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="SUPPLIER_PRICES_ENTERED_WITH_TAX" id="SUPPLIER_PRICES_ENTERED_WITH_TAX_off" value="0" @if( !old('SUPPLIER_PRICES_ENTERED_WITH_TAX', $key_group['SUPPLIER_PRICES_ENTERED_WITH_TAX']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('SUPPLIER_PRICES_ENTERED_WITH_TAX.help') !!}</span>
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