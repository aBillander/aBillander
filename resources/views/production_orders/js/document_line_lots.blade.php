 <script type="text/javascript">

    $(document).ready(function() {


          $(document).on('click', '.add-lots-to-line', function(evnt) {

              // What to do? Let's see:
              var line_type = $(this).attr('data-type');

              if ( line_type != 'product') {
                    return false;
              } 

              editDocumentProductLotsLine( $(this) );

              calculateSelectedAmount();

              // Seems to need a delay
              setTimeout(function(){ $("[data-toggle=popover]").popover(); }, 1000);              

          });
          

          function editDocumentProductLotsLine( selector ) {

            // Load form first
               var panel = $("#document_line_form");
               var url = "{{ route('productionorders.productlotsform', ['edit']) }}";

               panel.html('');
               panel.addClass('loading');

               $.get(url, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     // Populate form
                     getProductLotsLineData( selector );

                     $("[data-toggle=popover]").popover();

               }, 'html');


              $('#modal_document_line').modal({show: true});
              $("#line_quantity").focus();

              return false;

          };


          function getProductLotsLineData( selector ) {
                          
              var id = selector.attr('data-id');
              var quantity_label = selector.attr('data-quantity_label');
              var line_type = selector.attr('data-type');
              var url = "{{ route('productionorders.getlotsline', [$document->id, '']) }}/"+id;
              var label = '';

              PRICE_DECIMAL_PLACES = $('#currency_decimalPlaces').val();

              // if ( line_type == 'product' )
              
              $.get(url, function(result){
                    var label = '['+result.product.reference+'] '+result.product.name+' <span class="lead well well-sm alert-warning">'+quantity_label+'</span>';
                    var QUANTITY_DECIMAL_PLACES = result.product.quantity_decimal_places;

                    $('#modal_product_document_line_Label').html(label);

                    $('#product_lot_policy').html(result.product.lot_policy);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_combination_id').val(result.combination_id);
                    $('#line_type').val(result.type);

                    $('#line_lot_references').val(result.lot_references);
                    $('#product_available_lots').html(result.lots_view);


                    $('#line_name').val(result.name);
                    $('#line_lot_references').val(result.lot_references);
                    $('#line_reference').val(result.reference);

                    pmu_conversion_rate = 1.0;
                    pmu_quantity = result.quantity / pmu_conversion_rate;

                    $('#line_quantity_decimal_places').val( QUANTITY_DECIMAL_PLACES );
                    $('#line_quantity').val(pmu_quantity.round( QUANTITY_DECIMAL_PLACES ));
                    $('#line_package_measure_unit_id').val(result.measure_unit_id);
                    $('#line_measure_unit_id').val(result.measure_unit_id);
//                    $('#line_measure_unit_name').text(result.packagemeasureunit.name);
                    $('#line_package_label').text(result.package_label);

                    $('#line_pmu_label').val(result.pmu_label);
                    $('#line_extra_quantity_label').val(result.extra_quantity_label);

                    if ((result.pmu_label || '') != '')
                        $('#line_pmu_label').parent().removeClass('hidden');

                    if ((result.extra_quantity_label || '') != '')
                        $('#line_extra_quantity_label').parent().removeClass('hidden');

                    $('#line_notes').val(result.notes);

                    $("[data-toggle=popover]").popover();

                    console.log(result);
              });
          }



        $("body").on('click', "#modal_edit_document_line_product_lotsSubmit", function( event ) {

            var line_id = $('#line_id').val();
            var url = "{{ route('productionorderlines.lots.store', ['line_id']) }}".replace('line_id', line_id);
            var token = "{{ csrf_token() }}";
            var payload = $("#document_line_lots").serialize();

            $('#error-msg-box').hide();
/*
            var old_payload = { 
                              document_id : {{ $document->id }},
                              line_id : line_id,
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              name : $('#line_name').val(),

                              lot_references : $('#line_lot_references').val(),

                              quantity : $('#line_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_unit_price').val(),
                              unit_customer_price : $('#line_unit_customer_price').val(),
                              unit_customer_final_price : $('#line_price').val(),
                              prices_entered_with_tax : $('#line_is_prices_entered_with_tax').val(),
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
                              pmu_label : $('#line_pmu_label').val(),
                              extra_quantity_label : $('#line_extra_quantity_label').val(),
                              notes : $('#line_notes').val()
                          };
*/
            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){

                    if( response.success != 'OK' ) 
                    {
                        $('#error-msg-box').html(response.message);

                        $('#error-msg-box').show();

                        return ;
                    }

                    // loadDocumentlines();
                    
//                    $("[data-toggle=popover]").popover();

                    $('#modal_document_line').modal('toggle');

                    showAlertDivWithDelay("#msg-success");

                    console.log(response);
                }
            });


            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();

        });


    });



{{-- *************************************** --}}


        function calculateSelectedAmount() {
            var total = 0;
            $('.xcheckbox:checked').each(function(index,value){

                total += parseFloat($(this).closest('tr').find('.selectedamount').val().replace(',', '.'));

            });

            $('#balance').html(total);
        }

</script>
