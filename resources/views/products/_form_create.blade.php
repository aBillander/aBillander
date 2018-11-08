
               <div class="panel-body">
               

        <div class="row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Product Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('product_type') ? 'has-error' : '' }}">
                      {{ l('Product type') }}
                      {!! Form::select('product_type', $product_typeList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('product_type', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('procurement_type') ? 'has-error' : '' }}">
                      {{ l('Procurement type') }}
                      {!! Form::select('procurement_type', $product_procurementtypeList, null, array('class' => 'form-control')) !!}
                     {!! $errors->first('procurement_type', '<span class="help-block">:message</span>') !!}
                  </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-phantom_assembly">
                     {!! Form::label('phantom_assembly', l('Phantom Assembly?'), ['class' => 'control-label']) !!}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                      data-content="{{ l('A phantom assembly is a logical (rather than functional) grouping of materials.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('phantom_assembly', '1', false, ['id' => 'phantom_assembly_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('phantom_assembly', '0', true, ['id' => 'phantom_assembly_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>

        <div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
                     {{ l('Reference') }}
                     {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
                     {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('measure_unit_id', $measure_unitList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>
@if ( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
                 <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('ecotax_id') ? 'has-error' : '' }}">
                    {{ l('Eco-Tax') }}
                    {!! Form::select('ecotax_id', array('' => l('-- Please, select --', [], 'layouts')) + $ecotaxList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('ecotax_id', '<span class="help-block">:message</span>') !!}
                 </div>
@endif
        </div>

        <div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity_decimal_places') ? 'has-error' : '' }}">
                      {{ l('Quantity decimals') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('La cantidad del producto se expresa con estos decimales') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                      {!! Form::select('quantity_decimal_places', array('0' => '0', '1' => '1', '2' => '2', '3' => '3' ), null, array('class' => 'form-control')) !!}
                      {!! $errors->first('quantity_decimal_places', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('manufacturing_batch_size') ? 'has-error' : '' }}">
                     {{ l('Manufacturing Batch Size') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('La cantidad a fabricar se calcula como múltiplo del Lote de Fabricación') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('manufacturing_batch_size', null, array('class' => 'form-control', 'id' => 'manufacturing_batch_size')) !!}
                     {!! $errors->first('manufacturing_batch_size', '<span class="help-block">:message</span>') !!}
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
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price') ? 'has-error' : '' }}">
                     {{ l('Customer Price') }}
                     {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_price()', 'onchange' => 'new_price()')) !!}
                     {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
                  </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('tax_id') ? 'has-error' : '' }}">
                    {{ l('Tax') }}
                    {!! Form::select('tax_id', array('0' => l('-- Please, select --', [], 'layouts')) + $taxList, null, array('class' => 'form-control', 'id' => 'tax_id',
                                      'onchange' => 'new_tax()')) !!}
                    {!! $errors->first('tax_id', '<span class="help-block">:message</span>') !!}
                 </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('price_tax_inc') ? 'has-error' : '' }}">
                     {{ l('Customer Price (with Tax)') }}
                     {!! Form::text('price_tax_inc', null, array('class' => 'form-control', 'id' => 'price_tax_inc', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_price_tax_inc()', 'onchange' => 'new_price_tax_inc()')) !!}
                     {!! $errors->first('price_tax_inc', '<span class="help-block">:message</span>') !!}
                  </div>

                   <!-- div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
                     {!! Form::label('price_is_tax_inc', l('Save tax included?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('price_is_tax_inc', '1', false, ['id' => 'price_is_tax_inc_on', 'onclick' => 'return false;']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('price_is_tax_inc', '0', true, ['id' => 'price_is_tax_inc_off', 'onclick' => 'return false;']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div -->
        </div>

        <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('cost_price') ? 'has-error' : '' }}">
                     {{ l('Cost Price') }}
                     {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_cost_price()', 'onchange' => 'new_cost_price()')) !!}
                     {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('margin') ? 'has-error' : '' }}">
                     {{ l('Margin') }} (%)
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                      data-content="{{ l('Dado un Precio de Coste, cambiando el Margen se recalcula el Precio de Venta. Cambiando el Precio de Venta se recalcula el Margen. El valor del Margen no se guardará, ya que se calcula a partir del Precio de Venta y del Precio de Coste.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('margin', null, array('class' => 'form-control', 'id' => 'margin', 'autocomplete' => 'off', 
                                      'onclick' => 'this.select()', 'onkeyup' => 'new_margin()', 'onchange' => 'new_margin()')) !!}
                     {!! $errors->first('margin', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     <label for="margin_method" class="control-label">{{ l('Margin calculation method') }}</label>
                     <div class="form-control" id="margin_method">
                      <strong>{{ \App\Configuration::get('MARGIN_METHOD') }}</strong> : 

   @if ( \App\Configuration::get('MARGIN_METHOD') == 'CST' )  
      {{ l('Margin calculation is based on Cost Price', [], 'layouts') }}.
   @else
      {{ l('Margin calculation is based on Sales Price', [], 'layouts') }}.
   @endif
                        {{-- l (\App\Configuration::get('MARGIN_METHOD'), [], 'appmultilang') --}}
                      </div>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     <label for="prices_entered" class="control-label">{{ l('Price input method') }}</label>
                     <div class="form-control" id="prices_entered">{{ \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ?
                                                        l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                                        l('Prices are entered exclusive of tax', [], 'appmultilang') }}</div>
                  </div>
                  <!-- div class="form-group col-lg-3 col-md-3 col-sm-3 { { $errors->has('cost_average') ? 'has-error' : '' } }">
                     { { l('Average Cost Price') } }
                     {! ! Form::text('cost_average', null, array('class' => 'form-control', 'id' => 'cost_average')) ! !}
                     {! ! $errors->first('cost_average', '<span class="help-block">:message</span>') ! !}
                  </div -->
        </div>

        <div class="row">
             <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                {{ l('Category') }}
                {!! Form::select('category_id', array('0' => l('-- Please, select --', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
                {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
             </div>

              <div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-stock_control">
                     {!! Form::label('stock_control', l('Stock Control?'), ['class' => 'control-label']) !!}
                <div>
                  <div class="radio-inline">
                    <label>
                      {!! Form::radio('stock_control', '1', true, ['id' => 'stock_control_on']) !!}
                      {!! l('Yes', [], 'layouts') !!}
                    </label>
                  </div>
                  <div class="radio-inline">
                    <label>
                      {!! Form::radio('stock_control', '0', false, ['id' => 'stock_control_off']) !!}
                      {!! l('No', [], 'layouts') !!}
                    </label>
                  </div>
                </div>
              </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity_onhand') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('quantity_onhand', null, array('class' => 'form-control', 'id' => 'quantity_onhand')) !!}
                     {!! $errors->first('quantity_onhand', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
                      {{ l('Warehouse') }}
                      {!! Form::select('warehouse_id', array('0' => l('-- Please, select --', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control')) !!}
                      {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('products') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
                  <input type="hidden" id="nextAction" name="nextAction" value="" />
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#nextAction').val('completeProductData');this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Complete', [], 'layouts')}}
                  </button>
               </div>


@section('scripts')    @parent

@include('products.js._calculator_js')

    <script type="text/javascript">

        $(document).ready(function() {
           $("#cost_price").val({{ old('cost_price', 0.0) }});
           $("#measure_unit_id").val({{ old('measure_unit_id', \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS')) }});
//           $("#quantity_onhand").val( 0 );
          
          // Select default tax
          $('select[name="tax_id"]').val({{ old('tax_id', \App\Configuration::get('DEF_TAX')) }});

          // Select default warehouse
          $('select[name="warehouse_id"]').val({{ old('warehouse_id', \App\Configuration::get('DEF_WAREHOUSE')) }});

          // Select default decimals
          $('select[name="quantity_decimal_places"]').val({{ old('quantity_decimal_places', \App\Configuration::get('DEF_QUANTITY_DECIMALS')) }});

          // Select default manufacturing batch size
          $('input[name="manufacturing_batch_size"]').val({{ old('manufacturing_batch_size', 1) }});

        });

    </script> 

    <!-- script type="text/javascript">

        // Select default tax
        if ( !($('select[name="tax_id"]').val() > 0) ) {
          var def_taxID = {{ \App\Configuration::get('DEF_TAX') }};

          $('select[name="tax_id"]').val(def_taxID);
        }

        // Select default warehouse
        if ( !($('select[name="warehouse_id"]').val() > 0) ) {
          var def_warehouseID = {{ \App\Configuration::get('DEF_WAREHOUSE') }};

          $('select[name="warehouse_id"]').val(def_warehouseID);
        }


        // Select default decimals
        var def_decimalsID = {{ \App\Configuration::get('DEF_QUANTITY_DECIMALS') }};

        $('select[name="quantity_decimal_places"]').val(def_decimalsID);

        // Select default manufacturing batch size
        $('input[name="manufacturing_batch_size"]').val( 1 );

    </script -->


@endsection