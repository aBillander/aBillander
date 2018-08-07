
<div id="panel_customer_order"> 

<div class="panel panel-info" id="panel_update_order">

   <div class="panel-heading">
      <h3 class="panel-title collapsed" data-toggle="collapse" data-target="#header_data">{{ l('Header Data') }} :: <span class="label label-warning" title="{{ l('Order Date') }}">{{ $order->document_date_form }}</span> - <span class="label label-info" title="{{ l('Delivery Date') }}">{{ $order->delivery_date_form ?? ' -- / -- / -- '}}</span></h3>
   </div>
   <div id="header_data" class="panel-collapse collapse">
    {!! Form::model($order, array('method' => 'PATCH', 'route' => array('customerorders.update', $order->id), 'id' => 'update_customer_order', 'name' => 'update_customer_order', 'class' => 'form')) !!}


<!-- Order header -->

{!! Form::hidden('order_id', $order->id, array('id' => 'order_id')) !!}
{!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
{!! Form::hidden('sales_equalization', $customer->sales_equalization, array('id' => 'sales_equalization')) !!}
{!! Form::hidden('invoicing_address_id', null, array('id' => 'invoicing_address_id')) !!}
{!! Form::hidden('taxing_address_id', $order->taxingaddress->id, array('id' => 'taxing_address_id')) !!}

               <div class="panel-body">

      <div class="row">

         <div class="form-group col-lg-8 col-md-8 col-sm-8">
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('reference') ? 'has-error' : '' }}">
            {{ l('Reference / Project') }}
            {!! Form::text('reference', null, array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('sales_rep_id') ? 'has-error' : '' }}">
            {{ l('Sales Representative') }}
            {!! Form::select('sales_rep_id', array('0' => l('-- Please, select --', [], 'layouts')) + $salesrepList, null, array('class' => 'form-control', 'id' => 'sales_rep_id')) !!}
            {!! $errors->first('sales_rep_id', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('document_date') ? 'has-error' : '' }}">
               {{ l('Order Date') }}
               {!! Form::text('document_date_form', null, array('class' => 'form-control', 'id' => 'document_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('document_date', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="col-lg-2 col-md-2 col-sm-2 {{ $errors->has('delivery_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Delivery Date') }}
               {!! Form::text('delivery_date_form', null, array('class' => 'form-control', 'id' => 'delivery_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('delivery_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

      <!-- /div>
      <div class="row" -->

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('payment_method_id') ? 'has-error' : '' }}">
            {{ l('Payment Method') }}
            {!! Form::select('payment_method_id', array('0' => l('-- Please, select --', [], 'layouts')) + $payment_methodList, null, array('class' => 'form-control', 'id' => 'payment_method_id')) !!}
            {!! $errors->first('payment_method_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_id') ? 'has-error' : '' }}">
            {{ l('Currency') }}
            {!! Form::select('currency_id', $currencyList, null, array('class' => 'form-control', 'id' => 'currency_id', 'onchange' => 'get_currency_rate($("#currency_id").val())')) !!}
            {!! $errors->first('currency_id', '<span class="help-block">:message</span>') !!}

            {!! Form::hidden('currency_decimalPlaces', $order->currency->decimalPlaces, array('id' => 'currency_decimalPlaces')) !!}
         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('currency_conversion_rate') ? 'has-error' : '' }}">

            {{ l('Conversion Rate') }}
            <div  class="input-group">
              {!! Form::text('currency_conversion_rate', null, array('class' => 'form-control', 'id' => 'currency_conversion_rate')) !!}
              {!! $errors->first('currency_conversion_rate', '<span class="help-block">:message</span>') !!}

              <span class="input-group-btn" title="{{ l('Update Conversion Rate') }}">
              <button class="btn btn-md btn-lightblue" type="button" onclick="get_currency_rate($('#currency_id').val());">
                  <span class="fa fa-money"></span>
              </button>
              </span>
            </div>

         </div>

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('down_payment') ? 'has-error' : '' }}">
            {{ l('Down Payment') }}
            {!! Form::text('down_payment', null, array('class' => 'form-control', 'id' => 'down_payment')) !!}
            {!! $errors->first('down_payment', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', $customer->getAddressList(), null, array('class' => 'form-control', 'id' => 'shipping_address_id', 'onchange' => 'set_invoicing_address($(this).val())')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', $warehouseList, null, array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('shipping_method_id') ? 'has-error' : '' }}">
            {{ l('Shipping Method') }}
            {!! Form::select('shipping_method_id', array('' => l('-- Please, select --', [], 'layouts')) + $shipping_methodList, null, array('class' => 'form-control', 'id' => 'shipping_method_id')) !!}
            {!! $errors->first('shipping_method_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_conditions') ? 'has-error' : '' }}">
            {{ l('Shipping Conditions') }}
            {!! Form::textarea('shipping_conditions', null, array('class' => 'form-control', 'id' => 'shipping_conditions', 'rows' => '1')) !!}
            {!! $errors->first('shipping_conditions', '<span class="help-block">:message</span>') !!}
         </div>

      </div>
      <div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('notes') ? 'has-error' : '' }}" xstyle="margin-top: 20px;">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('notes_to_customer') ? 'has-error' : '' }}">
            {{ l('Notes to Customer') }}
            {!! Form::textarea('notes_to_customer', null, array('class' => 'form-control', 'id' => 'notes_to_customer', 'rows' => '2')) !!}
            {{ $errors->first('notes_to_customer', '<span class="help-block">:message</span>') }}
         </div>

      </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>

