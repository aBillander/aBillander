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




    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('USE_CUSTOM_THEME.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="USE_CUSTOM_THEME" id="USE_CUSTOM_THEME_on" value="1" @if( old('USE_CUSTOM_THEME', $key_group['USE_CUSTOM_THEME']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="USE_CUSTOM_THEME" id="USE_CUSTOM_THEME_off" value="0" @if( !old('USE_CUSTOM_THEME', $key_group['USE_CUSTOM_THEME']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('USE_CUSTOM_THEME.help') !!}</span>
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