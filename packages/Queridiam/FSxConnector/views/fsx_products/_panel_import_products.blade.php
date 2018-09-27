               <div class="panel-body well">


{!! Form::open(array('route' => 'fsxproducts.import.products', 'class' => 'form' )) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('FSx-Connector - Importar Secciones, Familias y Artículos') }}</legend>



        <p>{!! l('Actualizar el Catálogo de aBillander desde la Base de Datos de FactuSOLWeb.') !!}</p>
        <br />




    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_LOAD_FAMILIAS_TO_ROOT.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_LOAD_FAMILIAS_TO_ROOT" id="FSX_LOAD_FAMILIAS_TO_ROOT_on" value="1" @if( old('FSX_LOAD_FAMILIAS_TO_ROOT', $key_group['FSX_LOAD_FAMILIAS_TO_ROOT']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_LOAD_FAMILIAS_TO_ROOT" id="FSX_LOAD_FAMILIAS_TO_ROOT_off" value="0" @if( !old('FSX_LOAD_FAMILIAS_TO_ROOT', $key_group['FSX_LOAD_FAMILIAS_TO_ROOT']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_LOAD_FAMILIAS_TO_ROOT.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_LOAD_ARTICULOS.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS" id="FSX_LOAD_ARTICULOS_on" value="1" @if( old('FSX_LOAD_ARTICULOS', $key_group['FSX_LOAD_ARTICULOS']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS" id="FSX_LOAD_ARTICULOS_off" value="0" @if( !old('FSX_LOAD_ARTICULOS', $key_group['FSX_LOAD_ARTICULOS']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_LOAD_ARTICULOS.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_LOAD_ARTICULOS_ACTIVE.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS_ACTIVE" id="FSX_LOAD_ARTICULOS_ACTIVE_on" value="1" @if( old('FSX_LOAD_ARTICULOS_ACTIVE', $key_group['FSX_LOAD_ARTICULOS_ACTIVE']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS_ACTIVE" id="FSX_LOAD_ARTICULOS_ACTIVE_off" value="0" @if( !old('FSX_LOAD_ARTICULOS_ACTIVE', $key_group['FSX_LOAD_ARTICULOS_ACTIVE']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_LOAD_ARTICULOS_ACTIVE.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_LOAD_ARTICULOS_PRIZE_ALL.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS_PRIZE_ALL" id="FSX_LOAD_ARTICULOS_PRIZE_ALL_on" value="1" @if( old('FSX_LOAD_ARTICULOS_PRIZE_ALL', $key_group['FSX_LOAD_ARTICULOS_PRIZE_ALL']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS_PRIZE_ALL" id="FSX_LOAD_ARTICULOS_PRIZE_ALL_off" value="0" @if( !old('FSX_LOAD_ARTICULOS_PRIZE_ALL', $key_group['FSX_LOAD_ARTICULOS_PRIZE_ALL']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_LOAD_ARTICULOS_PRIZE_ALL.help') !!}</span>
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-4 control-label">{!! l('FSX_LOAD_ARTICULOS_STOCK_ALL.name') !!}</label>
      <div class="col-lg-8">
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS_STOCK_ALL" id="FSX_LOAD_ARTICULOS_STOCK_ALL_on" value="1" @if( old('FSX_LOAD_ARTICULOS_STOCK_ALL', $key_group['FSX_LOAD_ARTICULOS_STOCK_ALL']) ) checked="checked" @endif type="radio">
            {!! l('Yes', [], 'layouts') !!}
          </label>
        </div>
        <div class="radio">
          <label>
            <input name="FSX_LOAD_ARTICULOS_STOCK_ALL" id="FSX_LOAD_ARTICULOS_STOCK_ALL_off" value="0" @if( !old('FSX_LOAD_ARTICULOS_STOCK_ALL', $key_group['FSX_LOAD_ARTICULOS_STOCK_ALL']) ) checked="checked" @endif type="radio">
            {!! l('No', [], 'layouts') !!}
          </label>
        </div>
        <span class="help-block">{!! l('FSX_LOAD_ARTICULOS_STOCK_ALL.help') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('FSX_FSOL_AUSCFG_PEER') ? 'has-error' : '' }}">
      <label for="FSX_FSOL_AUSCFG_PEER" class="col-lg-4 control-label">{!! l('FSX_FSOL_AUSCFG_PEER.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('FSX_FSOL_AUSCFG_PEER', $warehouseList, old('FSX_FSOL_AUSCFG_PEER', $key_group['FSX_FSOL_AUSCFG_PEER']), array('class' => 'form-control')) !!}
        {{ $errors->first('FSX_FSOL_AUSCFG_PEER', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('FSX_FSOL_AUSCFG_PEER.help') !!}</span>
      </div>
    </div>




    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <!-- button class="btn btn-default">Cancelar</button -->
        <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.form.submit();">
          <i class="fa fa-refresh"></i>
                     &nbsp; {{l('Import', [], 'layouts')}}
          </button>
      </div>
    </div>
  </fieldset>
{!! Form::close() !!}



               </div>