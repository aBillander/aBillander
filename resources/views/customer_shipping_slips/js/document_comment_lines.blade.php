    <script type="text/javascript">

//        var PRICE_DECIMAL_PLACES;

		var DOCUMENT_AVAILABLE_TAXES= {!! json_encode( $document->taxingaddress->getTaxList() ) !!} ;

        $(document).ready(function() {


          $(document).on('click', '.create-document-comment', function(evnt) {

           
               var panel = $("#document_line_form");
               var url = "{{ route($model_path.'.commentform', ['create']) }}";

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();
                     // sortableCustomerOrderLines();
               }, 'html').done( function() { 

                    var selector = "#line_autocomment_name";
                    var next = $('#next_line_sort_order').val();

                    $('#line_type').val('comment');

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
                    // By default, line_is_sales_equalization = 0 , because this is a comment. 

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


                    // calculate_comment_price();
                    // calculate_line_product();
                    $("#line_final_price").html( '' );
                    $("#line_total_tax_exc").html( '' );
                    $("#line_total_tax_inc").html( '' );

                    $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
                    $('#line_commission_percent').val( 0.0 );   // Use default

                    $('#line_notes').val('');

                    $("#line_autocomment_name").val('');
                    $("#line_reference").val('');
                    $('#line_product_id').val('');
                    $('#line_combination_id').val('');


                    $('#modal_document_line').modal({show: true});
                    $("#line_autocomment_name").focus();

                });

              return false;
          });


        $("body").on('click', "#modal_document_line_commentSubmit", function() {

            var id = $('#line_id').val();
            var url = "{{ route($model_path.'.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            if ( id == '' )
                url = "{{ route($model_path.'.storeline', [$document->id]) }}";
//            else
//                url = "{{ route($model_path.'.updateline', ['']) }}/"+id;

            var payload = { 
                              document_id : {{ $document->id }},
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              name : $('#line_autocomment_name').val(),
                              quantity : $('#line_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              is_shipping : $("input[name='line_is_shipping']:checked").val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_price').val(),
                              unit_customer_price : $('#line_price').val(),
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
                    loadDocumentlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_document_line').modal('toggle');

                    showAlertDivWithDelay("#msg-success");
                }
            });

        });


        $("body").on('click', "#modal_edit_document_line_commentSubmit", function() {

            var id = $('#line_id').val();
            var url = "{{ route($model_path.'.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

//            if ( id == '' )
//                url = "{{ route($model_path.'.storeline', [$document->id]) }}";
//            else
//                url = "{{ route($model_path.'.updateline', ['']) }}/"+id;

            var payload = { 
                              document_id : {{ $document->id }},
                              line_id : id,
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              reference : $('#line_reference').val(),
                              name : $('#line_autocomment_name').val(),
                              quantity : $('#line_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              is_shipping : $("input[name='line_is_shipping']:checked").val(),
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
                              notes : $('#line_notes').val()
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


        });			// $(document).ready(function() {   ENDS


    	function editDocumentCommentLine( selector ) {

            // Load form first
               var panel = $("#document_line_form");
               var url = "{{ route($model_path.'.commentform', ['edit']) }}";

               panel.html('');
               panel.addClass('loading');

               $.get(url, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     // Populate form
                     getCommentLineData( selector );

               }, 'html');


              $('#modal_document_line').modal({show: true});
              //  $("#line_autocomment_name").focus();
              $("#line_price").focus();

              return false;

          };


          function getCommentLineData( selector ) {

              var id = selector.attr('data-id');
              var line_type = selector.attr('data-type');
              var url = "{{ route($model_path.'.getline', [$document->id, '']) }}/"+id;
              var label = '';

              PRICE_DECIMAL_PLACES = $('#currency_decimalPlaces').val();

              // if ( line_type == 'comment' || 'shipping' )

              $.get(url, function(result){
                    // label = '['+result.product.reference+'] '+result.product.name;
                    var label;
                    var QUANTITY_DECIMAL_PLACES = 0;		// This is a Comment!

                    if ( result.product == null )
                    {
                          label = result.name;

                    } else {
                          label = '['+result.product.reference+'] '+result.product.name;  // +' ('+result.measureunit.name+')';

                    }
                    
                    $('#modal_comment_document_line_Label').text(label);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_combination_id').val(result.combination_id);
                    $('#line_type').val(result.line_type);

                    $('#line_name').val(result.name);
                	$("#line_autocomment_name").val(result.name);
                	// auto_product_line( "#line_autocomment_name" );
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

                    $('#line_is_prices_entered_with_tax').val(result.prices_entered_with_tax);

                    if ( $('#line_is_prices_entered_with_tax').val() > 0 )
                    {
                        //
                        price = result.unit_customer_final_price_tax_inc;

                        // set labels
                        $(".label_tax_exc").hide();
                        $(".label_tax_inc").show();

                    } else {

                        //
                        price = result.unit_customer_final_price;

                        // set labels
                        $(".label_tax_inc").hide();
                        $(".label_tax_exc").show();

                    }

                    $("#line_price").val( price );
                    
                    // $("#line_price").val( result.unit_customer_final_price.round( PRICE_DECIMAL_PLACES ) );
                    // $("#line_price").val( result.unit_customer_final_price );

                    $('#line_discount_percent').val(result.discount_percent);

                    $('#discount_amount_tax_incl').val(result.discount_amount_tax_incl);
                    $('#discount_amount_tax_excl').val(result.discount_amount_tax_excl);
/*
                // By default, line_is_sales_equalization = 0 , because this is a comment. 

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

          			    $('#line_tax_id').val(result.tax_id);


//                    $('#line_tax_label').html(result.tax_label);
                    $('#line_tax_percent').val(result.tax_percent);

                    // sales_equalization
                    if ($('#sales_equalization').val()>0) {
                        $('input:radio[name=line_is_sales_equalization]').val([result.sales_equalization]);
                        $('#line_sales_equalization').show();
                    }

                    calculate_comment_price( );

                    $('#line_sales_rep_id').val( result.sales_rep_id );
                    $('#line_commission_percent').val( result.commission_percent );
                    
                    $('#line_notes').val(result.notes);

                    console.log(result);
              });
          };

    </script>