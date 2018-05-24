
@section('modals')    @parent

<div class="modal" id="modal_order_line" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg" xstyle="width: 99%; max-width: 1000px;">
      <div class="modal-content">

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_order_line_Label"> </h4>
         </div>

         <div class="modal-body">

            <ul class="nav nav-tabs" id="nav_new_order_line">
               <li id="li_new_product"  ><a href="javascript:void(0);" id="tab_new_product" >{{ l('Coded Product') }}</a></li>
               <li id="li_new_service"  ><a href="javascript:void(0);" id="tab_new_service" >{{ l('Service (not coded)') }}</a></li>
               <!-- li id="li_new_discount" ><a href="javascript:void(0);" id="tab_new_discount">{{ l('Discount') }}</a></li>
               <li id="li_new_text_line" ><a href="javascript:void(0);" id="tab_new_text_line" >{{ l('Text Line') }}</a></li -->
            </ul>

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
                {{ Form::hidden('discount_amount_tax_incl', null, array('id' => 'discount_amount_tax_incl')) }}
                {{ Form::hidden('discount_amount_tax_excl', null, array('id' => 'discount_amount_tax_excl')) }}

         </div>

         <div id="new_product">
         <div class="modal-body">
               


        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-8 col-md-8 col-sm-8">
                     {{ l('Product name') }}
                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name', 'onclick' => 'this.select()')) !!}
                  </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    {{ l('Tax') }}
                    <div id="line_tax_label" class="form-control"></div>
                    {{ Form::hidden('line_tax_percent', null, array('id' => 'line_tax_percent')) }}
                    {{ Form::hidden('line_tax_id', null, array('id' => 'line_tax_id')) }}
                 </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 ">
                     {{ l('Product ID') }}
                     {!! Form::text('pid', null, array('class' => 'form-control', 'id' => 'pid')) !!}
                  </div -->

                  @if( $customer->sales_equalization )
                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                          {{ l('¿Aplica RE?') }}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_sales_equalization', '1', true, ['id' => 'line_is_sales_equalization_on', 'onclick' => 'alert("peo")']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('line_is_sales_equalization', '0', false, ['id' => 'line_is_sales_equalization_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
                   @else
                           {!! Form::radio('line_is_sales_equalization', '0', true, ['id' => 'line_is_sales_equalization_off', 'style' => 'display:none']) !!}
                   @endif
                   
        </div>

        <!-- div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('BOMline[measure_unit_id]', array('0' => l('-- Please, select --', [], 'layouts')) , null, array('class' => 'form-control', 'id' => 'line_measure_unit_id', 'onFocus' => 'this.blur()')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('scrap') ? 'has-error' : '' }}">
                     {{ l('Scrap (%)') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="bottom" 
                                      data-container="body" 
                                      data-content="{{ l('Percent. When the components are ready to be consumed in a released production order, this percentage will be added to the expected quantity in the Consumption Quantity field in a production journal.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('BOMline[scrap]', null, array('class' => 'form-control', 'id' => 'line_scrap')) !!}
                     {!! $errors->first('scrap', '<span class="help-block">:message</span>') !!}
                  </div>
        </div -->

         <div class="row">
                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_sort_order') ? 'has-error' : '' }}" xstyle="display: none;">
                     {{ l('Position') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     { ! ! Form::text('line_sort_order', null, array('class' => 'form-control', 'id' => 'line_sort_order')) ! ! }
                     {!! $errors->first('line_sort_order', '<span class="help-block">:message</span>') !!}
                  </div -->
                 <!-- div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Cost Price') }}
                    {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                 </div -->

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('line_quantity', null, array('class' => 'form-control', 'id' => 'line_quantity', 'onkeyup' => 'calculate_line_product( )', 'onchange' => 'calculate_line_product( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_quantity', '<span class="help-block">:message</span>') !!}
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


<!--
@if ($customer->sales_equalization)
                     <div class="col-lg-4 col-md-4 col-sm-4">
                        {{ l('Apply Sales Equalization?') }} 

              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('El Recargo de Equivalencia sólo aplica en la compra de los bienes objeto del comercio minorista; no aplica a elementos de inmovilizado o en servicios contratados, etc.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
                        <div>
                          <div class="radio-inline">
                            <label>
                              {!! Form::radio('sales_equalization', '1', true, ['id' => 'sales_equalization_on']) !!}
                              {!! l('Yes', [], 'layouts') !!}
                            </label>
                          </div>
                          <div class="radio-inline">
                            <label>
                              {!! Form::radio('sales_equalization', '0', false, ['id' => 'sales_equalization_off']) !!}
                              {!! l('No', [], 'layouts') !!}
                            </label>
                          </div>
                        </div>
                     </div>
@else
            <input type="hidden" name="sales_equalization" value="1"/>
@endif
-->

         <!-- div id="search_results" style="padding-top: 20px;"></div -->

         </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_order_line_productSubmit" id="modal_order_line_productSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>

         </div>   <!-- div id="new_product" class="modal-body"  ENDS  -->


         <div id="new_service" style="display: none;">
         <div class="modal-body">
            
               <div class="row">
                 <div class="form-group col-lg-8 col-md-8 col-sm-8">
                    {{ l('Description') }}
                    {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                          {{ l('Is Shipping Cost?') }}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_shipping', '1', false, ['id' => 'is_shipping_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_shipping', '0', true, ['id' => 'is_shipping_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                  @if( $customer->sales_equalization )
                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                          {{ l('¿Aplica RE?') }}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_sales_equalization', '1', false, ['id' => 'is_sales_equalization_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('is_sales_equalization', '0', true, ['id' => 'is_sales_equalization_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
                   @else
                           {!! Form::radio('is_sales_equalization', '0', true, ['id' => 'is_sales_equalization_off', 'style' => 'display:none']) !!}
                   @endif
                   
               </div>
               <div class="row">
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Cost Price') }}
                    {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Price') }}
                    {!! Form::text('price', null, array('class' => 'form-control', 'id' => 'price', 'onkeyup' => 'calculate_service_price( "with_tax" )', 'onchange' => 'calculate_service_price( "with_tax" )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('Tax') }}
                    {!! Form::select('tax_id', $order->taxingaddress->getTaxList(), \App\Configuration::get('DEF_TAX'), array('class' => 'form-control', 'id' => 'tax_id', 'onchange' => 'calculate_service_price( )')) !!}
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('With Tax') }}
                    {!! Form::text('price_tax_inc', null, array('class' => 'form-control', 'id' => 'price_tax_inc', 'onkeyup' => 'calculate_service_price( )', 'onchange' => 'calculate_service_price( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('price_tax_inc', '<span class="help-block">:message</span>') !!}
                 </div>
               </div>

              <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('service_notes') ? 'has-error' : '' }}">
                           {{ l('Notes', [], 'layouts') }}
                           {!! Form::textarea('service_notes', null, array('class' => 'form-control', 'id' => 'service_notes', 'rows' => '3')) !!}
                           {!! $errors->first('service_notes', '<span class="help-block">:message</span>') !!}
                        </div>
              </div>

         </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_order_line_serviceSubmit" id="modal_order_line_serviceSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

               <!-- div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_service_to_document();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}</a>
               </div -->

           </div>
            
         </div>   <!-- div id="new_service" style="display: none;"  ENDS  -->


         <div id="new_discount" class="modal-body" style="display: none;">
            
               <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                     {{ l('Description') }}
                     {!! Form::text('discount_name', null, array('class' => 'form-control', 'id' => 'discount_name', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('discount_name', '<span class="help-block">:message</span>') !!}
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     {{ l('Price') }}
                     {!! Form::text('discount_price', null, array('class' => 'form-control', 'id' => 'discount_price', 'onkeyup' => 'calculate_discount_price( "with_tax" )', 'onchange' => 'calculate_discount_price( "with_tax" )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     {{ l('Tax') }}
                     {!! Form::select('discount_tax_id', $taxList, \App\Configuration::get('DEF_TAX'), array('class' => 'form-control', 'id' => 'discount_tax_id', 'onchange' => 'calculate_discount_price( )')) !!}
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     {{ l('With Tax') }}
                     {!! Form::text('discount_price_tax_inc', null, array('class' => 'form-control', 'id' => 'discount_price_tax_inc', 'onkeyup' => 'calculate_discount_price( )', 'onchange' => 'calculate_discount_price( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('price_tax_inc', '<span class="help-block">:message</span>') !!}
                  </div>
               </div>

               <div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_discount_to_document();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}</a>
               </div>
            
         </div>


         <div id="new_text_line" class="modal-body" style="display: none;">
            <form id="f_new_text_line" name="f_new_text_line" action="" method="post" class="form">
               <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                     {{ l('Description') }}
                     {!! Form::text('text_line_name', null, array('class' => 'form-control', 'id' => 'text_line_name', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('text_line_name', '<span class="help-block">:message</span>') !!}
                  </div>
               </div>

               <div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_text_line_to_order();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}</a>
               </div>
            </form>
         </div>


      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

{{-- Tabbed menu --}}

<script type="text/javascript">

$(document).ready(function() {
   
   $("#tab_new_product").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all();
      $("#li_new_product").addClass('active');

      // Reset values
      $('#line_type').val('product');
      $("#line_autoproduct_name").val('');
      $('#line_product_id').val();
      $('#line_combination_id').val();
      $('#line_quantity').val(1.0);
      $('#line_cost_price').val();
      $('#line_unit_price').val();
      $('#line_unit_customer_price').val();
      $('#line_price').val();
      $('#line_tax_id').val(0);
      $('#line_tax_percent').val(0.0);
      $('#line_tax_label').html('');
      $('#line_discount_percent').val(0.0);
      $('#line_discount_amount_tax_incl').val(0.0);
      $('#line_discount_amount_tax_excl').val(0.0);
      $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
      $('#line_total_tax_exc').html('');
      $('#line_total_tax_inc').html('');
      $('#line_notes').val('');

      $("#new_product").show();
      $("#line_autoproduct_name").focus();
   });
   
   $("#tab_new_service").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_service").addClass('active');

      // Reset values
      $('#line_type').val('service');
      $('#name').val('');
//      $('#is_shipping').val(0);
      $('input[name=is_shipping][value=0]').prop('checked', 'checked');
      $('#cost_price').val(0.0);
      $('#price').val(0.0);
      $('#tax_id').val({{ \App\Configuration::get('DEF_TAX') }});
      $('#line_tax_percent').val(0.0);
      $('#price_tax_inc').val(0.0);
      $('#service_notes').val('');

      $("#new_service").show();
      $("#name").focus();
   });
   
   $("#tab_new_discount").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_discount").addClass('active');
      $('#line_type').val('discount');
      $("#new_discount").show();
      document.f_new_discount.discount_name.select();
   });

      // To get focus properly:
      $("#tab_new_product").trigger("click");

});

function modal_search_tab_hide_all() {

  $("#nav_new_order_line li").each(function() {
     $(this).removeClass("active");
  });

     $("#new_product").hide();
     $("#new_service").hide();
     $("#new_discount").hide();
}

function calculate_line_product() {

   var tax_percent;

    // parseFloat

    var total;
    var total_tax_exc;
    var total_tax_inc;
//    var tax_percent = $('#line_tax_percent').val();

    if ($("#line_tax_id").val()>0) { 
      tax_percent = parseFloat( 
        get_tax_percent_by_id( $("#line_tax_id").val(), $("input[name='line_is_sales_equalization']:checked").val() ) 
      ); 
    }
    else { return false; }

    total = $("#line_quantity").val() * $("#line_price").val() * ( 1.0 - $("#line_discount_percent").val() / 100.0 );

    if ( PRICES_ENTERED_WITH_TAX ) {
        total_tax_inc = total;
        total_tax_exc = total / ( 1.0 + tax_percent / 100.0 );
    } else {
        total_tax_inc = total * ( 1.0 + tax_percent / 100.0 );
        total_tax_exc = total;
    }

    $("#line_total_tax_exc").html( total_tax_exc.round( PRICE_DECIMAL_PLACES ) );
    $("#line_total_tax_inc").html( total_tax_inc.round( PRICE_DECIMAL_PLACES ) );
}


function calculate_service_price( with_tax )
{
   var tax_percent;

  if ($("#tax_id").val()>0) { 
    tax_percent = parseFloat( 
      get_tax_percent_by_id( $("#tax_id").val(), $("input[name='is_sales_equalization']:checked").val() ) 
    ); 
  }
  else { return ; }

   $('#line_tax_percent').val( tax_percent );

   if (with_tax=='with_tax')
   {
      p = $("#price").val();
      p_t = p*(1.0 + tax_percent/100.0)
      $("#price_tax_inc").val(p_t);
   } else {
      p_t = $("#price_tax_inc").val();
      p = p_t/(1.0 + tax_percent/100.0)
      $("#price").val(p);
   }
}



function get_tax_percent_by_id(tax_id, se = 0) 
{
   // http://stackoverflow.com/questions/18910939/how-to-get-json-key-and-value-in-javascript
   // var taxes = $.parseJSON( '{{ json_encode( $taxpercentList ) }}' );

/*   var taxes = { ! ! json_encode( $customer->sales_equalization
                                  ? $taxeqpercentList 
                                  : $taxpercentList 
                              ) ! ! } ;
*/
   var se;
   var taxes   = {!! json_encode( $order->taxingaddress->getTaxPercentList() ) !!} ;
   var retaxes = {!! json_encode( $order->taxingaddress->getTaxWithREPercentList() ) !!} ;

   if (typeof taxes[tax_id] == "undefined")   // or if (taxes[tax_id] === undefined) {
   {
        // variable is undefined
        alert('Tax code ['+tax_id+'] not found!');

        return false;
   }

   if (se>0)
        return parseFloat(retaxes[tax_id]);
   else
        return parseFloat(taxes[tax_id]);
}

</script>

@endsection
