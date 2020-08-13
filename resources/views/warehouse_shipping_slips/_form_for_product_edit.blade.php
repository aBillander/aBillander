

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ l('Edit Line') }} :: <span  id="modal_product_document_line_Label"></span></h4>
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

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('line_name') ? 'has-error' : '' }}">
                     {{ l('Product Name') }}
                     {!! Form::text('line_name', null, array('class' => 'form-control', 'id' => 'line_name', 'onclick' => 'this.select()')) !!}
                     {!! $errors->first('line_name', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Measure Unit') }}
                    <div id="line_measure_unit_name" class="form-control"></div>

                    <span id="line_package_label" class="help-block text-success"></span>

                    {{ Form::hidden( 'line_measure_unit_id', null, ['id' => 'line_measure_unit_id'] ) }}

                    {{ Form::hidden( 'line_package_measure_unit_id', null, ['id' => 'line_package_measure_unit_id'] ) }}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('line_quantity', null, array('class' => 'form-control', 'id' => 'line_quantity', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_quantity', '<span class="help-block">:message</span>') !!}

                     {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}

                  </div>
        </div>


        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('line_notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('line_notes', null, array('class' => 'form-control', 'id' => 'line_notes', 'rows' => '3')) !!}
                     {!! $errors->first('line_notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

         </div><!-- div class="modal-body" ENDS -->

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-info" name="modal_edit_document_line_productSubmit" id="modal_edit_document_line_productSubmit">
                <i class="fa fa-hdd-o"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>
