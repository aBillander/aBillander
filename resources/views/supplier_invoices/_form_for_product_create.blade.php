

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

            {{ Form::hidden('line_cost_price',          null, array('id' => 'line_cost_price'         )) }}
            {{ Form::hidden('line_unit_price',          null, array('id' => 'line_unit_price'         )) }}
            {{ Form::hidden('line_unit_supplier_price', null, array('id' => 'line_unit_supplier_price')) }}

            {{-- Not in use so far --}}
            {{ Form::hidden('line_discount_amount_tax_incl', null, array('id' => 'line_discount_amount_tax_incl')) }}
            {{ Form::hidden('line_discount_amount_tax_excl', null, array('id' => 'line_discount_amount_tax_excl')) }}

            {{ Form::hidden('line_is_prices_entered_with_tax', null, array('id' => 'line_is_prices_entered_with_tax')) }}



        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-6 col-md-6 col-sm-6">
                     {{ l('Product Name') }}
                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name', 'onclick' => 'this.select()')) !!}
                  </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_package__measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('line_package_measure_unit_id', array('' => l('-- Please, select --', [], 'layouts')) , null, array('class' => 'form-control', 'id' => 'line_package_measure_unit_id')) !!}
                    {!! $errors->first('line_package_measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
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
                 <!-- div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Cost Price') }}
                    {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                 </div -->

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('line_quantity', null, array('class' => 'form-control', 'id' => 'line_quantity', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_quantity', '<span class="help-block">:message</span>') !!}

                     {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}

                  </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    
                    <span class="label_tax_inc">{{ l('Price with Tax') }}</span>
                    
                    <span class="label_tax_exc">{{ l('Price') }}</span>

@if( AbiConfiguration::isTrue('ENABLE_ECOTAXES') )
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" 
                                    data-content="{{ ld('Prices are inclusive of Ecotax.')}}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
@endif
                    
                    {!! Form::text('line_price', null, array('class' => 'form-control', 'id' => 'line_price', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('line_price', '<span class="help-block">:message</span>') !!}


@if( AbiConfiguration::isTrue('ENABLE_ECOTAXES') )
                    <span class="help-block">{{ ld('Ecotax: ')}}<span id="label_ecotax_value" class="text-success"></span></span>
@endif
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Discount') }} (%)
                    {!! Form::text('line_discount_percent', null, array('class' => 'form-control', 'id' => 'line_discount_percent', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('line_discount_percent', '<span class="help-block">:message</span>') !!}
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    
                    <span class="label_tax_inc">{{ l('Final Price with Tax') }}</span>
                    
                    <span class="label_tax_exc">{{ l('Final Price') }}</span>
                    
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

           <div class="modal-footer" style="display: none;" id="convinient_buttons">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success modal_document_line_productSubmit" name="modal_document_line_productSubmit" id="modal_document_line_productSubmit" title="{{l('Save line and apply Supplier Price List and/or Price Rules.', 'supplierdocuments')}}">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', 'layouts')}}</button>

               <button type="submit" class="btn btn-info modal_document_line_productSubmit" name="modal_document_line_productSubmitAsIs" id="modal_document_line_productSubmitAsIs" title="{{l('Save line "as is", and WILL NOT apply Supplier Price List and/or Price Rules.', 'supplierdocuments')}}">
                <i class="fa fa-hdd-o"></i>
                &nbsp; {{l('Save', 'layouts')}}</button>

           </div>

<div id="product_price_data">
  
{{-- Product price data here (Ajax loaded) --}}

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
{{--
               <button type="submit" class="btn btn-success modal_document_line_productSubmit" name="modal_document_line_productSubmit" id="modal_document_line_productSubmit" title="{{l('Save line and apply Supplier Price List and/or Price Rules.', 'supplierdocuments')}}">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', 'layouts')}}</button>
--}}
               <button type="submit" class="btn btn-info modal_document_line_productSubmit" name="modal_document_line_productSubmitAsIs" id="modal_document_line_productSubmitAsIs" title="{{l('Save line "as is", and WILL NOT apply Supplier Price List and/or Price Rules.', 'supplierdocuments')}}">
                <i class="fa fa-hdd-o"></i>
                &nbsp; {{l('Save', 'layouts')}}</button>

           </div>
