
<div id="panel_create_bom"> 

{!! Form::open(array('route' => 'productboms.store', 'id' => 'create_bom', 'name' => 'create_bom', 'class' => 'form')) !!}
<!-- input type="hidden" value="sales" name="tab_name" id="tab_name" -->

<div class="panel panel-primary">
   <div class="panel-heading">
      <h3 class="panel-title"><!-- {{ l('Bill of Materials') }} --></h3>
   </div>
   <div class="panel-body">

<!-- BOM Description -->

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('alias') ? 'has-error' : '' }}">
                      {{ l('Alias') }}
                      {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
                      {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                      {{ l('Name') }}
                      {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                      {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('La cantidad de los Ingredientes son para esta cantidad de Elaborado, expresada en la unidad de medida de la Lista de Materiales.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('quantity', null, array('class' => 'form-control', 'id' => 'quantity')) !!}
                     {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('measure_unit_id', $measure_unitList, old('measure_unit_id', AbiConfiguration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS')), array('class' => 'form-control')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('cost_price') ? 'has-error' : '' }}">
                     {{ l('Measure Unit') }}
                     {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off', 'onfocus' => 'this.blur()')) !!}
                     {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                  </div -->
        </div>

<!-- BOM Description ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}
