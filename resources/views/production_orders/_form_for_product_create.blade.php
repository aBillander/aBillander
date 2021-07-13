

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_document_line_Label">{{ l('Add Product') }}</h4>
         </div>

         <div class="modal-body">


            {{-- csrf_field() --}}
            {!! Form::token() !!}
            <!-- input type="hidden" name="_token" value="{ { csrf_token() } }" -->
            <!-- input type="hidden" id="" -->
            {{ Form::hidden('line_id',         null, array('id' => 'line_id'        )) }}
            {{ Form::hidden('line_sort_order', null, array('id' => 'line_sort_order')) }}

            {{ Form::hidden('line_product_id',     null, array('id' => 'line_product_id'    )) }}
            {{ Form::hidden('line_combination_id', null, array('id' => 'line_combination_id')) }}
            {{ Form::hidden('line_reference',      null, array('id' => 'line_reference'     )) }}

            {{ Form::hidden('line_type',           null, array('id' => 'line_type'          )) }}



        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-6 col-md-6 col-sm-6">
                     {{ l('Product Name') }}
                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name', 'onclick' => 'this.select()')) !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_required_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('line_required_quantity', null, array('class' => 'form-control', 'id' => 'line_required_quantity', 'xonclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_required_quantity', '<span class="help-block">:message</span>') !!}

                     {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}

                  </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Measure Unit') }}
                    <div id="line_measure_unit_name" class="form-control"></div>
                 </div>
         
               <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_warehouse_id') ? 'has-error' : '' }}">
                  {{ l('Warehouse') }}
                  {!! Form::select('line_warehouse_id', $warehouseList, null, array('class' => 'form-control', 'id' => 'line_warehouse_id')) !!}
                  {!! $errors->first('line_warehouse_id', '<span class="help-block">:message</span>') !!}
               </div>

                   
        </div>

                    {{ Form::hidden( 'line_measure_unit_id', null, ['id' => 'line_measure_unit_id'] ) }}

         <div class="row">

        </div>

         </div><!-- div class="modal-body" ENDS -->

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success modal_document_line_productSubmit" name="modal_document_line_productSubmit" id="modal_document_line_productSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Save', 'layouts')}}</button>

           </div>
