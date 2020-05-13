<div class="panel panel-primary" id="panel_product">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Search Product') }}</h3>
   </div>

   <div class="panel-body">

            {!! Form::hidden('customer_id', \App\Context::getContext()->customer->id, array('id' => 'customer_id')) !!}
            {!! Form::hidden('currency_id', \App\Context::getContext()->cart->currency_id, array('id' => 'currency_id')) !!}

            {{ Form::hidden('line_product_id',     null, array('id' => 'line_product_id'    )) }}
            {{ Form::hidden('line_combination_id', null, array('id' => 'line_combination_id')) }}

                  <div class="form-group col-lg-8 col-md-8 col-sm-8">
                     {{ l('Product Name') }}
                     <!-- input class="form-control ui-autocomplete-input" id="line_autoproduct_name" onclick="this.select()" name="line_autoproduct_name" autocomplete="off" value="pan in" type="text" -->

                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name', 'onclick' => 'this.select()')) !!}


                     {{ Form::hidden( 'line_measure_unit_id', null, ['id' => 'line_measure_unit_id'] ) }}
                  </div>

                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('line_quantity') ? 'has-error' : '' }}">
                    {{ l('Quantity') }}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{{ l('Change Quantity and press [Enter] or click button below.') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
                     {!! Form::text('line_quantity', null, array('class' => 'form-control', 'id' => 'line_quantity', 'xonkeyup' => 'calculate_line_product( )', 'xonchange' => 'calculate_line_product( )', 'onfocus' => 'this.select()', 'onclick' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('line_quantity', '<span class="help-block">:message</span>') !!}

                     {{ Form::hidden('line_quantity_decimal_places', null, array('id' => 'line_quantity_decimal_places')) }}
                 </div>


   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" id="product_add_to_cart" xonclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-plus"></i>
                     &nbsp; {{ l('Add to Cart') }}
                  </button>
               </div>
</div>




@section('scripts')    @parent

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

  $(document).ready(function() {


        $(document).on('click', "#product_add_to_cart", function() {

            var url = "{{ route('abcc.cart.add') }}";
            var token = "{{ csrf_token() }}";

            if ($('#line_product_id').val() == "") return false;

            var payload = { 
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),

                              quantity : $('#line_quantity').val(),
                              quantity_decimal_places : $('#line_quantity_decimal_places').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),

                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(reponse){

                    if(reponse.reload)
                    {
                      window.location.reload();
                      return false;
                    }

                    loadCartlines();

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});

                    // Initialize form!
                    resetSearchProduct();

                    showAlertDivWithDelay("#msg-success");
                }
            });

        });


        function auto_product_line( selector = "#line_autoproduct_name" ) {

            $( selector ).autocomplete({
                source : "{{ route('cart.searchproduct') }}?customer_id="+$('#customer_id').val()+"&currency_id="+$('#currency_id').val(),
                minLength : 1,
                appendTo : "#panel_product",

                select : function(key, value) {
                    var str = '[' + value.item.reference+'] ' + value.item.name;

                    $("#line_autoproduct_name").val(str);
                    $('#line_product_id').val(value.item.id);
                    $('#line_combination_id').val(0);

                    // getProductData( $('#line_product_id').val(), $('#line_combination_id').val() );

                    $('#line_quantity').focus();

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
                url: "{{ route('cart.getproduct') }}",
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

                    // calculate_line_product();

                    console.log(response);
                }
            });
        }


        $(document).on('click', '.update-line-quantity', function(evnt) {
            var id       = $(this).attr('data-id');
            var quantity = $(this).parent().prev( ".input-line-quantity" ).val();

            updateCartlineQuantity(id, quantity);
            return false;

        });


        $(document).on('click', '.update-line-measureunit', function(evnt) {
            var id          = $(this).attr('data-id');
            var quantity    = $("#"+id+"_quantity").val();
            var measureunit = $(this).attr('data-measureunit');

            updateCartlineMeasureUnit(id, quantity, measureunit);
            return false;

        });
        

        $(document).on('keydown','.input-line-quantity', function(evnt){
            var id       = $(this).attr('data-id');
            var quantity = $(this).val();
      
          if (evnt.keyCode == 13) {
             // console.log("put function call here");
             evnt.preventDefault();
             updateCartlineQuantity(id, quantity);
             return false;
          }

        });
        

        $(document).on('keydown','#line_quantity', function(evnt){

          if (evnt.keyCode == 13) {
             // console.log("put function call here");
             evnt.preventDefault();
             $("#product_add_to_cart").click();
             return false;
          }

        });

    // Initialization stuff
 
        loadCartlines();

        auto_product_line( "#line_autoproduct_name" );

        resetSearchProduct();


  }); // $(document).ready


/* ******************************************************************************************************************************************** */
  

  function updateCartlineQuantity(line_id=0, quantity=1) {
     
     if (line_id<=0) return ;

     // alert(line_id+' - '+quantity);

      var url = "{{ route('abcc.cart.updateline') }}";
      var token = "{{ csrf_token() }}";

      var payload = { 
                        line_id : line_id,
                        quantity : quantity
                    };

      $.ajax({
          url : url,
          headers : {'X-CSRF-TOKEN' : token},
          type : 'POST',
          dataType : 'json',
          data : payload,

          success: function(result){

              loadCartlines();

              $(function () {  $('[data-toggle="tooltip"]').tooltip()});

              showAlertDivWithDelay("#msg-success-update");

              console.log(result);
          }
      });

  }
  

  function updateCartlineMeasureUnit(line_id=0, quantity=0, measureunit=0) {
     
     if (line_id<=0) return ;

     // alert(line_id+' - '+quantity);

      var url = "{{ route('abcc.cart.updateline') }}";
      var token = "{{ csrf_token() }}";

      var payload = { 
                        line_id : line_id,
                        quantity : quantity,
                        measureunit : measureunit
                    };

      $.ajax({
          url : url,
          headers : {'X-CSRF-TOKEN' : token},
          type : 'POST',
          dataType : 'json',
          data : payload,

          success: function(result){

              loadCartlines();

              $(function () {  $('[data-toggle="tooltip"]').tooltip()});

              showAlertDivWithDelay("#msg-success-update");

              console.log(result);
          }
      });

  }

  
  function loadCartlines() {
     
     var panel = $("#panel_cart_lines");
     var url = "{{ route('abcc.cart.getlines') }}";

     panel.addClass('loading');

     $.get(url, {}, function(result){
           panel.html(result);
           panel.removeClass('loading');
           $("[data-toggle=popover]").popover();
//                 sortableOrderlines();
          
          $('#badge_cart_nbr_items').html( $('#cart_nbr_items').val() );
          $('#is_billable').val( $('#cart_is_billable').val() );

          // 
          if ( $('#cart_is_billable').val() != 0)
          {
                $("#can_min_order").addClass("alert-success").removeClass("alert-danger");

          } else {

               $("#can_min_order").addClass("alert-danger").removeClass("alert-success");

          }

          sortableCartLines();

     }, 'html');

  }
  

  function resetSearchProduct() {

        $("#line_autoproduct_name").val('');
        $('#line_quantity').val(1);
        $('#line_measure_unit_id').val(0);
        $('#line_quantity_decimal_places').val(0);

        $('#line_product_id').val('');
        $('#line_combination_id').val('');
        $("#line_autoproduct_name").focus();  // Focus won't work if console is open!!!

  }
  
</script>

@endsection


@section('styles')    @parent


{{-- Auto Complete --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }

</style>

@endsection
