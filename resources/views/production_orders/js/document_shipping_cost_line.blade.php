  <script type="text/javascript">

      $(document).ready(function() {


          $(document).on('click', '.create-document-shipping_dist', function(evnt) {

           
               var panel = $("#document_line_form");
               var url = "{{ route('productionorders.serviceform', ['create']) }}";

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
                    $('#line_measure_unit_id').val( {{ AbiConfiguration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS') }} );
                    $('#line_quantity_decimal_places').val(0);

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
                    $.each(DOCUMENT_AVAILABLE_TAXES, function(key, data){
        				         cb += '<option value="'+key+'">'+data+'</option>';
        				    });
        				    $("#line_tax_id").append(cb);

        				    $('#line_tax_id').val({{ AbiConfiguration::get('DEF_TAX') }});

                    // set labels
                    @if( AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') )
                        $('#line_is_prices_entered_with_tax').val(1);
                        $(".label_tax_exc").hide();
                        $(".label_tax_inc").show();
                    @else
                        $('#line_is_prices_entered_with_tax').val(0);
                        $(".label_tax_inc").hide();
                        $(".label_tax_exc").show();
                    @endif


                    // calculate_service_price();
                    // calculate_line_product();
                    $("#line_final_price").html( '' );
                    $("#line_total_tax_exc").html( '' );
                    $("#line_total_tax_inc").html( '' );

                    $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
                    $('#line_commission_percent').val( 0.0 );   // Use default

                    $('#line_notes').val('');

                    $("#line_autoservice_name").val('');
                    $("#line_reference").val('');
                    $('#line_product_id').val('');
                    $('#line_combination_id').val('');


                    $('#modal_document_line').modal({show: true});
                    $("#line_autoservice_name").focus();

                });

              return false;
          });


//        $("body").on('click', "#modal_document_line_shippingSubmit", function() {
        $(document).on('click', '.create-document-shipping', function(evnt) {

            var token = "{{ csrf_token() }}";

            url = "{{ route('productionorders.storeline', [$document->id]) }}";

            var payload = { 
                              document_id : {{ $document->id }},
                              line_sort_order : $('#next_line_sort_order').val(),
                              line_type : 'service',
                              product_id : '',
                              combination_id : '',
                              reference : '',
                              name : '{{ AbiConfiguration::get('ABCC_SHIPPING_LABEL') }}',
                              quantity : '1',
                              quantity_decimal_places : '0',
                              is_shipping : '1',
                              use_shipping_method : '1',
                              measure_unit_id : '{{ AbiConfiguration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS') }}',
                              cost_price : '0.0',
                              unit_price : '0.0',
                              unit_customer_price : '0.0',
                              unit_customer_final_price : '0.0',
                              prices_entered_with_tax : 0,  // Shipping Cost Rules are tax excluded!!! '{{ AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') }}',
                              tax_id : '{{ AbiConfiguration::get('DEF_TAX') }}',
//                              tax_percent : $('#line_tax_percent').val(),
                              sales_equalization : '',
                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
                              discount_percent : '0.0',
                              discount_amount_tax_incl : '0.0',
                              discount_amount_tax_excl : '0.0',
                              sales_rep_id : $('#sales_rep_id').val(),
                              commission_percent : '0.0',
                              notes : ''
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    loadDocumentlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

//                    $('#modal_document_line').modal('toggle');

                    showAlertDivWithDelay("#msg-success");
                }
            });

        });


    });			// $(document).ready(function() {   ENDS


</script>