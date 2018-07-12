    <script type="text/javascript">

//        var PRICE_DECIMAL_PLACES;

		var ORDER_AVAILABLE_TAXES= {!! json_encode( $order->taxingaddress->getTaxList() ) !!} ;

        $(document).ready(function() {


          $(document).on('click', '.create-order-service', function(evnt) {

           
               var panel = $("#order_line_form");
               var url = "{{ route('customerorderline.serviceform', ['create']) }}";

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();
                     // sortableCustomerOrderLines();
               }, 'html').done( function() { 

                    var selector = "#line_autoservice_name";
                    var next = $('#next_line_sort_order').val();

                    $('#line_type').val('service');

                    auto_service_line( selector );

                    $('#line_id').val('');
//                    $('#line_type').val('');
                    $('#line_sort_order').val(next);
                    $('#line_quantity').val(1);
                    $('#line_quantity_decimal_places').val(0);
                    $('#line_measure_unit_id').val( {{ \App\Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS') }} );

                    $('#line_cost_price').val(0.0);
                    $('#line_price').val(0.0);

                    $('#line_discount_percent').val(0.0);
                    $('#line_discount_amount_tax_incl').val(0.0);
                    $('#line_discount_amount_tax_excl').val(0.0);
/*
                    // By default, line_is_sales_equalization = 0 , because this is a service. 

                    if ($('#sales_equalization').val()>0) {
                        $('input:radio[name=line_is_sales_equalization]').val([1]);
                        $('#line_sales_equalization').show();
                    }
*/
                    // Populate Taxes
                    // https://www.codebyamir.com/blog/populate-a-select-dropdown-list-with-json
                    cb = '';
                    $.each(ORDER_AVAILABLE_TAXES, function(key, data){
				         cb += '<option value="'+key+'">'+data+'</option>';
				    });
				    $("#line_tax_id").append(cb);

				    $('#line_tax_id').val({{ \App\Configuration::get('DEF_TAX') }});

                    calculate_service_price();

                    $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
                    $('#line_commission_percent').val( 0.0 );   // Use default

                    $('#line_notes').val('');

                    $("#line_autoservice_name").val('');
                    $("#line_reference").val('');
                    $('#line_product_id').val('');
                    $('#line_combination_id').val('');


                    $('#modal_order_line').modal({show: true});
                    $("#line_autoservice_name").focus();

                });

              return false;
          });


        $("body").on('click', "#modal_order_line_serviceSubmit", function() {

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
                              name : $('#line_autoservice_name').val(),
                              quantity : $('#line_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              is_shipping : $("input[name='line_is_shipping']:checked").val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_unit_price').val(),
                              unit_customer_price : $('#line_unit_customer_price').val(),
                              unit_customer_final_price : $('#line_price').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_id : $('#line_tax_id').val(),
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


        $("body").on('click', "#modal_edit_order_line_serviceSubmit", function() {

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
                              name : $('#line_autoservice_name').val(),
                              quantity : $('#line_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              is_shipping : $("input[name='line_is_shipping']:checked").val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_unit_price').val(),
                              unit_customer_price : $('#line_unit_customer_price').val(),
                              unit_customer_final_price : $('#line_price').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_id : $('#line_tax_id').val(),
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


        });			// $(document).ready(function() {   ENDS


    	function editCustomerOrderServiceLine( selector ) {

            // Load form first
               var panel = $("#order_line_form");
               var url = "{{ route('customerorderline.serviceform', ['edit']) }}";

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

              // if ( line_type == 'service' || 'shipping' )

              $.get(url, function(result){
                    // label = '['+result.product.reference+'] '+result.product.name;
                    label = result.name;
                    var QUANTITY_DECIMAL_PLACES = 0;		// This is a Service!
                    
                    $('#modal_service_order_line_Label').text(label);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_combination_id').val(result.combination_id);
                    $('#line_type').val(result.line_type);

                    $('#line_name').val(result.name);
                	$("#line_autoservice_name").val(result.name);
                	// auto_product_line( "#line_autoservice_name" );
                    $('#line_reference').val(result.reference);

                    $('#line_quantity_decimal_places').val( QUANTITY_DECIMAL_PLACES );
                    $('#line_quantity').val(result.quantity.round( QUANTITY_DECIMAL_PLACES ));
                    $('#line_measure_unit_id').val(result.measure_unit_id);

                    // Shipping
                    if ( result.line_type == 'shipping' )
                          $('input:radio[name=line_is_shipping]').val([1]);
                    else
                          $('input:radio[name=line_is_shipping]').val([0]);

                    $('#line_cost_price').val(result.cost_price);
                    $('#line_unit_price').val(result.unit_price);
                    $('#line_unit_customer_price').val(result.unit_customer_price);
                    
                    $("#line_price").val( result.unit_customer_final_price.round( PRICE_DECIMAL_PLACES ) );
                    $('#line_discount_percent').val(result.discount_percent);

                    $('#discount_amount_tax_incl').val(result.discount_amount_tax_incl);
                    $('#discount_amount_tax_excl').val(result.discount_amount_tax_excl);
/*
                // By default, line_is_sales_equalization = 0 , because this is a service. 

                    if ($('#sales_equalization').val()>0) {
                        $('input:radio[name=line_is_sales_equalization]').val([1]);
                        $('#line_sales_equalization').show();
                    }
*/

                // sales_equalization
                $('input:radio[name=line_is_sales_equalization]').val([result.sales_equalization]);

                // Populate Taxes
                // https://www.codebyamir.com/blog/populate-a-select-dropdown-list-with-json
                cb = '';
                $.each(ORDER_AVAILABLE_TAXES, function(key, data){
			         cb += '<option value="'+key+'">'+data+'</option>';
			    });
			    $("#line_tax_id").append(cb);

			    $('#line_tax_id').val({{ \App\Configuration::get('DEF_TAX') }});


//                    $('#line_tax_label').html(result.tax_label);
                    $('#line_tax_percent').val(result.tax_percent);

                    calculate_service_price( );

                    $('#line_sales_rep_id').val( result.sales_rep_id );
                    $('#line_commission_percent').val( result.commission_percent );
                    
                    $('#line_notes').val(result.notes);

                    console.log(result);


              });

              $('#modal_order_line').modal({show: true});
              //  $("#line_autoservice_name").focus();
              $("#line_price").focus();

              return false;

          };





        function auto_service_line( selector = "#line_autoservice_name" ) {

            $( selector ).autocomplete({
                source : "{{ route('customerorderline.searchservice') }}?customer_id="+$('#customer_id').val()+"&currency_id"+$('#currency_id').val(),
                minLength : 1,
                appendTo : "#modal_order_line",

                select : function(key, value) {
                    var str = '[' + value.item.reference+'] ' + value.item.name;

                    $("#line_autoservice_name").val(str);
                    $('#line_product_id').val(value.item.id);
//                    $('#line_combination_id').val(0)

                    getServicetData( $('#line_product_id').val() );

                    return false;
                }
            }).data('ui-autocomplete')._renderItem = function( ul, item ) {
                  return $( "<li></li>" )
                    .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
                    .appendTo( ul );
                };
        }


        function getServicetData( product_id, combination_id = 0 ) {
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

                    // Disallow change Tax
                    $('#line_tax_id').off('click');
					$('#line_tax_id').click(function(){
					    $('#line_tax_id').blur();
					});

/*                    if( $("#sales_equalization").val()>0 )
                        $('input:radio[name=line_is_sales_equalization][value=1]').prop('checked', true);
                    else
                        $('input:radio[name=line_is_sales_equalization][value=0]').prop('checked', true);
*/                    
                    $('#line_discount_percent').val(0);
//                    price = parseFloat(response.unit_customer_price.display);
                    price = response.unit_customer_price.display;
                    $("#line_unit_customer_price").val( price );
                    $("#line_price").val( price.round( PRICE_DECIMAL_PLACES ) );

                    calculate_service_price();

                    console.log(response);
                }
            });
        }


    </script>