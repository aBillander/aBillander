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
    <legend>{{ l('Other') }}</legend>



    <div class="form-group {{ $errors->has('DEF_ITEMS_PERAJAX') ? 'has-error' : '' }}">
      <label for="DEF_ITEMS_PERAJAX" class="col-lg-4 control-label">{!! l('DEF_ITEMS_PERAJAX.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_ITEMS_PERAJAX" name="DEF_ITEMS_PERAJAX" placeholder="" value="{{ old('DEF_ITEMS_PERAJAX', $key_group['DEF_ITEMS_PERAJAX']) }}" />
        {{ $errors->first('DEF_ITEMS_PERAJAX', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('DEF_ITEMS_PERAJAX.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_ITEMS_PERPAGE') ? 'has-error' : '' }}">
      <label for="DEF_ITEMS_PERPAGE" class="col-lg-4 control-label">{!! l('DEF_ITEMS_PERPAGE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_ITEMS_PERPAGE" name="DEF_ITEMS_PERPAGE" placeholder="" value="{{ old('DEF_ITEMS_PERPAGE', $key_group['DEF_ITEMS_PERPAGE']) }}" />
        {{ $errors->first('DEF_ITEMS_PERPAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('DEF_ITEMS_PERPAGE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_LOGS_PERPAGE') ? 'has-error' : '' }}">
      <label for="DEF_LOGS_PERPAGE" class="col-lg-4 control-label">{!! l('DEF_LOGS_PERPAGE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_LOGS_PERPAGE" name="DEF_LOGS_PERPAGE" placeholder="" value="{{ old('DEF_LOGS_PERPAGE', $key_group['DEF_LOGS_PERPAGE']) }}" />
        {{ $errors->first('DEF_LOGS_PERPAGE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('DEF_LOGS_PERPAGE.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_PERCENT_DECIMALS') ? 'has-error' : '' }}">
      <label for="DEF_PERCENT_DECIMALS" class="col-lg-4 control-label">{!! l('DEF_PERCENT_DECIMALS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_PERCENT_DECIMALS" name="DEF_PERCENT_DECIMALS" placeholder="" value="{{ old('DEF_PERCENT_DECIMALS', $key_group['DEF_PERCENT_DECIMALS']) }}" />
        {{ $errors->first('DEF_PERCENT_DECIMALS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('DEF_PERCENT_DECIMALS.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('DEF_QUANTITY_DECIMALS') ? 'has-error' : '' }}">
      <label for="DEF_QUANTITY_DECIMALS" class="col-lg-4 control-label">{!! l('DEF_QUANTITY_DECIMALS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="DEF_QUANTITY_DECIMALS" name="DEF_QUANTITY_DECIMALS" placeholder="" value="{{ old('DEF_QUANTITY_DECIMALS', $key_group['DEF_QUANTITY_DECIMALS']) }}" />
        {{ $errors->first('DEF_QUANTITY_DECIMALS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('DEF_QUANTITY_DECIMALS.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('SHOW_PRODUCTS_ACTIVE_ONLY.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="SHOW_PRODUCTS_ACTIVE_ONLY" id="SHOW_PRODUCTS_ACTIVE_ONLY_on" value="1" @if( old('SHOW_PRODUCTS_ACTIVE_ONLY', $key_group['SHOW_PRODUCTS_ACTIVE_ONLY']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="SHOW_PRODUCTS_ACTIVE_ONLY" id="SHOW_PRODUCTS_ACTIVE_ONLY_off" value="0" @if( !old('SHOW_PRODUCTS_ACTIVE_ONLY', $key_group['SHOW_PRODUCTS_ACTIVE_ONLY']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('SHOW_PRODUCTS_ACTIVE_ONLY.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group  hide ">
      <label class="col-lg-4 control-label">{!! l('SHOW_CUSTOMERS_ACTIVE_ONLY.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="SHOW_CUSTOMERS_ACTIVE_ONLY" id="SHOW_CUSTOMERS_ACTIVE_ONLY_on" value="1" @if( old('SHOW_CUSTOMERS_ACTIVE_ONLY', $key_group['SHOW_CUSTOMERS_ACTIVE_ONLY']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="SHOW_CUSTOMERS_ACTIVE_ONLY" id="SHOW_CUSTOMERS_ACTIVE_ONLY_off" value="0" @if( !old('SHOW_CUSTOMERS_ACTIVE_ONLY', $key_group['SHOW_CUSTOMERS_ACTIVE_ONLY']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('SHOW_CUSTOMERS_ACTIVE_ONLY.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('BUSINESS_NAME_TO_SHOW.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="BUSINESS_NAME_TO_SHOW" id="BUSINESS_NAME_TO_SHOW_fiscal" value="fiscal" @if( old('BUSINESS_NAME_TO_SHOW', $key_group['BUSINESS_NAME_TO_SHOW']) == 'fiscal' ) checked="checked" @endif type="radio">
            {!! l('BUSINESS_NAME_TO_SHOW.option.fiscal') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="BUSINESS_NAME_TO_SHOW" id="BUSINESS_NAME_TO_SHOW_commercial" value="commercial" @if( old('BUSINESS_NAME_TO_SHOW', $key_group['BUSINESS_NAME_TO_SHOW']) == 'commercial' ) checked="checked" @endif type="radio">
            {!! l('BUSINESS_NAME_TO_SHOW.option.commercial') !!}
          </label>
        </div>
        <span class="help-block">{!! l('BUSINESS_NAME_TO_SHOW.help') !!}</span>
      </div>
    </div>




    <div class="form-group {{ $errors->has('FILE_ALLOWED_EXTENSIONS') ? 'has-error' : '' }}">
      <label for="FILE_ALLOWED_EXTENSIONS" class="col-lg-4 control-label">{!! l('FILE_ALLOWED_EXTENSIONS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-10">
        <input class="form-control" type="text" id="FILE_ALLOWED_EXTENSIONS" name="FILE_ALLOWED_EXTENSIONS" placeholder="" value="{{ old('FILE_ALLOWED_EXTENSIONS', $key_group['FILE_ALLOWED_EXTENSIONS']) }}" />
        {{ $errors->first('FILE_ALLOWED_EXTENSIONS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FILE_ALLOWED_EXTENSIONS.help') !!}</span>
      </div>
    </div>




    <div class="form-group {{ $errors->has('CURRENCY_CONVERTER_API_KEY') ? 'has-error' : '' }}">
      <label for="CURRENCY_CONVERTER_API_KEY" class="col-lg-4 control-label">{!! l('CURRENCY_CONVERTER_API_KEY.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-10">
        <input class="form-control" type="text" id="CURRENCY_CONVERTER_API_KEY" name="CURRENCY_CONVERTER_API_KEY" placeholder="" value="{{ old('CURRENCY_CONVERTER_API_KEY', $key_group['CURRENCY_CONVERTER_API_KEY']) }}" />
        {{ $errors->first('CURRENCY_CONVERTER_API_KEY', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('CURRENCY_CONVERTER_API_KEY.help') !!}</span>
      </div>
    </div>




    <div class="form-group {{ $errors->has('ALLOW_IP_ADDRESSES') ? 'has-error' : '' }}">
      <label for="ALLOW_IP_ADDRESSES" class="col-lg-4 control-label">{!! l('ALLOW_IP_ADDRESSES.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-10">
        <input class="form-control" type="text" id="ALLOW_IP_ADDRESSES" name="ALLOW_IP_ADDRESSES" placeholder="" value="{{ old('ALLOW_IP_ADDRESSES', $key_group['ALLOW_IP_ADDRESSES']) }}" />
        {{ $errors->first('ALLOW_IP_ADDRESSES', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ALLOW_IP_ADDRESSES.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('MAX_DB_BACKUPS') ? 'has-error' : '' }}">
      <label for="MAX_DB_BACKUPS" class="col-lg-4 control-label">{!! l('MAX_DB_BACKUPS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="MAX_DB_BACKUPS" name="MAX_DB_BACKUPS" placeholder="" value="{{ old('MAX_DB_BACKUPS', $key_group['MAX_DB_BACKUPS']) }}" />
        {{ $errors->first('MAX_DB_BACKUPS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('MAX_DB_BACKUPS.help') !!}</span>
      </div>
    </div>

    
    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('MAX_DB_BACKUPS_ACTION.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="MAX_DB_BACKUPS_ACTION" id="MAX_DB_BACKUPS_ACTION_delete" value="delete" @if( old('MAX_DB_BACKUPS_ACTION', $key_group['MAX_DB_BACKUPS_ACTION']) == 'delete' ) checked="checked" @endif type="radio">
            {!! l('MAX_DB_BACKUPS_ACTION.option.delete') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MAX_DB_BACKUPS_ACTION" id="MAX_DB_BACKUPS_ACTION_email" value="email" @if( old('MAX_DB_BACKUPS_ACTION', $key_group['MAX_DB_BACKUPS_ACTION']) == 'email' ) checked="checked" @endif type="radio">
            {!! l('MAX_DB_BACKUPS_ACTION.option.email') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="MAX_DB_BACKUPS_ACTION" id="MAX_DB_BACKUPS_ACTION_nothing" value="nothing" @if( old('MAX_DB_BACKUPS_ACTION', $key_group['MAX_DB_BACKUPS_ACTION']) == 'nothing' ) checked="checked" @endif type="radio">
            {!! l('MAX_DB_BACKUPS_ACTION.option.nothing') !!}
          </label>
        </div>
        <span class="help-block">{!! l('MAX_DB_BACKUPS_ACTION.help') !!}</span>
      </div>
    </div>





    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('RECENT_SALES_CLASS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="RECENT_SALES_CLASS" id="RECENT_SALES_CLASS_CustomerOrder" value="CustomerOrder" @if( old('RECENT_SALES_CLASS', $key_group['RECENT_SALES_CLASS']) == 'CustomerOrder' ) checked="checked" @endif type="radio">
            {!! l('RECENT_SALES_CLASS.option.CustomerOrder') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="RECENT_SALES_CLASS" id="RECENT_SALES_CLASS_CustomerShippingSlip" value="CustomerShippingSlip" @if( old('RECENT_SALES_CLASS', $key_group['RECENT_SALES_CLASS']) == 'CustomerShippingSlip' ) checked="checked" @endif type="radio">
            {!! l('RECENT_SALES_CLASS.option.CustomerShippingSlip') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="RECENT_SALES_CLASS" id="RECENT_SALES_CLASS_CustomerInvoice" value="CustomerInvoice" @if( old('RECENT_SALES_CLASS', $key_group['RECENT_SALES_CLASS']) == 'CustomerInvoice' ) checked="checked" @endif type="radio">
            {!! l('RECENT_SALES_CLASS.option.CustomerInvoice') !!}
          </label>
        </div>
        <span class="help-block">{!! l('RECENT_SALES_CLASS.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('ABI_IMPERSONATE_TIMEOUT') ? 'has-error' : '' }}">
      <label for="ABI_IMPERSONATE_TIMEOUT" class="col-lg-4 control-label">{!! l('ABI_IMPERSONATE_TIMEOUT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABI_IMPERSONATE_TIMEOUT" name="ABI_IMPERSONATE_TIMEOUT" placeholder="" value="{{ old('ABI_IMPERSONATE_TIMEOUT', $key_group['ABI_IMPERSONATE_TIMEOUT']) }}" />
        {{ $errors->first('ABI_IMPERSONATE_TIMEOUT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABI_IMPERSONATE_TIMEOUT.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABI_TIMEOUT_OFFSET') ? 'has-error' : '' }}">
      <label for="ABI_TIMEOUT_OFFSET" class="col-lg-4 control-label">{!! l('ABI_TIMEOUT_OFFSET.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABI_TIMEOUT_OFFSET" name="ABI_TIMEOUT_OFFSET" placeholder="" value="{{ old('ABI_TIMEOUT_OFFSET', $key_group['ABI_TIMEOUT_OFFSET']) }}" />
        {{ $errors->first('ABI_TIMEOUT_OFFSET', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABI_TIMEOUT_OFFSET.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('ABI_MAX_ROUNDCYCLES') ? 'has-error' : '' }}">
      <label for="ABI_MAX_ROUNDCYCLES" class="col-lg-4 control-label">{!! l('ABI_MAX_ROUNDCYCLES.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABI_MAX_ROUNDCYCLES" name="ABI_MAX_ROUNDCYCLES" placeholder="" value="{{ old('ABI_MAX_ROUNDCYCLES', $key_group['ABI_MAX_ROUNDCYCLES']) }}" />
        {{ $errors->first('ABI_MAX_ROUNDCYCLES', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('ABI_MAX_ROUNDCYCLES.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('ENABLE_CRAZY_IVAN.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="ENABLE_CRAZY_IVAN" id="ENABLE_CRAZY_IVAN_on" value="1" @if( old('ENABLE_CRAZY_IVAN', $key_group['ENABLE_CRAZY_IVAN']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="ENABLE_CRAZY_IVAN" id="ENABLE_CRAZY_IVAN_off" value="0" @if( !old('ENABLE_CRAZY_IVAN', $key_group['ENABLE_CRAZY_IVAN']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('ENABLE_CRAZY_IVAN.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('TIMEZONE') ? 'has-error' : '' }}">
      <label for="TIMEZONE" class="col-lg-4 control-label">{!! l('TIMEZONE.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('TIMEZONE', ['Europe/Madrid' => 'Europe/Madrid'], old('TIMEZONE', $key_group['TIMEZONE']), array('class' => 'form-control')) !!}
        {{ $errors->first('TIMEZONE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('TIMEZONE.help') !!}</span>
      </div>
    </div>






    <div class="form-group {{ $errors->has('USE_CUSTOM_THEME') ? 'has-error' : '' }}">
      <label for="USE_CUSTOM_THEME" class="col-lg-4 control-label">{!! l('USE_CUSTOM_THEME.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('USE_CUSTOM_THEME', ['' => '-- '.l( 'None', [], 'layouts').' --'] + $themeList, old('USE_CUSTOM_THEME', $key_group['USE_CUSTOM_THEME']), array('class' => 'form-control')) !!}
        {{ $errors->first('USE_CUSTOM_THEME', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('USE_CUSTOM_THEME.help') !!}</span>
      </div>
    </div>



    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('DEVELOPER_MODE.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="DEVELOPER_MODE" id="DEVELOPER_MODE_on" value="1" @if( old('DEVELOPER_MODE', $key_group['DEVELOPER_MODE']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="DEVELOPER_MODE" id="DEVELOPER_MODE_off" value="0" @if( !old('DEVELOPER_MODE', $key_group['DEVELOPER_MODE']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('DEVELOPER_MODE.help') !!}</span>
      </div>
    </div>
    

    <div class="form-group {{ $errors->has('URL_ABILLANDER_DOCS') ? 'has-error' : '' }}">
      <label for="URL_ABILLANDER_DOCS" class="col-lg-4 control-label">{!! l('URL_ABILLANDER_DOCS.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="URL_ABILLANDER_DOCS" name="URL_ABILLANDER_DOCS" placeholder="" value="{{ old('URL_ABILLANDER_DOCS', $key_group['URL_ABILLANDER_DOCS']) }}" />
        {{ $errors->first('URL_ABILLANDER_DOCS', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('URL_ABILLANDER_DOCS.help') !!}</span>
      </div>
    </div>


    <div class="form-group {{ $errors->has('URL_ABILLANDER_SUPPORT') ? 'has-error' : '' }}">
      <label for="URL_ABILLANDER_SUPPORT" class="col-lg-4 control-label">{!! l('URL_ABILLANDER_SUPPORT.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="URL_ABILLANDER_SUPPORT" name="URL_ABILLANDER_SUPPORT" placeholder="" value="{{ old('URL_ABILLANDER_SUPPORT', $key_group['URL_ABILLANDER_SUPPORT']) }}" />
        {{ $errors->first('URL_ABILLANDER_SUPPORT', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('URL_ABILLANDER_SUPPORT.help') !!}</span>
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