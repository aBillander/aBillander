   <script type="text/javascript">

        var PRICE_DECIMAL_PLACES;

        $(document).ready(function() {




          $(document).on('click', '.create-document-product', function(evnt) {

           
               var panel = $("#document_line_form");
               var url = "{{ route('productionorders.productform', ['create']) }}";

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();
                     // sortableDocumentLines();
               }, 'html').done( function() { 

                    var selector = "#line_autoproduct_name";
                    var next = $('#next_line_sort_order').val();

                    $('#line_type').val('product');

                    auto_product_line( selector );

                    $('#line_id').val('');
//                    $('#line_type').val('');
                    $('#line_sort_order').val(next);
                    $('#line_required_quantity').val(1);
                    $('#line_measure_unit_id').val(0);
                    $('#line_quantity_decimal_places').val(0);

                    $("#line_autoproduct_name").val('');
                    $("#line_reference").val('');
                    $('#line_product_id').val('');
                    $('#line_combination_id').val('');


                    $('#modal_document_line').modal({show: true});
                    $("#line_autoproduct_name").focus();

                });

              return false;
          });

          $(document).on('click', '.edit-document-line', function(evnt) {

              // What to do? Let's see:
              var line_type = $(this).attr('data-type');

              switch( line_type ) {
                  case 'product':
                      
                      editDocumentProductLine( $(this) );
                      break;

                  case 'service':
                  case 'shipping':
                      
                      editDocumentServiceLine( $(this) );
                      break;

                  case 'comment':
                      
                      editDocumentCommentLine( $(this) );
                      break;

                  default:
                      // Not good to reach this point
                      return false;
              } 

              return false;

          });
          

          function editDocumentProductLine( selector ) {

            // Load form first
               var panel = $("#document_line_form");
               var url = "{{ route('productionorders.productform', ['edit']) }}";

               panel.html('');
               panel.addClass('loading');

               $.get(url, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();

                     // Populate form
                     getProductLineData( selector );

               }, 'html');


              $('#modal_document_line').modal({show: true});
              $("#line_quantity").focus();

              return false;

          };


          function getProductLineData( selector ) {
                          
              var id = selector.attr('data-id');
              var line_type = selector.attr('data-type');
              var url = "{{ route('productionorders.getline', [$document->id, '']) }}/"+id;
              var label = '';

              
              $.get(url, function(result){
                    var label = '['+result.product.reference+'] '+result.product.name+' ('+result.measureunit.name+')';
                    var QUANTITY_DECIMAL_PLACES = result.measureunit.decimalPlaces;

                    $('#modal_product_document_line_Label').text(label);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_combination_id').val(result.combination_id);
                    $('#line_type').val(result.line_type);

                    $('#line_name').val(result.name);
                    $('#line_reference').val(result.reference);

                    $('#line_quantity_decimal_places').val( QUANTITY_DECIMAL_PLACES );
                    $('#line_required_quantity').val(result.required_quantity.round( QUANTITY_DECIMAL_PLACES ));

                    $('#line_measure_unit_id').val(result.measure_unit_id);
                    $('#line_measure_unit_name').text(result.measureunit.name);
                    
                    $('#line_warehouse_id').val(result.warehouse_id);

                    calculate_line_product( );

                    console.log(result);
              });
          }


          $(document).on('click', '.update-document-total', function(evnt) {

              updateDocumentTotal();
              return false;

          });
          

          $(document).on('keydown','.input-update-document-total', function(e){
        
            if (e.keyCode == 13) {
             // console.log("put function call here");
             e.preventDefault();
             updateDocumentTotal();
             return false;
            }

          });


          loadDocumentlines();

          // loadDocumentPayments();  // Not needed!

          

        });       // $(document).ready(function() {    ENDS




