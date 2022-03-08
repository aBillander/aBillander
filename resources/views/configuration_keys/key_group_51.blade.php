<br><br>

               <div class="panel-body well">


@if (AbiConfiguration::isTrue('ENABLE_CUSTOMER_CENTER') )


{!! Form::open(array('url' => 'configurationkeys', 'id' => 'key_group_'.intval($tab_index).'1', 'name' => 'key_group_'.intval($tab_index).'1', 'class' => 'form-horizontal')) !!}


  <input id="tab_index" name="tab_index" type="hidden" value="51">

  <fieldset>
    <legend>{{ l('Customer Center') }} :: Gastos de Envío</legend>
    


    <div class="form-group {{ $errors->has('ABCC_SHIPPING_LABEL') ? 'has-error' : '' }}">
      <label for="ABCC_SHIPPING_LABEL" class="col-lg-4 control-label">{!! l('Etiqueta para los Costes de Envío') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_SHIPPING_LABEL" name="ABCC_SHIPPING_LABEL" placeholder="" value="{{ old('ABCC_SHIPPING_LABEL', $key_group['ABCC_SHIPPING_LABEL']) }}" />
        {{ $errors->first('ABCC_SHIPPING_LABEL', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('Esta etiqueta será el nombre de la línea del Pedido correspondiente al Coste de Envío.') !!}</span>
      </div>
    </div>
    


    <div class="form-group {{ $errors->has('ABCC_FREE_SHIPPING_PRICE') ? 'has-error' : '' }}">
      <label for="ABCC_FREE_SHIPPING_PRICE" class="col-lg-4 control-label">{!! l('Envío gratis a partir de (Euros)') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_FREE_SHIPPING_PRICE" name="ABCC_FREE_SHIPPING_PRICE" placeholder="" value="{{ old('ABCC_FREE_SHIPPING_PRICE', $key_group['ABCC_FREE_SHIPPING_PRICE']) }}" />
        {{ $errors->first('ABCC_FREE_SHIPPING_PRICE', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('Valor de los Productos sin Impuestos. Este valor tiene precedencia sobre el valor calculado a partir del Método de Envío. Introduzca un valor negativo para deshabilitar esta característica.') !!}</span>
      </div>
    </div>


{{--
    <div class="form-group {{ $errors->has('ABCC_STATE_42_SHIPPING') ? 'has-error' : '' }}">
      <label for="ABCC_STATE_42_SHIPPING" class="col-lg-4 control-label">{!! l('Coste del Envío a Sevilla') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_STATE_42_SHIPPING" name="ABCC_STATE_42_SHIPPING" placeholder="" value="{{ old('ABCC_STATE_42_SHIPPING', $key_group['ABCC_STATE_42_SHIPPING']) }}" />
        {{ $errors->first('ABCC_STATE_42_SHIPPING', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('En Euros') !!}</span>
      </div>
    </div>



    <div class="form-group {{ $errors->has('ABCC_COUNTRY_1_SHIPPING') ? 'has-error' : '' }}">
      <label for="ABCC_COUNTRY_1_SHIPPING" class="col-lg-4 control-label">{!! l('Coste del Envío al Resto Península') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="ABCC_COUNTRY_1_SHIPPING" name="ABCC_COUNTRY_1_SHIPPING" placeholder="" value="{{ old('ABCC_COUNTRY_1_SHIPPING', $key_group['ABCC_COUNTRY_1_SHIPPING']) }}" />
        {{ $errors->first('ABCC_COUNTRY_1_SHIPPING', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('En Euros') !!}</span>
      </div>
    </div>

@php

$taxList = \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

@endphp    

    <div class="form-group {{ $errors->has('ABCC_SHIPPING_TAX') ? 'has-error' : '' }}">
      <label for="ABCC_SHIPPING_TAX" class="col-lg-4 control-label">{!! l('Impuesto') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-8">
        {!! Form::select('ABCC_SHIPPING_TAX', $taxList, old('ABCC_SHIPPING_TAX', $key_group['ABCC_SHIPPING_TAX']), array('class' => 'form-control')) !!}
        {{ $errors->first('ABCC_SHIPPING_TAX', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-4"> </div>
        </div>
        <span class="help-block">{!! l('Este Impuesto se aplicará al Coste del Envío') !!}</span>
      </div>
    </div>
--}}


    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <!-- button class="btn btn-default">Cancelar</button -->
        <button type="submit" class="btn btn-primary" onclick="$('#tab_index').val('51');this.form.submit();">
          <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
          </button>
      </div>
    </div>
  </fieldset>
{!! Form::close() !!}


@endif


               </div>