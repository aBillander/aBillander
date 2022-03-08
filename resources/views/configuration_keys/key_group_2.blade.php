@extends('layouts.master')

@section('title') {{ l('Settings') }} @parent @endsection

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
    <legend>{{ l('Default Values') }}</legend>




    <div class="form-group {{ $errors->has('DEF_COMPANY') ? 'has-error' : '' }}">
      <label for="DEF_COMPANY" class="col-lg-4 control-label">{!! l('DEF_COMPANY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_COMPANY', $companyList, old('DEF_COMPANY', $key_group['DEF_COMPANY']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_COMPANY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_COMPANY.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_COUNTRY') ? 'has-error' : '' }}">
      <label for="DEF_COUNTRY" class="col-lg-4 control-label">{!! l('DEF_COUNTRY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_COUNTRY', $countryList, old('DEF_COUNTRY', $key_group['DEF_COUNTRY']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_COUNTRY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_COUNTRY.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CURRENCY') ? 'has-error' : '' }}">
      <label for="DEF_CURRENCY" class="col-lg-4 control-label">{!! l('DEF_CURRENCY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CURRENCY', $currencyList, old('DEF_CURRENCY', $key_group['DEF_CURRENCY']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CURRENCY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CURRENCY.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_QUOTATION_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_QUOTATION_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_QUOTATION_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_QUOTATION_SEQUENCE', $quotations_sequenceList, old('DEF_CUSTOMER_QUOTATION_SEQUENCE', $key_group['DEF_CUSTOMER_QUOTATION_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_QUOTATION_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_QUOTATION_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_QUOTATION_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_QUOTATION_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_QUOTATION_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_QUOTATION_TEMPLATE', $quotations_templateList, old('DEF_CUSTOMER_QUOTATION_TEMPLATE', $key_group['DEF_CUSTOMER_QUOTATION_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_QUOTATION_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_QUOTATION_TEMPLATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_ORDER_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_ORDER_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_ORDER_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_ORDER_SEQUENCE', $orders_sequenceList, old('DEF_CUSTOMER_ORDER_SEQUENCE', $key_group['DEF_CUSTOMER_ORDER_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_ORDER_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_ORDER_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_ORDER_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_ORDER_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_ORDER_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_ORDER_TEMPLATE', $orders_templateList, old('DEF_CUSTOMER_ORDER_TEMPLATE', $key_group['DEF_CUSTOMER_ORDER_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_ORDER_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_ORDER_TEMPLATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE', $shipping_slips_sequenceList, old('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE', $key_group['DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE', $shipping_slips_templateList, old('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE', $key_group['DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_INVOICE_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_INVOICE_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_INVOICE_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_INVOICE_SEQUENCE', $invoices_sequenceList, old('DEF_CUSTOMER_INVOICE_SEQUENCE', $key_group['DEF_CUSTOMER_INVOICE_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_INVOICE_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_INVOICE_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CUSTOMER_INVOICE_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_INVOICE_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_INVOICE_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_INVOICE_TEMPLATE', $invoices_templateList, old('DEF_CUSTOMER_INVOICE_TEMPLATE', $key_group['DEF_CUSTOMER_INVOICE_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_INVOICE_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_INVOICE_TEMPLATE.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('CUSTOMER_INVOICE_BANNER') ? 'has-error' : '' }}">
      <label for="CUSTOMER_INVOICE_BANNER" class="col-lg-4 control-label">{!! l('CUSTOMER_INVOICE_BANNER.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        <textarea class="form-control" rows="1" id="CUSTOMER_INVOICE_BANNER" name="CUSTOMER_INVOICE_BANNER">{{ old('CUSTOMER_INVOICE_BANNER', $key_group['CUSTOMER_INVOICE_BANNER']) }}</textarea>
        {{ $errors->first('CUSTOMER_INVOICE_BANNER', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('CUSTOMER_INVOICE_BANNER.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('CUSTOMER_INVOICE_TAX_LABEL') ? 'has-error' : '' }}">
      <label for="CUSTOMER_INVOICE_TAX_LABEL" class="col-lg-4 control-label">{!! l('CUSTOMER_INVOICE_TAX_LABEL.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        <input class="form-control" type="text" id="CUSTOMER_INVOICE_TAX_LABEL" name="CUSTOMER_INVOICE_TAX_LABEL" placeholder="" value="{{ old('CUSTOMER_INVOICE_TAX_LABEL', $key_group['CUSTOMER_INVOICE_TAX_LABEL']) }}" />
        {{ $errors->first('CUSTOMER_INVOICE_TAX_LABEL', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('CUSTOMER_INVOICE_TAX_LABEL.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('CUSTOMER_INVOICE_CAPTION') ? 'has-error' : '' }}">
      <label for="CUSTOMER_INVOICE_CAPTION" class="col-lg-4 control-label">{!! l('CUSTOMER_INVOICE_CAPTION.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        <textarea class="form-control" rows="4" id="CUSTOMER_INVOICE_CAPTION" name="CUSTOMER_INVOICE_CAPTION">{{ old('CUSTOMER_INVOICE_CAPTION', $key_group['CUSTOMER_INVOICE_CAPTION']) }}</textarea>
        {{ $errors->first('CUSTOMER_INVOICE_CAPTION', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('CUSTOMER_INVOICE_CAPTION.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('DEF_CUSTOMER_PAYMENT_METHOD') ? 'has-error' : '' }}">
      <label for="DEF_CUSTOMER_PAYMENT_METHOD" class="col-lg-4 control-label">{!! l('DEF_CUSTOMER_PAYMENT_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CUSTOMER_PAYMENT_METHOD', ['0' => l('-- Please, select --', [], 'layouts')] + $payment_methodList, old('DEF_CUSTOMER_PAYMENT_METHOD', $key_group['DEF_CUSTOMER_PAYMENT_METHOD']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CUSTOMER_PAYMENT_METHOD', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CUSTOMER_PAYMENT_METHOD.help') !!}</span>
      </div>
    </div>

    
    <div class="form-group {{ $errors->has('DEF_SHIPPING_METHOD') ? 'has-error' : '' }}">
      <label for="DEF_SHIPPING_METHOD" class="col-lg-4 control-label">{!! l('DEF_SHIPPING_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SHIPPING_METHOD', ['' => l('-- Please, select --', [], 'layouts')] + $shipping_methodList, old('DEF_SHIPPING_METHOD', $key_group['DEF_SHIPPING_METHOD']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SHIPPING_METHOD', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SHIPPING_METHOD.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('DEF_SUPPLIER_ORDER_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_ORDER_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_ORDER_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_ORDER_SEQUENCE', $supplier_orders_sequenceList, old('DEF_SUPPLIER_ORDER_SEQUENCE', $key_group['DEF_SUPPLIER_ORDER_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_ORDER_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_ORDER_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_SUPPLIER_ORDER_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_ORDER_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_ORDER_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_ORDER_TEMPLATE', $supplier_orders_templateList, old('DEF_SUPPLIER_ORDER_TEMPLATE', $key_group['DEF_SUPPLIER_ORDER_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_ORDER_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_ORDER_TEMPLATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE', $supplier_shipping_slips_sequenceList, old('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE', $key_group['DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_SHIPPING_SLIP_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE', $supplier_shipping_slips_templateList, old('DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE', $key_group['DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_SHIPPING_SLIP_TEMPLATE.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('DEF_SUPPLIER_INVOICE_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_INVOICE_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_INVOICE_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_INVOICE_SEQUENCE', $supplier_invoices_sequenceList, old('DEF_SUPPLIER_INVOICE_SEQUENCE', $key_group['DEF_SUPPLIER_INVOICE_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_INVOICE_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_INVOICE_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_SUPPLIER_INVOICE_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_INVOICE_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_INVOICE_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_INVOICE_TEMPLATE', $supplier_invoices_templateList, old('DEF_SUPPLIER_INVOICE_TEMPLATE', $key_group['DEF_SUPPLIER_INVOICE_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_INVOICE_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_INVOICE_TEMPLATE.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('DEF_SUPPLIER_PAYMENT_METHOD') ? 'has-error' : '' }}">
      <label for="DEF_SUPPLIER_PAYMENT_METHOD" class="col-lg-4 control-label">{!! l('DEF_SUPPLIER_PAYMENT_METHOD.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SUPPLIER_PAYMENT_METHOD', ['0' => l('-- Please, select --', [], 'layouts')] + $payment_methodList, old('DEF_SUPPLIER_PAYMENT_METHOD', $key_group['DEF_SUPPLIER_PAYMENT_METHOD']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SUPPLIER_PAYMENT_METHOD', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SUPPLIER_PAYMENT_METHOD.help') !!}</span>
      </div>
    </div>





    <div class="form-group {{ $errors->has('DEF_LANGUAGE') ? 'has-error' : '' }}">
      <label for="DEF_LANGUAGE" class="col-lg-4 control-label">{!! l('DEF_LANGUAGE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_LANGUAGE', $languageList, old('DEF_LANGUAGE', $key_group['DEF_LANGUAGE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_LANGUAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_LANGUAGE.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('DEF_CATEGORY') ? 'has-error' : '' }}">
      <label for="DEF_CATEGORY" class="col-lg-4 control-label">{!! l('DEF_CATEGORY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CATEGORY', $categoryList, old('DEF_CATEGORY', $key_group['DEF_CATEGORY']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CATEGORY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CATEGORY.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_MEASURE_UNIT_FOR_BOMS') ? 'has-error' : '' }}">
      <label for="DEF_MEASURE_UNIT_FOR_BOMS" class="col-lg-4 control-label">{!! l('DEF_MEASURE_UNIT_FOR_BOMS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_MEASURE_UNIT_FOR_BOMS', ['0' => l('-- Please, select --', [], 'layouts')] + $measure_unitList, old('DEF_MEASURE_UNIT_FOR_BOMS', $key_group['DEF_MEASURE_UNIT_FOR_BOMS']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_MEASURE_UNIT_FOR_BOMS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_MEASURE_UNIT_FOR_BOMS.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_MEASURE_UNIT_FOR_PRODUCTS') ? 'has-error' : '' }}">
      <label for="DEF_MEASURE_UNIT_FOR_PRODUCTS" class="col-lg-4 control-label">{!! l('DEF_MEASURE_UNIT_FOR_PRODUCTS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_MEASURE_UNIT_FOR_PRODUCTS', ['0' => l('-- Please, select --', [], 'layouts')] + $measure_unitList, old('DEF_MEASURE_UNIT_FOR_PRODUCTS', $key_group['DEF_MEASURE_UNIT_FOR_PRODUCTS']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_MEASURE_UNIT_FOR_PRODUCTS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_MEASURE_UNIT_FOR_PRODUCTS.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_OUTSTANDING_AMOUNT') ? 'has-error' : '' }}">
      <label for="DEF_OUTSTANDING_AMOUNT" class="col-lg-4 control-label">{!! l('DEF_OUTSTANDING_AMOUNT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_OUTSTANDING_AMOUNT" name="DEF_OUTSTANDING_AMOUNT" placeholder="" value="{{ old('DEF_OUTSTANDING_AMOUNT', $key_group['DEF_OUTSTANDING_AMOUNT']) }}" />
        {{ $errors->first('DEF_OUTSTANDING_AMOUNT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('DEF_OUTSTANDING_AMOUNT.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_CHEQUE_PAYMENT_TYPE') ? 'has-error' : '' }}">
      <label for="DEF_CHEQUE_PAYMENT_TYPE" class="col-lg-4 control-label">{!! l('DEF_CHEQUE_PAYMENT_TYPE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_CHEQUE_PAYMENT_TYPE', ['0' => l('-- Please, select --', [], 'layouts')] + $payment_typeList, old('DEF_CHEQUE_PAYMENT_TYPE', $key_group['DEF_CHEQUE_PAYMENT_TYPE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_CHEQUE_PAYMENT_TYPE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_CHEQUE_PAYMENT_TYPE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_SEPA_PAYMENT_TYPE') ? 'has-error' : '' }}">
      <label for="DEF_SEPA_PAYMENT_TYPE" class="col-lg-4 control-label">{!! l('DEF_SEPA_PAYMENT_TYPE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_SEPA_PAYMENT_TYPE', ['0' => l('-- Please, select --', [], 'layouts')] + $payment_typeList, old('DEF_SEPA_PAYMENT_TYPE', $key_group['DEF_SEPA_PAYMENT_TYPE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_SEPA_PAYMENT_TYPE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_SEPA_PAYMENT_TYPE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_TAX') ? 'has-error' : '' }}">
      <label for="DEF_TAX" class="col-lg-4 control-label">{!! l('DEF_TAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_TAX', ['0' => l('-- Please, select --', [], 'layouts')] + $taxList, old('DEF_TAX', $key_group['DEF_TAX']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_TAX', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_TAX.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_WAREHOUSE') ? 'has-error' : '' }}">
      <label for="DEF_WAREHOUSE" class="col-lg-4 control-label">{!! l('DEF_WAREHOUSE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_WAREHOUSE', $warehouseList, old('DEF_WAREHOUSE', $key_group['DEF_WAREHOUSE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_WAREHOUSE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_WAREHOUSE.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE') ? 'has-error' : '' }}">
      <label for="DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE" class="col-lg-4 control-label">{!! l('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE', $warehouse_shipping_slips_sequenceList, old('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE', $key_group['DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE') ? 'has-error' : '' }}">
      <label for="DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE" class="col-lg-4 control-label">{!! l('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE', $warehouse_shipping_slips_templateList, old('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE', $key_group['DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION" id="WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION_on" value="1" @if( old('WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION', $key_group['WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION" id="WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION_off" value="0" @if( !old('WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION', $key_group['WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('WAREHOUSE_SHIPPING_SLIPS_NEED_VALIDATION.help') !!}</span>
      </div>
    </div>




    <div class="form-group {{ $errors->has('DEF_LENGTH_UNIT') ? 'has-error' : '' }}">
      <label for="DEF_LENGTH_UNIT" class="col-lg-4 control-label">{!! l('DEF_LENGTH_UNIT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_LENGTH_UNIT', $length_unitList, old('DEF_LENGTH_UNIT', $key_group['DEF_LENGTH_UNIT']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_LENGTH_UNIT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_LENGTH_UNIT.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_VOLUME_UNIT') ? 'has-error' : '' }}">
      <label for="DEF_VOLUME_UNIT" class="col-lg-4 control-label">{!! l('DEF_VOLUME_UNIT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_VOLUME_UNIT', $volume_unitList, old('DEF_VOLUME_UNIT', $key_group['DEF_VOLUME_UNIT']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_VOLUME_UNIT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_VOLUME_UNIT.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_VOLUME_UNIT_CONVERSION_RATE') ? 'has-error' : '' }}">
      <label for="DEF_VOLUME_UNIT_CONVERSION_RATE" class="col-lg-4 control-label">{!! l('DEF_VOLUME_UNIT_CONVERSION_RATE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_VOLUME_UNIT_CONVERSION_RATE', $vu_conversion_rateList, old('DEF_VOLUME_UNIT_CONVERSION_RATE', $key_group['DEF_VOLUME_UNIT_CONVERSION_RATE']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_VOLUME_UNIT_CONVERSION_RATE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_VOLUME_UNIT_CONVERSION_RATE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_WEIGHT_UNIT') ? 'has-error' : '' }}">
      <label for="DEF_WEIGHT_UNIT" class="col-lg-4 control-label">{!! l('DEF_WEIGHT_UNIT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('DEF_WEIGHT_UNIT', $weight_unitList, old('DEF_WEIGHT_UNIT', $key_group['DEF_WEIGHT_UNIT']), array('class' => 'form-control')) !!}
        {{ $errors->first('DEF_WEIGHT_UNIT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('DEF_WEIGHT_UNIT.help') !!}</span>
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