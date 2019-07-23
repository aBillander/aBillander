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