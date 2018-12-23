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