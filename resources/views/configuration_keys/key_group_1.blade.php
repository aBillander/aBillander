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