<!-- Order header ENDS -->


    {!! Form::close() !!}
   </div>

              <div class="panel-footer text-right">       </div>

</div>

<!-- Order Lines -->

<div id="msg-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<div id="msg-success-delete" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-delete-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<div id="panel_customer_order_lines" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('customer_orders._panel_order_lines') --}}

</div>


@include('customer_orders._modal_order_line_form')

@include('customer_orders._modal_order_line_delete')

<!-- Order Lines ENDS -->

</div>


@section('scripts')    @parent

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->
    {{-- See: Laravel 5.4 ajax todo project: Autocomplete search #7 --}}



@include('customer_orders._chunck_js_service')


    <script type="text/javascript">

        var PRICE_DECIMAL_PLACES;

        $(document).ready(function() {

//          loadBOMlines();




          $(document).on('click', '.create-order-product', function(evnt) {

           
               var panel = $("#order_line_form");
               var url = "{{ route('customerorderline.productform', ['create']) }}";

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();
                     // sortableCustomerOrderLines();
               }, 'html').done( function() { 

                    var selector = "#line_autoproduct_name";
                    var next = $('#next_line_sort_order').val();

                    $('#line_type').val('product');

                    auto_product_line( selector );

                    $('#line_id').val('');
//                    $('#line_type').val('');
                    $('#line_sort_order').val(next);
                    $('#line_quantity').val(1);
                    $('#line_measure_unit_id').val(0);

                    $('#line_price').val(0.0);

                    $('#line_discount_percent').val(0.0);
                    $('#line_discount_amount_tax_incl').val(0.0);
                    $('#line_discount_amount_tax_excl').val(0.0);

                    if ($('#sales_equalization').val()>0) {
                        $('input:radio[name=line_is_sales_equalization]').val([1]);
                        $('#line_sales_equalization').show();
                    }

                    calculate_line_product();

                    $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
                    $('#line_commission_percent').val( 0.0 );   // Use default

                    $('#line_notes').val('');

                    $("#line_autoproduct_name").val('');
                    $("#line_reference").val('');
                    $('#line_product_id').val('');
                    $('#line_combination_id').val('');


                    $('#modal_order_line').modal({show: true});
                    $("#line_autoproduct_name").focus();

                });

              return false;
          });

          $(document).on('click', '.edit-order-line', function(evnt) {

              // What to do? Let's see:
              var line_type = $(this).attr('data-type');

              switch( line_type ) {
                  case 'product':
                      
                      editCustomerOrderProductLine( $(this) );
                      break;

                  case 'service':
                  case 'shipping':
                      
                      editCustomerOrderServiceLine( $(this) );
                      break;

                  default:
                      // Not good to reach this point
                      return false;
              } 

              return false;

          });
          

          function editCustomerOrderProductLine( selector ) {

            // Load form first
               var panel = $("#order_line_form");
               var url = "{{ route('customerorderline.productform', ['edit']) }}";

               panel.html('');
               panel.addClass('loading');

               $.get(url, {async:false}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();
                     // sortableCustomerOrderLines();
               }, 'html');


            // Populate form
            // Set delay to wait for form load
            setTimeout(function(){  }, 1000); 

              var id = selector.attr('data-id');
              var line_type = selector.attr('data-type');
              var url = "{{ route('customerorder.getline', [$order->id, '']) }}/"+id;
              var label = '';

              PRICE_DECIMAL_PLACES = $('#currency_decimalPlaces').val();

              // if ( line_type == 'product' )
              
              $.get(url, function(result){
                    var label = '['+result.product.reference+'] '+result.product.name+' ('+result.measureunit.name+')';
                    var QUANTITY_DECIMAL_PLACES = result.product.quantity_decimal_places;

                    $('#modal_product_order_line_Label').text(label);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_combination_id').val(result.combination_id);
                    $('#line_type').val(result.line_type);

                    $('#line_name').val(result.name);
                    $('#line_reference').val(result.reference);

                    $('#line_quantity_decimal_places').val( QUANTITY_DECIMAL_PLACES );
                    $('#line_quantity').val(result.quantity.round( QUANTITY_DECIMAL_PLACES ));
                    $('#line_measure_unit_id').val(result.measure_unit_id);

                    $('#line_cost_price').val(result.cost_price);
                    $('#line_unit_price').val(result.unit_price);
                    $('#line_unit_customer_price').val(result.unit_customer_price);
                    
                    $("#line_price").val( result.unit_customer_final_price.round( PRICE_DECIMAL_PLACES ) );
                    $("#line_price").val( result.unit_customer_final_price );
                    $('#line_discount_percent').val(result.discount_percent);

                    $('#discount_amount_tax_incl').val(result.discount_amount_tax_incl);
                    $('#discount_amount_tax_excl').val(result.discount_amount_tax_excl);

                    $('#line_tax_label').html(result.tax_label);
                    $('#line_tax_id').val(result.tax_id);
                    $('#line_tax_percent').val(result.tax_percent);

                    if ($('#sales_equalization').val()>0) {
                        $('input:radio[name=line_is_sales_equalization]').val([1]);
                        $('#line_sales_equalization').show();
                    }

                    calculate_line_product( );

                    $('#line_sales_rep_id').val( result.sales_rep_id );
                    $('#line_commission_percent').val( result.commission_percent );
                    
                    $('#line_notes').val(result.notes);

                    console.log(result);
              });

              $('#modal_order_line').modal({show: true});
              $("#line_quantity").focus();

              return false;

          };


          loadCustomerOrderlines();


          $(document).on('click', '.update-order-total', function(evnt) {

              updateCustomerOrderTotal();
              return false;

          });

          

        });       // $(document).ready(function() {    ENDS
  

        function loadCustomerOrderlines() {
           
           var panel = $("#panel_customer_order_lines");
           var url = "{{ route('customerorder.getlines', $order->id) }}";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
                 sortableOrderlines();
           }, 'html');

        }

        function updateCustomerOrderTotal() {
           
           var panel = $("#panel_customer_order_total");
           var url = "{{ route('customerorder.updatetotal', $order->id) }}";
           var token = "{{ csrf_token() }}";

           panel.addClass('loading');

            $.ajax({
                url: url,
                headers : {'X-CSRF-TOKEN' : token},
                method: 'POST',
                dataType: 'html',
                data: {
                    document_discount_percent: $("#document_discount_percent").val()
                },
                success: function (response) {

                   panel.html(response);
                   panel.removeClass('loading');
                   $("[data-toggle=popover]").popover();
                }
            });

        }

        function sortableOrderlines() {

          // Sortable :: http://codingpassiveincome.com/jquery-ui-sortable-tutorial-save-positions-with-ajax-php-mysql
          // See: https://stackoverflow.com/questions/24858549/jquery-sortable-not-functioning-when-ajax-loaded
          $('.sortable').sortable({
              cursor: "move",
              update:function( event, ui )
              {
                  $(this).children().each(function(index) {
                      if ( $(this).attr('data-sort-order') != ((index+1)*10) ) {
                          $(this).attr('data-sort-order', (index+1)*10).addClass('updated');
                      }
                  });

                  saveNewPositions();
              }
          });

        }

        function saveNewPositions() {
            var positions = [];
            var token = "{{ csrf_token() }}";

            $('.updated').each(function() {
                positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: "{{ route('customerorder.sortlines') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'POST',
                dataType: 'json',
                data: {
                    positions: positions
                },
                success: function (response) {
                    console.log(response);
                }
            });
        }

        $("body").on('click', "#modal_order_line_productSubmit", function() {

            var id = $('#line_id').val();
            var url = "{{ route('customerorder.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            if ( id == '' )
                url = "{{ route('customerorder.storeline', [$order->id]) }}";
            else
                url = "{{ route('customerorder.updateline', ['']) }}/"+id;

            var payload = { 
                              order_id : {{ $order->id }},
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              quantity : $('#line_quantity').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_unit_price').val(),
                              unit_customer_price : $('#line_unit_customer_price').val(),
                              unit_customer_final_price : $('#line_price').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_percent : $('#line_tax_percent').val(),
                              sales_equalization : $("input[name='line_is_sales_equalization']:checked").val(),
                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
                              discount_percent : $('#line_discount_percent').val(),
                              discount_amount_tax_incl : $('#line_discount_amount_tax_incl').val(),
                              discount_amount_tax_excl : $('#line_discount_amount_tax_excl').val(),
                              sales_rep_id : $('#line_sales_rep_id').val(),
                              commission_percent : $('#line_commission_percent').val(),
                              notes : $('#line_notes').val()
                          };



//    pload = pload + "&customer_id="+$("#customer_id").val();
//    pload = pload + "&currency_id="+$("#currency_id").val()+"&conversion_rate="+$("#currency_conversion_rate").val();
//    pload = pload + "&_token="+$('[name="_token"]').val();

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    loadCustomerOrderlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_order_line').modal('toggle');

                    showAlertDivWithDelay("#msg-success");
                }
            });

        });

        $("body").on('click', "#modal_edit_order_line_productSubmit", function() {

            var id = $('#line_id').val();
            var url = "{{ route('customerorder.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            if ( id == '' )
                url = "{{ route('customerorder.storeline', [$order->id]) }}";
            else
                url = "{{ route('customerorder.updateline', ['']) }}/"+id;

            var payload = { 
                              order_id : {{ $order->id }},
                              line_id : id,
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              name : $('#line_name').val(),
                              quantity : $('#line_quantity').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_unit_price').val(),
                              unit_customer_price : $('#line_unit_customer_price').val(),
                              unit_customer_final_price : $('#line_price').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_percent : $('#line_tax_percent').val(),
                              sales_equalization : $("input[name='line_is_sales_equalization']:checked").val(),
                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
                              discount_percent : $('#line_discount_percent').val(),
                              discount_amount_tax_incl : $('#line_discount_amount_tax_incl').val(),
                              discount_amount_tax_excl : $('#line_discount_amount_tax_excl').val(),
                              sales_rep_id : $('#line_sales_rep_id').val(),
                              commission_percent : $('#line_commission_percent').val(),
                              notes : $('#line_notes').val()
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){
                    loadCustomerOrderlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_order_line').modal('toggle');

                    showAlertDivWithDelay("#msg-success");

                    console.log(response);
                }
            });

        });

        function auto_product_line( selector = "#line_autoproduct_name" ) {

            $( selector ).autocomplete({
                source : "{{ route('customerorderline.searchproduct') }}?customer_id="+$('#customer_id').val()+"&currency_id"+$('#currency_id').val(),
                minLength : 1,
                appendTo : "#modal_order_line",

                select : function(key, value) {
                    var str = '[' + value.item.reference+'] ' + value.item.name;

                    $("#line_autoproduct_name").val(str);
                    $('#line_product_id').val(value.item.id);
                    $('#line_combination_id').val(0)

                    getProductData( $('#line_product_id').val(), $('#line_combination_id').val() );

                    return false;
                }
            }).data('ui-autocomplete')._renderItem = function( ul, item ) {
                  return $( "<li></li>" )
                    .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
                    .appendTo( ul );
                };
        }


        function getProductData( product_id, combination_id ) {
            var price;
            var token = "{{ csrf_token() }}";
            // https://stackoverflow.com/questions/28417781/jquery-add-csrf-token-to-all-post-requests-data/28418032#28418032

            $.ajax({
                url: "{{ route('customerorderline.getproduct') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    combination_id: combination_id,
                    customer_id: $("#customer_id").val(),
                    currency_id: $("#currency_id").val(),
                    conversion_rate: $("#currency_conversion_rate").val(),
                    taxing_address_id: $("#taxing_address_id").val()
                },
                success: function (response) {
                    
                    if ($.isEmptyObject(response)) alert('Producto vacío!!!');

                    $('#line_reference').val(response.reference);
                    $('#line_measure_unit_id').val(response.measure_unit_id);

                    PRICE_DECIMAL_PLACES = $('#currency_decimalPlaces').val();

                    $('#line_cost_price').val(response.cost_price);
                    $('#line_unit_price').val(response.unit_price.display);
                    $('#line_tax_label').html(response.tax_label);
                    $('#line_tax_id').val(response.tax_id);
                    $('#line_tax_percent').val(response.tax_percent);

                    if( $("#sales_equalization").val()>0 )
                        $('input:radio[name=line_is_sales_equalization][value=1]').prop('checked', true);
                    else
                        $('input:radio[name=line_is_sales_equalization][value=0]').prop('checked', true);
                    
                    $('#line_discount_percent').val(0);
//                    price = parseFloat(response.unit_customer_price.display);
                    price = response.unit_customer_price.display;
                    $("#line_unit_customer_price").val( price );
                    $("#line_price").val( price.round( PRICE_DECIMAL_PLACES ) );
                    $("#line_price").val( price );

                    calculate_line_product();

                    console.log(response);
                }
            });
        }


        $("#modal_order_line_serviceSubmit").click(function() {

 //         alert('etgwer');

            var id = $('#line_id').val();
            var url = "{{ route('customerorder.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            if ( id == '' )
                url = "{{ route('customerorder.storeline', [$order->id]) }}";
            else
                url = "{{ route('customerorder.updateline', ['']) }}/"+id;

  //        alert(url);

            var payload = { 
                              order_id : {{ $order->id }},
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              name : $('#name').val(),
                              quantity : 1,
                              is_shipping : $("input[name='is_shipping']:checked").val(),
                              cost_price : $('#cost_price').val(),
                              price : $('#price').val(),
                              price_tax_inc : $('#price_tax_inc').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_id : $('#tax_id').val(),
                              tax_percent : $('#line_tax_percent').val(),
                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
//                              sales_rep_id : $('#line_sales_rep_id').val(),
                              notes : $('#service_notes').val()
                          };



//    pload = pload + "&customer_id="+$("#customer_id").val();
//    pload = pload + "&currency_id="+$("#currency_id").val()+"&conversion_rate="+$("#currency_conversion_rate").val();
//    pload = pload + "&_token="+$('[name="_token"]').val();

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    loadCustomerOrderlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_order_line').modal('toggle');
                    $("#msg-success").fadeIn();
                }
            });

