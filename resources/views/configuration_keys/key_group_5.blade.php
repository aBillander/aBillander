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

    <div class="form-group {{ $errors->has('ABCC_EMAIL') ? 'has-error' : '' }}" style="display: none;">
      <label for="ABCC_EMAIL" class="col-lg-4 control-label">{!! l('ABCC_EMAIL.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_EMAIL" name="ABCC_EMAIL" placeholder="" value="{{ old('ABCC_EMAIL', $key_group['ABCC_EMAIL']) }}" />
        {{ $errors->first('ABCC_EMAIL', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_EMAIL.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABCC_EMAIL_NAME') ? 'has-error' : '' }}" style="display: none;">
      <label for="ABCC_EMAIL_NAME" class="col-lg-4 control-label">{!! l('ABCC_EMAIL_NAME.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_EMAIL_NAME" name="ABCC_EMAIL_NAME" placeholder="" value="{{ old('ABCC_EMAIL_NAME', $key_group['ABCC_EMAIL_NAME']) }}" />
        {{ $errors->first('ABCC_EMAIL_NAME', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_EMAIL_NAME.help') !!}</span>
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
      <label class="col-lg-4 control-label">{!! l('ABCC_LOGIN_REDIRECT.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_LOGIN_REDIRECT" id="ABCC_LOGIN_REDIRECT_customer.dashboard" value="customer.dashboard" @if( old('ABCC_LOGIN_REDIRECT', $key_group['ABCC_LOGIN_REDIRECT']) == 'customer.dashboard' ) checked="checked" @endif type="radio">
            {!! l('ABCC_LOGIN_REDIRECT.option.customer.dashboard') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_LOGIN_REDIRECT" id="ABCC_LOGIN_REDIRECT_abcc.customer.pricerules" value="abcc.customer.pricerules" @if( old('ABCC_LOGIN_REDIRECT', $key_group['ABCC_LOGIN_REDIRECT']) == 'abcc.customer.pricerules' ) checked="checked" @endif type="radio">
            {!! l('ABCC_LOGIN_REDIRECT.option.abcc.customer.pricerules') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_LOGIN_REDIRECT" id="ABCC_LOGIN_REDIRECT_abcc.catalogue.newproducts" value="abcc.catalogue.newproducts" @if( old('ABCC_LOGIN_REDIRECT', $key_group['ABCC_LOGIN_REDIRECT']) == 'abcc.catalogue.newproducts' ) checked="checked" @endif type="radio">
            {!! l('ABCC_LOGIN_REDIRECT.option.abcc.catalogue.newproducts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_LOGIN_REDIRECT" id="ABCC_LOGIN_REDIRECT_abcc.catalogue" value="abcc.catalogue" @if( old('ABCC_LOGIN_REDIRECT', $key_group['ABCC_LOGIN_REDIRECT']) == 'abcc.catalogue' ) checked="checked" @endif type="radio">
            {!! l('ABCC_LOGIN_REDIRECT.option.abcc.catalogue') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_LOGIN_REDIRECT" id="ABCC_LOGIN_REDIRECT_abcc.cart" value="abcc.cart" @if( old('ABCC_LOGIN_REDIRECT', $key_group['ABCC_LOGIN_REDIRECT']) == 'abcc.cart' ) checked="checked" @endif type="radio">
            {!! l('ABCC_LOGIN_REDIRECT.option.abcc.cart') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_LOGIN_REDIRECT" id="ABCC_LOGIN_REDIRECT_none" value="none" @if( old('ABCC_LOGIN_REDIRECT', $key_group['ABCC_LOGIN_REDIRECT']) == 'none' ) checked="checked" @endif type="radio">
            {!! l('ABCC_LOGIN_REDIRECT.option.none') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_LOGIN_REDIRECT.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_STOCK_SHOW.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_STOCK_SHOW" id="ABCC_STOCK_SHOW_none" value="none" @if( old('ABCC_STOCK_SHOW', $key_group['ABCC_STOCK_SHOW']) == 'none' ) checked="checked" @endif type="radio">
            {!! l('ABCC_STOCK_SHOW.option.none') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_STOCK_SHOW" id="ABCC_STOCK_SHOW_label" value="label" @if( old('ABCC_STOCK_SHOW', $key_group['ABCC_STOCK_SHOW']) == 'label' ) checked="checked" @endif type="radio">
            {!! l('ABCC_STOCK_SHOW.option.label') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_STOCK_SHOW" id="ABCC_STOCK_SHOW_amount" value="amount" @if( old('ABCC_STOCK_SHOW', $key_group['ABCC_STOCK_SHOW']) == 'amount' ) checked="checked" @endif type="radio">
            {!! l('ABCC_STOCK_SHOW.option.amount') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_STOCK_SHOW.help') !!}</span>
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




    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_OUT_OF_STOCK_PRODUCTS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_OUT_OF_STOCK_PRODUCTS" id="ABCC_OUT_OF_STOCK_PRODUCTS_hide" value="hide" @if( old('ABCC_OUT_OF_STOCK_PRODUCTS', $key_group['ABCC_OUT_OF_STOCK_PRODUCTS']) == 'hide' ) checked="checked" @endif type="radio">
            {!! l('ABCC_OUT_OF_STOCK_PRODUCTS.option.hide') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_OUT_OF_STOCK_PRODUCTS" id="ABCC_OUT_OF_STOCK_PRODUCTS_deny" value="deny" @if( old('ABCC_OUT_OF_STOCK_PRODUCTS', $key_group['ABCC_OUT_OF_STOCK_PRODUCTS']) == 'deny' ) checked="checked" @endif type="radio">
            {!! l('ABCC_OUT_OF_STOCK_PRODUCTS.option.deny') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_OUT_OF_STOCK_PRODUCTS" id="ABCC_OUT_OF_STOCK_PRODUCTS_allow" value="allow" @if( old('ABCC_OUT_OF_STOCK_PRODUCTS', $key_group['ABCC_OUT_OF_STOCK_PRODUCTS']) == 'allow' ) checked="checked" @endif type="radio">
            {!! l('ABCC_OUT_OF_STOCK_PRODUCTS.option.allow') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_OUT_OF_STOCK_PRODUCTS.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY" id="ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY_on" value="1" @if( old('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY', $key_group['ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY" id="ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY_off" value="0" @if( !old('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY', $key_group['ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY.help') !!}</span>
      </div>
    </div>
    
    
    <div class="form-group {{ $errors->has('ABCC_OUT_OF_STOCK_TEXT') ? 'has-error' : '' }}">
      <label for="ABCC_OUT_OF_STOCK_TEXT" class="col-lg-4 control-label">{!! l('ABCC_OUT_OF_STOCK_TEXT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_OUT_OF_STOCK_TEXT" name="ABCC_OUT_OF_STOCK_TEXT" placeholder="" value="{{ old('ABCC_OUT_OF_STOCK_TEXT', $key_group['ABCC_OUT_OF_STOCK_TEXT']) }}" />
        {{ $errors->first('ABCC_OUT_OF_STOCK_TEXT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_OUT_OF_STOCK_TEXT.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_ORDERS_NEED_VALIDATION.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_ORDERS_NEED_VALIDATION" id="ABCC_ORDERS_NEED_VALIDATION_on" value="1" @if( old('ABCC_ORDERS_NEED_VALIDATION', $key_group['ABCC_ORDERS_NEED_VALIDATION']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_ORDERS_NEED_VALIDATION" id="ABCC_ORDERS_NEED_VALIDATION_off" value="0" @if( !old('ABCC_ORDERS_NEED_VALIDATION', $key_group['ABCC_ORDERS_NEED_VALIDATION']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_ORDERS_NEED_VALIDATION.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_ENABLE_QUOTATIONS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_QUOTATIONS" id="ABCC_ENABLE_QUOTATIONS_on" value="1" @if( old('ABCC_ENABLE_QUOTATIONS', $key_group['ABCC_ENABLE_QUOTATIONS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_QUOTATIONS" id="ABCC_ENABLE_QUOTATIONS_off" value="0" @if( !old('ABCC_ENABLE_QUOTATIONS', $key_group['ABCC_ENABLE_QUOTATIONS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_ENABLE_QUOTATIONS.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_ENABLE_SHIPPING_SLIPS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_SHIPPING_SLIPS" id="ABCC_ENABLE_SHIPPING_SLIPS_on" value="1" @if( old('ABCC_ENABLE_SHIPPING_SLIPS', $key_group['ABCC_ENABLE_SHIPPING_SLIPS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_SHIPPING_SLIPS" id="ABCC_ENABLE_SHIPPING_SLIPS_off" value="0" @if( !old('ABCC_ENABLE_SHIPPING_SLIPS', $key_group['ABCC_ENABLE_SHIPPING_SLIPS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_ENABLE_SHIPPING_SLIPS.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_ENABLE_INVOICES.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_INVOICES" id="ABCC_ENABLE_INVOICES_on" value="1" @if( old('ABCC_ENABLE_INVOICES', $key_group['ABCC_ENABLE_INVOICES']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_INVOICES" id="ABCC_ENABLE_INVOICES_off" value="0" @if( !old('ABCC_ENABLE_INVOICES', $key_group['ABCC_ENABLE_INVOICES']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_ENABLE_INVOICES.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_ENABLE_MIN_ORDER.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_MIN_ORDER" id="ABCC_ENABLE_MIN_ORDER_on" value="1" @if( old('ABCC_ENABLE_MIN_ORDER', $key_group['ABCC_ENABLE_MIN_ORDER']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_MIN_ORDER" id="ABCC_ENABLE_MIN_ORDER_off" value="0" @if( !old('ABCC_ENABLE_MIN_ORDER', $key_group['ABCC_ENABLE_MIN_ORDER']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_ENABLE_MIN_ORDER.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('ABCC_MIN_ORDER_VALUE') ? 'has-error' : '' }}">
      <label for="ABCC_MIN_ORDER_VALUE" class="col-lg-4 control-label">{!! l('ABCC_MIN_ORDER_VALUE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_MIN_ORDER_VALUE" name="ABCC_MIN_ORDER_VALUE" placeholder="" value="{{ old('ABCC_MIN_ORDER_VALUE', $key_group['ABCC_MIN_ORDER_VALUE']) }}" />
        {{ $errors->first('ABCC_MIN_ORDER_VALUE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_MIN_ORDER_VALUE.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('ABCC_MAX_ORDER_VALUE') ? 'has-error' : '' }}">
      <label for="ABCC_MAX_ORDER_VALUE" class="col-lg-4 control-label">{!! l('ABCC_MAX_ORDER_VALUE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_MAX_ORDER_VALUE" name="ABCC_MAX_ORDER_VALUE" placeholder="" value="{{ old('ABCC_MAX_ORDER_VALUE', $key_group['ABCC_MAX_ORDER_VALUE']) }}" />
        {{ $errors->first('ABCC_MAX_ORDER_VALUE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_MAX_ORDER_VALUE.help') !!}</span>
      </div>
    </div>







    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_DISPLAY_PRICES_TAX_INC.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_DISPLAY_PRICES_TAX_INC" id="ABCC_DISPLAY_PRICES_TAX_INC_on" value="1" @if( old('ABCC_DISPLAY_PRICES_TAX_INC', $key_group['ABCC_DISPLAY_PRICES_TAX_INC']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_DISPLAY_PRICES_TAX_INC" id="ABCC_DISPLAY_PRICES_TAX_INC_off" value="0" @if( !old('ABCC_DISPLAY_PRICES_TAX_INC', $key_group['ABCC_DISPLAY_PRICES_TAX_INC']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_DISPLAY_PRICES_TAX_INC.help') !!}</span>
      </div>
    </div>





    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_ENABLE_NEW_PRODUCTS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_NEW_PRODUCTS" id="ABCC_ENABLE_NEW_PRODUCTS_on" value="1" @if( old('ABCC_ENABLE_NEW_PRODUCTS', $key_group['ABCC_ENABLE_NEW_PRODUCTS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_ENABLE_NEW_PRODUCTS" id="ABCC_ENABLE_NEW_PRODUCTS_off" value="0" @if( !old('ABCC_ENABLE_NEW_PRODUCTS', $key_group['ABCC_ENABLE_NEW_PRODUCTS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_ENABLE_NEW_PRODUCTS.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('ABCC_NBR_DAYS_NEW_PRODUCT') ? 'has-error' : '' }}">
      <label for="ABCC_NBR_DAYS_NEW_PRODUCT" class="col-lg-4 control-label">{!! l('ABCC_NBR_DAYS_NEW_PRODUCT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_NBR_DAYS_NEW_PRODUCT" name="ABCC_NBR_DAYS_NEW_PRODUCT" placeholder="" value="{{ old('ABCC_NBR_DAYS_NEW_PRODUCT', $key_group['ABCC_NBR_DAYS_NEW_PRODUCT']) }}" />
        {{ $errors->first('ABCC_NBR_DAYS_NEW_PRODUCT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_NBR_DAYS_NEW_PRODUCT.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ABCC_NBR_ITEMS_IS_QUANTITY.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ABCC_NBR_ITEMS_IS_QUANTITY" id="ABCC_NBR_ITEMS_IS_QUANTITY_quantity" value="quantity" @if( old('ABCC_NBR_ITEMS_IS_QUANTITY', $key_group['ABCC_NBR_ITEMS_IS_QUANTITY']) == 'quantity' ) checked="checked" @endif type="radio">
            {!! l('ABCC_NBR_ITEMS_IS_QUANTITY.option.quantity') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_NBR_ITEMS_IS_QUANTITY" id="ABCC_NBR_ITEMS_IS_QUANTITY_items" value="items" @if( old('ABCC_NBR_ITEMS_IS_QUANTITY', $key_group['ABCC_NBR_ITEMS_IS_QUANTITY']) == 'items' ) checked="checked" @endif type="radio">
            {!! l('ABCC_NBR_ITEMS_IS_QUANTITY.option.items') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ABCC_NBR_ITEMS_IS_QUANTITY" id="ABCC_NBR_ITEMS_IS_QUANTITY_value" value="value" @if( old('ABCC_NBR_ITEMS_IS_QUANTITY', $key_group['ABCC_NBR_ITEMS_IS_QUANTITY']) == 'value' ) checked="checked" @endif type="radio">
            {!! l('ABCC_NBR_ITEMS_IS_QUANTITY.option.value') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ABCC_NBR_ITEMS_IS_QUANTITY.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('ABCC_ITEMS_PERPAGE') ? 'has-error' : '' }}">
      <label for="ABCC_ITEMS_PERPAGE" class="col-lg-4 control-label">{!! l('ABCC_ITEMS_PERPAGE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_ITEMS_PERPAGE" name="ABCC_ITEMS_PERPAGE" placeholder="" value="{{ old('ABCC_ITEMS_PERPAGE', $key_group['ABCC_ITEMS_PERPAGE']) }}" />
        {{ $errors->first('ABCC_ITEMS_PERPAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_ITEMS_PERPAGE.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('ABCC_CART_PERSISTANCE') ? 'has-error' : '' }}">
      <label for="ABCC_CART_PERSISTANCE" class="col-lg-4 control-label">{!! l('ABCC_CART_PERSISTANCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_CART_PERSISTANCE" name="ABCC_CART_PERSISTANCE" placeholder="" value="{{ old('ABCC_CART_PERSISTANCE', $key_group['ABCC_CART_PERSISTANCE']) }}" />
        {{ $errors->first('ABCC_CART_PERSISTANCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_CART_PERSISTANCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABCC_DEFAULT_ORDER_TEMPLATE') ? 'has-error' : '' }}">
      <label for="ABCC_DEFAULT_ORDER_TEMPLATE" class="col-lg-4 control-label">{!! l('ABCC_DEFAULT_ORDER_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('ABCC_DEFAULT_ORDER_TEMPLATE', $orders_templateList, old('ABCC_DEFAULT_ORDER_TEMPLATE', $key_group['ABCC_DEFAULT_ORDER_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('ABCC_DEFAULT_ORDER_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_DEFAULT_ORDER_TEMPLATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABCC_ORDERS_SEQUENCE') ? 'has-error' : '' }}">
      <label for="ABCC_ORDERS_SEQUENCE" class="col-lg-4 control-label">{!! l('ABCC_ORDERS_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('ABCC_ORDERS_SEQUENCE', $orders_sequenceList, old('ABCC_ORDERS_SEQUENCE', $key_group['ABCC_ORDERS_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('ABCC_ORDERS_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_ORDERS_SEQUENCE.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group {{ $errors->has('ABCC_QUOTATIONS_SEQUENCE') ? 'has-error' : '' }}">
      <label for="ABCC_QUOTATIONS_SEQUENCE" class="col-lg-4 control-label">{!! l('ABCC_QUOTATIONS_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('ABCC_QUOTATIONS_SEQUENCE', $quotations_sequenceList, old('ABCC_QUOTATIONS_SEQUENCE', $key_group['ABCC_QUOTATIONS_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('ABCC_QUOTATIONS_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('ABCC_QUOTATIONS_SEQUENCE.help') !!}</span>
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


        {{-- Temporarily --}}
        @include('configuration_keys.key_group_51')


               <!-- div class="panel-footer text-right">
               </div>

            </div -->

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>

@endsection