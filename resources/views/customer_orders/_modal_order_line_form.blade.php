
@section('modals')    @parent

<div class="modal" id="modal_order_line" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" style="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="order_line_form">




      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">


// $(document).ready(function() {

// });


function calculate_line_product( ) {

   var QUANTITY_DECIMAL_PLACES = $('#line_quantity_decimal_places').val();
   var tax_percent;

    // parseFloat

    var total;
    var total_tax_exc;
    var total_tax_inc;
//    var tax_percent = $('line_tax_percent').val();

    if ($("#line_tax_id").val()>0) { 
      tax_percent = parseFloat( 
        get_tax_percent_by_id( $("#line_tax_id").val(), $("input[name='line_is_sales_equalization']:checked").val() ) 
      ); 
    }
    else { return false; }

    if ( nbr_decimals( $("#line_quantity").val() ) > QUANTITY_DECIMAL_PLACES )
        $("#line_quantity").val( $("#line_quantity").val().round( QUANTITY_DECIMAL_PLACES ) );

    final_price = $("#line_price").val() * ( 1.0 - $("#line_discount_percent").val() / 100.0 );
    total       = $("#line_quantity").val() * final_price;

    if ( PRICES_ENTERED_WITH_TAX ) {
        total_tax_inc = total;
        total_tax_exc = total / ( 1.0 + tax_percent / 100.0 );
    } else {
        total_tax_inc = total * ( 1.0 + tax_percent / 100.0 );
        total_tax_exc = total;
    }

    $("#line_final_price").html( final_price.round( PRICE_DECIMAL_PLACES ) );
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

   // Skip sales equalization
   se = 0;

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


function nbr_decimals(number)
{
    // See: https://stackoverflow.com/questions/10454518/javascript-how-to-retrieve-the-number-of-decimals-of-a-string-number
    if($.type(number) !== "string") 
        value = number.toString();
    else 
        value = number;

    if ( value.indexOf('.') >= 0 ) return value.split('.')[1].length;

    return 0;
}

</script>

@endsection
