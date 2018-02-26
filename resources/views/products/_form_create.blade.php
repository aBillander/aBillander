
               <div class="panel-body">

                {!! Form::hidden('product_type',     'simple',      array('id' => 'product_type')) !!}
                {!! Form::hidden('procurement_type', 'manufacture', array('id' => 'procurement_type')) !!}

        <div class="row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Product Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('product_type') ? 'has-error' : '' }}"">

                      {{-- l('Product type') } }
                      { !! Form::select('product_type', $product_typeList, null, array('class' => 'form-control')) !! }
                     { !! $errors->first('product_type', '<span class="help-block">:message</span>') !! --}}
                  </div -->

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
                     {{ l('Reference') }}
                     {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
                     {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('measure_unit_id', array('0' => l('-- Please, select --', [], 'layouts')) + $measure_unitList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>
        </div>

        <div class="row">
		         <!-- div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('category_id') ? 'has-error' : '' }}">
		            {{-- l('Category') } }
		            { !! Form::select('category_id', array('0' => l('-- Please, select --', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !! }
                { !! $errors->first('category_id', '<span class="help-block">:message</span>') !! --}}
		         </div -->

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

    <script type="text/javascript">

        $(document).ready(function() {
           $("#cost_price").val( 0.0 );
//           $("#quantity_onhand").val( 0 );
        });

    </script> 

    <script type="text/javascript">

        // Select default decimals
        var def_decimalsID = {{ \App\Configuration::get('DEF_QUANTITY_DECIMALS') }};

        $('select[name="quantity_decimal_places"]').val(def_decimalsID);

        // Select default manufacturing batch size
        $('input[name="manufacturing_batch_size"]').val( 1 );

    </script>


@endsection