

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ l('Edit Order Line') }} :: <span  id="modal_product_order_line_Label"></span></h4>
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

            {{ Form::hidden('line_type',           null, array('id' => 'line_type'          )) }}

            {{ Form::hidden('line_cost_price',          null, array('id' => 'line_cost_price'         )) }}
            {{ Form::hidden('line_unit_price',          null, array('id' => 'line_unit_price'         )) }}
            {{ Form::hidden('line_unit_customer_price', null, array('id' => 'line_unit_customer_price')) }}

            {{-- Not in use so far --}}
            {{ Form::hidden('line_discount_amount_tax_incl', null, array('id' => 'line_discount_amount_tax_incl')) }}
            {{ Form::hidden('line_discount_amount_tax_excl', null, array('id' => 'line_discount_amount_tax_excl')) }}
               


        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('line_name') ? 'has-error' : '' }}">
                     {{ l('Product Name') }}
                     {!! Form::text('line_name', null, array('class' => 'form-control', 'id' => 'line_name', 'onclick' => 'this.select()')) !!}
                     {!! $errors->first('line_name', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Tax') }}
                    <div id="line_tax_label" class="form-control"></div>
                    {{ Form::hidden('line_tax_percent', null, array('id' => 'line_tax_percent')) }}
                    {{ Form::hidden('line_tax_id', null, array('id' => 'line_tax_id')) }}
                 </div>


                  <div class="form-group col-lg-2 col-md-2 col-sm-2" id="line_sales_equalization" style="display:none">
                          {{ l('Apply Sales Equalization?') }}

                              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                        data-content="{{ l('sales_equalization', 'apphelp') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a>
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_sales_equalization', '1', false, ['id' => 'line_is_sales_equalization_on', 'xonclick' => 'alert("peo")']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_sales_equalization', '0', true, ['id' => 'line_is_sales_equalization_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                   
        </div>

        <!-- div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('product_line_measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('product_line_measure_unit_id', array('0' => l('-- Please, select --', [], 'layouts')) , null, array('class' => 'form-control', 'id' => 'product_line_measure_unit_id', 'onFocus' => 'this.blur()')) !!}
                    {!! $errors->first('product_line_measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

        </div -->

                    {{ Form::hidden( 'line_measure_unit_id', null, ['id' => 'line_measure_unit_id'] ) }}

         <div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('line_quantity', null, array('class' => 'form-control', 'id' => 'line_quantity', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_quantity', '<span class="help-block">:message</span>') !!}

                     {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}

                  </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ l('Price with Tax') }}
                    @else
                    {{ l('Price') }}
                    @endif
                    {!! Form::text('line_price', null, array('class' => 'form-control', 'id' => 'line_price', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Discount') }} (%)
                    {!! Form::text('line_discount_percent', null, array('class' => 'form-control', 'id' => 'line_discount_percent', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('line_discount_percent', '<span class="help-block">:message</span>') !!}
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    @if( \App\Configuration::get('PRICES_ENTERED_WITH_TAX') )
                    {{ l('Final Price with Tax') }}
                    @else
                    {{ l('Final Price') }}
                    @endif
                    <div id="line_final_price" class="form-control"></div>
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Total') }}
                    <div id="line_total_tax_exc" class="form-control"></div>
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Total with Tax') }}
                    <div id="line_total_tax_inc" class="form-control"></div>
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
               <button type="submit" class="btn btn-success" name="modal_product_order_line_productSubmit" id="modal_product_order_line_productSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>
