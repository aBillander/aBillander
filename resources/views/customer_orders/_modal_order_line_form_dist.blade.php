
@section('modals')    @parent

<div class="modal" id="modal_order_line" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg" xstyle="width: 99%; max-width: 1000px;">
      <div class="modal-content">

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_order_line_Label">{{ l('Add Line to Order') }}</h4>
         </div>

         <div class="modal-body">

            <ul class="nav nav-tabs" id="nav_new_order_line">
               <li id="li_new_product"  ><a href="javascript:void(0);" id="b_new_product" >{{ l('Coded Product') }}</a></li>
               <li id="li_new_service"  ><a href="javascript:void(0);" id="b_new_service" >{{ l('Service (not coded)') }}</a></li>
               <li id="li_new_discount" ><a href="javascript:void(0);" id="b_new_discount">{{ l('Discount') }}</a></li>
               <!-- li id="li_new_text_line" ><a href="javascript:void(0);" id="b_new_text_line" >{{ l('Text Line') }}</a></li -->
            </ul>

                {{-- csrf_field() --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="line_id">
                <input type="hidden" id="line_product_id">

         </div>

         <div id="new_product" class="modal-body">
            <form id="f_product_search" name="f_product_search" action="" method="post" class="form">
               
               <div class="container-fluid" style="xpadding-top: 20px;">
                  <div class="row">
                     <div class="col-lg-5 col-md-5 col-sm-5">
                        <div class="input-group">
                           <input class="form-control" type="text" name="query" autocomplete="off"/>
                           <span class="input-group-btn">
                              <button class="btn btn-primary" type="submit">
                                 <i class="fa fa-search"></i>
                              </button>
                           </span>
                        </div>
                     </div>
                     <div class="col-lg-3 col-md-3 col-sm-3">
                        <label>
                           <input type="checkbox" name="onhand_only" value="1" onchange="product_search()"/>
                           {{ l('Only Products with Stock') }}
                        </label>
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
                  </div>
               </div>
            </form>
         </div>

         <div id="search_results" style="padding-top: 20px;"></div>

         </div>

         <div id="new_service" class="modal-body" style="display: none;">
            <form id="f_new_service" name="f_new_service" action="" method="post" class="form">
               <div class="row">
                 <div class="form-group col-lg-10 col-md-10 col-sm-10">
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
                    { ! ! Form::select('tax_id', $taxList, \App\Configuration::get('DEF_TAX'), array('class' => 'form-control', 'id' => 'tax_id', 'onchange' => 'calculate_service_price( )')) ! ! }
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    {{ l('With Tax') }}
                    {!! Form::text('price_tax_inc', null, array('class' => 'form-control', 'id' => 'price_tax_inc', 'onkeyup' => 'calculate_service_price( )', 'onchange' => 'calculate_service_price( )', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                    {!! $errors->first('price_tax_inc', '<span class="help-block">:message</span>') !!}
                 </div>
               </div>

               <div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_service_to_document();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}</a>
               </div>
            </form>
         </div>


         <div id="new_discount" class="modal-body" style="display: none;">
            <form id="f_new_discount" name="f_new_discount" action="" method="post" class="form">
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
                     { ! ! Form::select('discount_tax_id', $taxList, \App\Configuration::get('DEF_TAX'), array('class' => 'form-control', 'id' => 'discount_tax_id', 'onchange' => 'calculate_discount_price( )')) ! ! }
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
            </form>
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


@section('scripts')    @parent

{{-- Tabbed menu --}}

<script type="text/javascript">

$(document).ready(function() {
   
   $("#b_new_product").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_product").addClass('active');
      $("#new_product").show();
      document.f_product_search.query.focus();
   });
   
   $("#b_new_service").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_service").addClass('active');
      $("#new_service").show();
      document.f_new_service.name.select();
   });
   
   $("#b_new_discount").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_discount").addClass('active');
      $("#new_discount").show();
      document.f_new_discount.discount_name.select();
   });

      // To get focus properly:
      $("#b_new_product").trigger("click");

});

function modal_search_tab_hide_all() {

  $("#nav_new_order_line li").each(function() {
     $(this).removeClass("active");
  });

     $("#new_product").hide();
     $("#new_service").hide();
     $("#new_discount").hide();
}

</script>

@endsection
