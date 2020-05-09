@extends('layouts.master')

@section('title') {{ l('WooConnect Settings') }} @parent @stop

@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
            </div>
            <h2>{{ l('WooCommerce link Settings') }} <span style="color: #cccccc;">::</span> <a href="#">{{ config('woocommerce.store_url') }}</a></h2>
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


{!! Form::open(array('url' => 'wooc/wooconnect/configurationkeys', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('General') }}</legend>


{{--
    <div class="form-group {{ $errors->has('WOOC_STORE_URL') ? 'has-error' : '' }}">
      <label for="WOOC_STORE_URL" class="col-lg-4 control-label">{!! l('WOOC_STORE_URL.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="WOOC_STORE_URL" name="WOOC_STORE_URL" placeholder="" value="{{ old('WOOC_STORE_URL', $key_group['WOOC_STORE_URL']) }}" />
        {{ $errors->first('WOOC_STORE_URL', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_STORE_URL.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_CONSUMER_KEY') ? 'has-error' : '' }}">
      <label for="WOOC_CONSUMER_KEY" class="col-lg-4 control-label">{!! l('WOOC_CONSUMER_KEY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="WOOC_CONSUMER_KEY" name="WOOC_CONSUMER_KEY" placeholder="" value="{{ old('WOOC_CONSUMER_KEY', $key_group['WOOC_CONSUMER_KEY']) }}" />
        {{ $errors->first('WOOC_CONSUMER_KEY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_CONSUMER_KEY.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_CONSUMER_SECRET') ? 'has-error' : '' }}">
      <label for="WOOC_CONSUMER_SECRET" class="col-lg-4 control-label">{!! l('WOOC_CONSUMER_SECRET.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="WOOC_CONSUMER_SECRET" name="WOOC_CONSUMER_SECRET" placeholder="" value="{{ old('WOOC_CONSUMER_SECRET', $key_group['WOOC_CONSUMER_SECRET']) }}" />
        {{ $errors->first('WOOC_CONSUMER_SECRET', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_CONSUMER_SECRET.help') !!}</span>
      </div>
    </div>
--}}



    <div class="form-group {{ $errors->has('WOOC_DEF_LANGUAGE') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_LANGUAGE" class="col-lg-4 control-label">{!! l('WOOC_DEF_LANGUAGE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_LANGUAGE', $languageList, old('WOOC_DEF_LANGUAGE', $key_group['WOOC_DEF_LANGUAGE']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_LANGUAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_LANGUAGE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_DECIMAL_PLACES') ? 'has-error' : '' }}">
      <label for="WOOC_DECIMAL_PLACES" class="col-lg-4 control-label">{!! l('WOOC_DECIMAL_PLACES.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DECIMAL_PLACES', [2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', ], old('WOOC_DECIMAL_PLACES', $key_group['WOOC_DECIMAL_PLACES']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DECIMAL_PLACES', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DECIMAL_PLACES.help') !!}</span>
      </div>
    </div>

{{--
    <div class="form-group {{ $errors->has('WOOC_DEF_CURRENCY') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_CURRENCY" class="col-lg-4 control-label">{!! l('WOOC_DEF_CURRENCY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_CURRENCY', $currencyList, old('WOOC_DEF_CURRENCY', $key_group['WOOC_DEF_CURRENCY']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_CURRENCY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_CURRENCY.help') !!}</span>
      </div>
    </div>
--}}

    <div class="form-group {{ $errors->has('WOOC_DEF_CUSTOMER_GROUP') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_CUSTOMER_GROUP" class="col-lg-4 control-label">{!! l('WOOC_DEF_CUSTOMER_GROUP.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_CUSTOMER_GROUP', ['0' => l('-- Please, select --', [], 'layouts')] + $customer_groupList, old('WOOC_DEF_CUSTOMER_GROUP', $key_group['WOOC_DEF_CUSTOMER_GROUP']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_CUSTOMER_GROUP', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_CUSTOMER_GROUP.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_DEF_CUSTOMER_PRICE_LIST') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_CUSTOMER_PRICE_LIST" class="col-lg-4 control-label">{!! l('WOOC_DEF_CUSTOMER_PRICE_LIST.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_CUSTOMER_PRICE_LIST', ['0' => l('-- Please, select --', [], 'layouts')] + $price_listList, old('WOOC_DEF_CUSTOMER_PRICE_LIST', $key_group['WOOC_DEF_CUSTOMER_PRICE_LIST']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_CUSTOMER_PRICE_LIST', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_CUSTOMER_PRICE_LIST.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_DEF_WAREHOUSE') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_WAREHOUSE" class="col-lg-4 control-label">{!! l('WOOC_DEF_WAREHOUSE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_WAREHOUSE', $warehouseList, old('WOOC_DEF_WAREHOUSE', $key_group['WOOC_DEF_WAREHOUSE']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_WAREHOUSE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_WAREHOUSE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_DEF_ORDERS_SEQUENCE') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_ORDERS_SEQUENCE" class="col-lg-4 control-label">{!! l('WOOC_DEF_ORDERS_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_ORDERS_SEQUENCE', $orders_sequenceList, old('WOOC_DEF_ORDERS_SEQUENCE', $key_group['WOOC_DEF_ORDERS_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_ORDERS_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_ORDERS_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_DEF_SHIPPING_TAX') ? 'has-error' : '' }}">
      <label for="WOOC_DEF_SHIPPING_TAX" class="col-lg-4 control-label">{!! l('WOOC_DEF_SHIPPING_TAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('WOOC_DEF_SHIPPING_TAX', $taxList, old('WOOC_DEF_SHIPPING_TAX', $key_group['WOOC_DEF_SHIPPING_TAX']), array('class' => 'form-control')) !!}
        {{ $errors->first('WOOC_DEF_SHIPPING_TAX', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_DEF_SHIPPING_TAX.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_ORDER_NIF_META') ? 'has-error' : '' }}">
      <label for="WOOC_ORDER_NIF_META" class="col-lg-4 control-label">{!! l('WOOC_ORDER_NIF_META.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="WOOC_ORDER_NIF_META" name="WOOC_ORDER_NIF_META" placeholder="" value="{{ old('WOOC_ORDER_NIF_META', $key_group['WOOC_ORDER_NIF_META']) }}" />
        {{ $errors->first('WOOC_ORDER_NIF_META', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_ORDER_NIF_META.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('WOOC_ORDERS_PER_PAGE') ? 'has-error' : '' }}">
      <label for="WOOC_ORDERS_PER_PAGE" class="col-lg-4 control-label">{!! l('WOOC_ORDERS_PER_PAGE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="WOOC_ORDERS_PER_PAGE" name="WOOC_ORDERS_PER_PAGE" placeholder="" value="{{ old('WOOC_ORDERS_PER_PAGE', $key_group['WOOC_ORDERS_PER_PAGE']) }}" />
        {{ $errors->first('WOOC_ORDERS_PER_PAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('WOOC_ORDERS_PER_PAGE.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('WOOC_USE_LOCAL_PRODUCT_NAME.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="WOOC_USE_LOCAL_PRODUCT_NAME" id="WOOC_USE_LOCAL_PRODUCT_NAME_on" value="1" @if( old('WOOC_USE_LOCAL_PRODUCT_NAME', $key_group['WOOC_USE_LOCAL_PRODUCT_NAME']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="WOOC_USE_LOCAL_PRODUCT_NAME" id="WOOC_USE_LOCAL_PRODUCT_NAME_off" value="0" @if( !old('WOOC_USE_LOCAL_PRODUCT_NAME', $key_group['WOOC_USE_LOCAL_PRODUCT_NAME']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('WOOC_USE_LOCAL_PRODUCT_NAME.help') !!}</span>
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