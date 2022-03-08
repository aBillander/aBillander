

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_document_line_Label">{{ l('Add Text Line') }}</h4>
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

            {{-- Form::hidden('line_cost_price',          null, array('id' => 'line_cost_price'         )) --}}
            {{ Form::hidden('line_unit_price',          null, array('id' => 'line_unit_price'         )) }}
            {{ Form::hidden('line_unit_customer_price', null, array('id' => 'line_unit_customer_price')) }}

            {{-- Not in use so far --}}
            {{ Form::hidden('line_discount_amount_tax_incl', null, array('id' => 'line_discount_amount_tax_incl')) }}
            {{ Form::hidden('line_discount_amount_tax_excl', null, array('id' => 'line_discount_amount_tax_excl')) }}

            {{ Form::hidden('line_sales_rep_id',       null, array('id' => 'line_sales_rep_id'      )) }}
            {{ Form::hidden('line_commission_percent', null, array('id' => 'line_commission_percent')) }}

            {{ Form::hidden('line_is_prices_entered_with_tax', null, array('id' => 'line_is_prices_entered_with_tax')) }}



        <div class="row" id="comment-search-autocomplete">
                 <div class="form-group col-lg-8 col-md-8 col-sm-8">
                    {{ l('Description') }}
                    {!! Form::text('line_autocomment_name', null, array('class' => 'form-control', 'id' => 'line_autocomment_name', 'autocomplete' => 'off', 'onclick' => 'this.select()')) !!}
                    {!! $errors->first('line_autocomment_name', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 hidden">
                          {{ l('Is Shipping Cost?') }}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_shipping', '1', false, ['id' => 'line_is_shipping_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_shipping', '0', true, ['id' => 'line_is_shipping_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
                 

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 hidden">
                          {{ l('Apply Sales Equalization?') }}

                              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                                        data-content="{{ l('sales_equalization', 'apphelp') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a>
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_sales_equalization', '1', false, ['id' => 'line_is_sales_equalization_on', 'onclick' => 'calculate_line_product();']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_sales_equalization', '0', true, ['id' => 'line_is_sales_equalization_off', 'onclick' => 'calculate_line_product();']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                   
               </div>
               <div class="row hidden">
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Cost Price') }}
                    {!! Form::text('line_cost_price', null, array('class' => 'form-control', 'id' => 'line_cost_price', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('line_cost_price', '<span class="help-block">:message</span>') !!}
                 </div>

                 {{ Form::hidden('line_quantity', null, array('id' => 'line_quantity')) }}

                 {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}

                 {{ Form::hidden( 'line_measure_unit_id', null, ['id' => 'line_measure_unit_id'] ) }}

                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    
                    <span class="label_tax_inc">{{ l('Price with Tax') }}</span>
                    
                    <span class="label_tax_exc">{{ l('Price') }}</span>
                    
                    {!! Form::text('line_price', null, array('class' => 'form-control', 'id' => 'line_price', 'onkeyup' => 'calculate_service_price( )', 'onchange' => 'calculate_service_price( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('line_price', '<span class="help-block">:message</span>') !!}

                 </div>

                 {{ Form::hidden('line_discount_percent', null, array('id' => 'line_discount_percent')) }}

                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Tax') }}
                    {!! Form::select('line_tax_id', [], null, array('class' => 'form-control', 'id' => 'line_tax_id', 'onchange' => 'calculate_service_price( )')) !!}

                    <div id="line_tax_label" class="form-control" style="display: none;"></div>
                    {{ Form::hidden('line_tax_percent', null, array('id' => 'line_tax_percent')) }}
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
               <button type="submit" class="btn btn-success" name="modal_document_line_commentSubmit" id="modal_document_line_commentSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>
