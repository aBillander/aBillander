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


@if (AbiConfiguration::get('SKU_AUTOGENERATE') )


{!! Form::open(array('url' => 'configurationkeys', 'id' => 'key_group_'.intval($tab_index), 'name' => 'key_group_'.intval($tab_index), 'class' => 'form-horizontal')) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('Auto-SKU') }}</legend>
    


    <div class="form-group {{ $errors->has('SKU_PREFIX_LENGTH') ? 'has-error' : '' }}">
      <label for="SKU_PREFIX_LENGTH" class="col-lg-4 control-label">{!! l('SKU_PREFIX_LENGTH.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="SKU_PREFIX_LENGTH" name="SKU_PREFIX_LENGTH" placeholder="" value="{{ old('SKU_PREFIX_LENGTH', $key_group['SKU_PREFIX_LENGTH']) }}" />
        {{ $errors->first('SKU_PREFIX_LENGTH', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('SKU_PREFIX_LENGTH.help') !!}</span>
      </div>
    </div>
    
    <div class="form-group {{ $errors->has('SKU_PREFIX_OFFSET') ? 'has-error' : '' }}">
      <label for="SKU_PREFIX_OFFSET" class="col-lg-4 control-label">{!! l('SKU_PREFIX_OFFSET.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="SKU_PREFIX_OFFSET" name="SKU_PREFIX_OFFSET" placeholder="" value="{{ old('SKU_PREFIX_OFFSET', $key_group['SKU_PREFIX_OFFSET']) }}" />
        {{ $errors->first('SKU_PREFIX_OFFSET', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('SKU_PREFIX_OFFSET.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('SKU_SEPARATOR') ? 'has-error' : '' }}">
      <label for="SKU_SEPARATOR" class="col-lg-4 control-label">{!! l('SKU_SEPARATOR.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="SKU_SEPARATOR" name="SKU_SEPARATOR" placeholder="" value="{{ old('SKU_SEPARATOR', $key_group['SKU_SEPARATOR']) }}" />
        {{ $errors->first('SKU_SEPARATOR', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('SKU_SEPARATOR.help') !!}</span>
      </div>
    </div>

    <div class="form-group {{ $errors->has('SKU_SUFFIX_LENGTH') ? 'has-error' : '' }}">
      <label for="SKU_SUFFIX_LENGTH" class="col-lg-4 control-label">{!! l('SKU_SUFFIX_LENGTH.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="SKU_SUFFIX_LENGTH" name="SKU_SUFFIX_LENGTH" placeholder="" value="{{ old('SKU_SUFFIX_LENGTH', $key_group['SKU_SUFFIX_LENGTH']) }}" />
        {{ $errors->first('SKU_SUFFIX_LENGTH', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('SKU_SUFFIX_LENGTH.help') !!}</span>
      </div>
    </div>



    <div class="form-group">
      <label class="col-lg-4 control-label"> </label>
      <div class="col-lg-8">
        <div class="row">
        <div>

{!! l('Auto-SKU.help') !!}

        </div>
        </div>
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