
{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="" name="tab_name" id="tab_name_main_data">

<div class="panel panel-primary" id="panel_main_data">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>
   <div class="panel-body">

<!-- Main Data -->
@php
    $foo = $product->product_type == 'combinable' ? [ 'onfocus' => 'this.blur()' ] : [] ;
@endphp

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
                     {{ l('Reference') }}
                     {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference') + $foo) !!}
                     {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Product Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                     {{ l('Product type') }}
                     {!! Form::text('product_type', l($product->product_type, [], 'appmultilang'), array('class' => 'form-control', 'onfocus' => 'this.blur()')) !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('ean13') ? 'has-error' : '' }}">
                     {{ l('Ean13') }}
                     {!! Form::text('ean13', null, array('class' => 'form-control', 'id' => 'ean13') + $foo) !!}
                     {!! $errors->first('ean13', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('description') ? 'has-error' : '' }}">
                     {{ l('Description') }}
                     {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '3')) !!}
                     {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                  </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                    {{ l('Category') }}
                    {!! Form::select('category_id', array('0' => l('-- Please, select --', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                 </div>
                 <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('tax_id') ? 'has-error' : '' }}">
                    {{ l('Tax') }}
                    {!! Form::select('tax_id', array('0' => '-- Seleccione --') + $taxList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('tax_id', '<span class="help-block">:message</span>') !!}
                 </div -->
        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('location') ? 'has-error' : '' }}">
                     {{ l('Location') }}
                     {!! Form::text('location', null, array('class' => 'form-control', 'id' => 'location')) !!}
                     {!! $errors->first('location', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('width') ? 'has-error' : '' }}">
                     {{ l('Width') }}
                     {!! Form::text('width', null, array('class' => 'form-control', 'id' => 'width')) !!}
                     {!! $errors->first('width', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('height') ? 'has-error' : '' }}">
                     {{ l('Height') }}
                     {!! Form::text('height', null, array('class' => 'form-control', 'id' => 'height')) !!}
                     {!! $errors->first('height', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('depth') ? 'has-error' : '' }}">
                     {{ l('Depth') }}
                     {!! Form::text('depth', null, array('class' => 'form-control', 'id' => 'depth')) !!}
                     {!! $errors->first('depth', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('weight') ? 'has-error' : '' }}">
                     {{ l('Weight') }}
                     {!! Form::text('weight', null, array('class' => 'form-control', 'id' => 'weight')) !!}
                     {!! $errors->first('weight', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('warranty_period') ? 'has-error' : '' }}">
                     {{ l('Warranty period') }}
                     {!! Form::text('warranty_period', null, array('class' => 'form-control', 'id' => 'warranty_period')) !!}
                     {!! $errors->first('warranty_period', '<span class="help-block">:message</span>') !!}
                  </div>


                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
                     {!! Form::label('blocked', l('Blocked?', [], 'layouts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('blocked', '1', false, ['id' => 'blocked_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('blocked', '0', true, ['id' => 'blocked_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
                     {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('measure_unit') ? 'has-error' : '' }}">
                     {{ l('Measure Unit') }}
                     {!! Form::text('measure_unit', null, array('class' => 'form-control', 'id' => 'measure_unit')) !!}
                     {!! $errors->first('measure_unit', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity_decimal_places') ? 'has-error' : '' }}">
                      {{ l('Quantity decimals') }}
                      {!! Form::select('quantity_decimal_places', array('0' => '0', '1' => '1', '2' => '2', '3' => '3' ), null, array('class' => 'form-control')) !!}
                      {!! $errors->first('quantity_decimal_places', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

<!--  Main Data ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;$('#tab_name_main_data').val('main_data');this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}