{{-- *************************************** --}}



    // See: https://stackoverflow.com/questions/20705905/bootstrap-3-jquery-event-for-active-tab-change
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      if (target == '#tab3default')
      {
          getDocumentProfit();
      }
      if (target == '#tab1default' && 0)
      {
          // loadDocumentHeader();
          location.reload(); 
      }
      if (target == '#tab5default')
      {
          loadDocumentPayments();
      }
      /*
      if ($(target).is(':empty')) {
        $.ajax({
          type: "GET",
          url: "/article/",
          error: function(data){
            alert("There was a problem");
          },
          success: function(data){
            $(target).html(data);
          }
      })
     }
     */
    });
  

        function loadDocumentHeader() {
           
           var panel = $("#tab1default");
           var url = "{{ route('productionorders.getheader', $document->id) }}";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();

           }, 'html');

        }

        function loadDocumentlines() {
           
           var panel = $("#panel_production_order_lines");
           var url = "{{ route('productionorders.getlines', $document->id) }}";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
                 sortableDocumentlines();
           }, 'html');

        }

        function loadDocumentPayments() {
           
           var panel = $("#panel_production_order_payments");
           var url = "{ { route('productionorders.getpayments', $document->id) } }";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
                 
           }, 'html');

        }

        function updateDocumentTotal() {
           
           var panel = $("#panel_document_total");
           var url = "{{ route('productionorders.updatetotal', $document->id) }}";
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

//                   console.log(response);

                   panel.html(response);
                   panel.removeClass('loading');
                   $("[data-toggle=popover]").popover();

                    showAlertDivWithDelay("#msg-success-update");
                }
            });

        }

        function sortableDocumentlines() {

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
                url: "{{ route('productionorders.sortlines') }}",
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

        $("body").on('click', ".modal_document_line_productSubmit", function( event ) {

            var clicked = event.target;

            // alert(clicked.name); return;

            var id = $('#line_id').val();
            var url = "{{ route('productionorders.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            var store_mode = '';

            if (clicked.name  == 'modal_document_line_productSubmitAsIs')
                store_mode = 'asis';

            if ( id == '' )
                url = "{{ route('productionorders.storeline', [$document->id]) }}";
//            else
//                url = "{{ route('productionorders.updateline', ['']) }}/"+id;

            var payload = { 
                              document_id : {{ $document->id }},
                              store_mode : store_mode,
                              line_sort_order : $('#line_sort_order').val(),
                              type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              required_quantity : $('#line_required_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              warehouse_id : $('#line_warehouse_id').val()
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

                success: function(response){
                    loadDocumentlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_document_line').modal('toggle');

                    if ( response.msg == 'OK' )
                      showAlertDivWithDelay("#msg-success");
                    else
                      showAlertDivWithDelay("#msg-error");
                }
            });

        });

        $("body").on('click', "#modal_edit_document_line_productSubmit", function() {

            var id = $('#line_id').val();
            var url = "{{ route('productionorders.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            var payload = { 
                              document_id : {{ $document->id }},
                              line_id : id,
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              name : $('#line_name').val(),
                              required_quantity : $('#line_required_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              warehouse_id : $('#line_warehouse_id').val()
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){
                    loadDocumentlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_document_line').modal('toggle');

                    showAlertDivWithDelay("#msg-success");

                    console.log(response);
                }
            });

        });

        function auto_product_line( selector = "#line_autoproduct_name" ) {

            // See: http://jsfiddle.net/chridam/hfre25pf/

            $( selector ).autocomplete({
                source : "{{ route('productionorders.searchproduct') }}?search_parts=1",
                minLength : 1,
                appendTo : "#modal_document_line",

                response: function(event, ui) {
                    if (!ui.content.length) {
                        var noResult = { 
                             id: "", 
                             reference: "",
                             name: "{{ l('No records found', 'layouts') }}" 
                         };
                         ui.content.push(noResult);                    
                     } else {
                        // $("#message").empty();
                     }
                },

                select : function(key, value) {
                    var str = '[' + value.item.reference+'] ' + value.item.name;
                    var QUANTITY_DECIMAL_PLACES = value.item.measureunit.decimalPlaces;

                    if ( value.item.id != '' ) {
                        $("#line_autoproduct_name").val(str);
                        $('#line_product_id').val(value.item.id);
                        $('#line_combination_id').val(0)
    
                        // getProductData( $('#line_product_id').val(), $('#line_combination_id').val() );
    
                        // getProductPriceData( $('#line_product_id').val(), $('#line_combination_id').val(), $("#customer_id").val() );

                        $('#line_type').val('product');

                        $('#line_name').val(value.item.name);
                        $('#line_reference').val(value.item.reference);

                        $('#line_quantity_decimal_places').val( QUANTITY_DECIMAL_PLACES );
                        // $('#line_required_quantity').val(value.item.manufacturing_batch_size.round( QUANTITY_DECIMAL_PLACES ));
                        $('#line_required_quantity').val('1.0');

                        $('#line_measure_unit_id').val(value.item.measure_unit_id);
                        $('#line_measure_unit_name').text(value.item.measureunit.name);
                    }

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
                url: "{{ route('productionorders.getproduct') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    combination_id: combination_id,
                    customer_id: $("#customer_id").val(),
                    sales_rep_id: $("#sales_rep_id").val(),                    
                    currency_id: $("#currency_id").val(),
                    conversion_rate: $("#currency_conversion_rate").val(),
                    taxing_address_id: $("#taxing_address_id").val()
                },
                success: function (response) {
                    
                    if ($.isEmptyObject(response)) alert('Producto vacío!!!');

                    $('#line_reference').val(response.reference);
                    $('#line_measure_unit_id').val(response.measure_unit_id);

                    // Populate Measure Units
                    munits = response.measure_units;

                    $('select[name="line_package_measure_unit_id"]').empty();
                    // $('select[name="line_package_measure_unit_id"]').append('<option value="">{{ l('-- Please, select --', [], 'layouts') }}</option>');
                    $.each(munits, function (key, value) {
                        $('select[name="line_package_measure_unit_id"]').append('<option value=' + key + '>' + value + '</option>');
                    });

                    $('select[name="line_package_measure_unit_id').val(response.measure_unit_id);



                    $('#line_quantity').val(1);
                    $('#line_quantity_decimal_places').val(response.quantity_decimal_places);

                    PRICE_DECIMAL_PLACES = $('#currency_decimalPlaces').val();

                    $('#line_cost_price').val(response.cost_price);
                    
                    $('#line_tax_label').html(response.tax_label);
                    $('#line_tax_id').val(response.tax_id);
                    $('#line_tax_percent').val(response.tax_percent);

                    if( $("#sales_equalization").val()>0 )
                        $('input:radio[name=line_is_sales_equalization][value=1]').prop('checked', true);
                    else
                        $('input:radio[name=line_is_sales_equalization][value=0]').prop('checked', true);
                    
                    $('#line_discount_percent').val(0);
//                    price = parseFloat(response.unit_customer_price.display);

                    $('#line_is_prices_entered_with_tax').val(response.unit_customer_price.price_is_tax_inc);

                    if ( $('#line_is_prices_entered_with_tax').val() > 0 )
                    {
                        
                        unit_price = response.unit_price.tax_inc;
                        //
                        price = response.unit_customer_price.tax_inc;

                        // set labels
                        $(".label_tax_exc").hide();
                        $(".label_tax_inc").show();

                    } else {

                        unit_price = response.unit_price.tax_exc;
                        //
                        price = response.unit_customer_price.tax_exc;

                        // set labels
                        $(".label_tax_inc").hide();
                        $(".label_tax_exc").show();

                    }

                    $('#line_unit_price').val( unit_price );
                    $("#line_unit_customer_price").val( price );
                    // $("#line_price").val( price.round( PRICE_DECIMAL_PLACES ) );
                    $("#line_price").val( price );

                    $("#label_ecotax_value").html(response.ecotax_value_label);

                    $('#line_commission_percent').val(response.commission_percent);

                    calculate_line_product();

                    console.log(response);
                }
            });
        }


        function getProductPriceData( product_id, combination_id, customer_id ) {
            var token = "{{ csrf_token() }}";
           
            var panel = $("#product_price_data");

            panel.addClass('loading');

            $.ajax({
                url: "{ { route('productionorders.getproduct.prices') } }",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'html',
                data: {
                    product_id: product_id,
                    combination_id: combination_id,
                    customer_id: $("#customer_id").val(),
                    recent_sales_this_customer: 1,
                    currency_id: $("#currency_id").val(),
                    conversion_rate: $("#currency_conversion_rate").val(),
                    taxing_address_id: $("#taxing_address_id").val()
                },
                success: function (response) {
                    
                    // if ($.isEmptyObject(response)) alert('Producto vacío!!!');
                    
                   panel.html(response);
                   panel.removeClass('loading');
                   $("[data-toggle=popover]").popover();


                   $("#convinient_buttons").show();


                    console.log(response);
                },
                error: function(xhr, status) {
                         panel.html( '' );

                    // check if xhr.status is defined in $.ajax.statusCode
                    // if true, return false to stop this function
                    if (typeof this.statusCode[xhr.status] != 'undefined') {
                        return false;
                    }
                    // else continue
                    console.log('ajax.error');
                },
                statusCode: {
                    404: function(response) {
                        console.log('ajax.statusCode: 404');
                    },
                    500: function(response) {
                        panel.html( JSON.parse(response.responseText).message );
                        panel.removeClass('loading');
                        console.log(response);
                        console.log('ajax.statusCode: 500');
                    }
                }
            });
        }



    </script>





<script>


function set_invoicing_address()
{
  @if ( AbiConfiguration::get('TAX_BASED_ON_SHIPPING_ADDRESS') )

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