/*            $(function () {  $('[data-toggle="tooltip"]').tooltip()});
            $("[data-toggle=popover]").popover();
            $(function () {
  $('[data-toggle="popover"]').popover()
})
*/
        });

    </script>





<script>


function set_invoicing_address()
{
  @if ( \App\Configuration::get('TAX_BASED_ON_SHIPPING_ADDRESS') )

    var theValue = $('#shipping_address_id').val();

    $("#taxing_address_id").val(theValue);

  @endif
}


function get_currency_rate(currency_id)
{
    var pload = '';

    pload = pload + "currency_id="+currency_id;
    pload = pload + "&_token="+$('[name="_token"]').val();

   $.ajax({
      type: 'POST',
      url: '{{ route('currencies.ajax.rateLookup') }}',
      dataType: 'html',
      data: pload,
      success: function(data) {
         var theHTML = data;
         if ( theHTML == '' ) {
              theHTML = '<div class="alert alert-warning alert-block"><i class="fa fa-warning"></i> {{l('No records found', [], 'layouts')}}</div>';
         }
         $("#currency_conversion_rate").val(theHTML);
      }
   });
}

</script>



{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#delivery_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection


@section('styles')    @parent

<style>
  .panel-heading h3:after {
      font-family:'FontAwesome';
      content:"\f077";
      float: right;
      xcolor: grey;
  }
  .panel-heading h3.collapsed:after {
      font-family:'FontAwesome';
      content:"\f078";
      float: right;
  }
</style>

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>


{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection
