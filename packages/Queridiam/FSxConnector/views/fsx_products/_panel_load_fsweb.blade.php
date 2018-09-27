<div class="row"> 
  <div class="col-lg-8 col-md-8 col-sm-8">


               <div class="panel-body well">


{!! Form::open(array('route' => 'fsxproducts.store', 'class' => 'form' )) !!}


  {!! Form::hidden('tab_index', $tab_index, array('id' => 'tab_index')) !!}

  <fieldset>
    <legend>{{ l('FSx-Connector - Cargar FactuSOLWeb') }}</legend>



        <p>{!! l('Cargar la Base de Datos de FactuSOLWeb desde el fichero <i>factusolweb.sql</i>.') !!}</p>
        <br />


{{--
    <div class="form-group {{ $errors->has('FSOL_SPCCFG') ? 'has-error' : '' }}">
      <label for="FSOL_SPCCFG" class="col-lg-4 control-label">{!! l('FSOL_SPCCFG.name') !!}</label>
      <div class="col-lg-8">
        <div class="row">
        <div class="col-lg-6">
        <input class="form-control" type="text" id="FSOL_SPCCFG" name="FSOL_SPCCFG" placeholder="" value="{{ old('FSOL_SPCCFG', $key_group['FSOL_SPCCFG']) }}" />
        {{ $errors->first('FSOL_SPCCFG', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-lg-6"> </div>
        </div>
        <span class="help-block">{!! l('FSOL_SPCCFG.help') !!}</span>
      </div>
    </div>


<div id="fsxconfs" style="display:none;">
@foreach ( $fsxconfs as $fsxconf )
<div class="row">

  <div class="form-group col-lg-6 col-md-6 col-sm-6">

      <div class="text-right">
        <label>{{ $fsxconf['id'] }}</label>
      </div>

  { { -- abi_r($fsxconf) -- } }

      {{-- !! Form::label($tax['slug'], $tax['name']) !! -- }}
      <!-- div class="text-right"><label>{ { $tax['name'].' ['.$tax['slug'].']' } }</label></div -->
      {{-- !! Form::text($tax['slug'], null, array('class' => 'form-control')) !! -- }}
  </div>
  <div class="form-group col-lg-6 col-md-6 col-sm-6 { { $errors->has('dic.'.$dic[$tax['slug']]) ? 'has-error' : '' } }">

    {{ $fsxconf['value'] }}

        {{-- !! Form::select('dic['.$dic[$tax['slug']].']', array('0' => l('-- Please, select --', [], 'layouts')) + $taxList, $dic_val[$tax['slug']], array('class' => 'form-control')) !! -- }}
      {{-- !! $errors->first('dic.'.$dic[$tax['slug']], '<span class="help-block">:message</span>') !! -- }}
    </div>

</div>

@endforeach
</div>
--}}

    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <!-- button class="btn btn-default">Cancelar</button -->
        <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.form.submit();">
          <i class="fa fa-database"></i>
                     &nbsp; {{l('Load', [], 'layouts')}}
          </button>
      </div>
    </div>
  </fieldset>
{!! Form::close() !!}



               </div><!-- div class="panel-body well" -->

  </div>

  <div class="col-lg-4 col-md-4 col-sm-4">

<ul class="list-group">
  <li class="list-group-item" style="background-color: #dff0d8;
border-color: #d6e9c6;
color: #468847;">
    <h4 class="list-group-item-heading">FactuSOL</h4>
  </li>
  <li class="list-group-item">
    <h4 class="list-group-item-heading">Tarifa</h4>
    <p class="list-group-item-text">[{{ \Queridiam\FSxConnector\Tarifa::tarifa_codigo() }}] {{  \Queridiam\FSxConnector\Tarifa::tarifa_nombre() }}</p>
  </li>
  <li class="list-group-item">
    <h4 class="list-group-item-heading">Almacén</h4>
    <p class="list-group-item-text">[{{ \Queridiam\FSxConnector\Stock::almacen_codigo() }}] {{  \Queridiam\FSxConnector\Stock::almacen_nombre() }}</p>
  </li>
</ul>

  </div>


</div><!-- div class="row" --> 