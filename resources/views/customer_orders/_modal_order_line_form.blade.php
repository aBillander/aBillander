
@section('modals')    @parent

<div class="modal" id="modal_order_line" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" style="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="order_line_form">




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

function calculate_line_product( prefix = '' ) {

   var QUANTITY_DECIMAL_PLACES = $('#product_line_quantity_decimal_places').val();
   var tax_percent;

    // parseFloat

    var total;
    var total_tax_exc;
    var total_tax_inc;
//    var tax_percent = $('#"+prefix+"line_tax_percent').val();

    if ($("#"+prefix+"line_tax_id").val()>0) { 
      tax_percent = parseFloat( 
        get_tax_percent_by_id( $("#"+prefix+"line_tax_id").val(), $("input[name='line_is_sales_equalization']:checked").val() ) 
      ); 
    }
    else { return false; }

    if ( nbr_decimals( $("#"+prefix+"line_quantity").val() ) > QUANTITY_DECIMAL_PLACES )
        $("#"+prefix+"line_quantity").val( $("#"+prefix+"line_quantity").val().round( QUANTITY_DECIMAL_PLACES ) );

    final_price = $("#"+prefix+"line_price").val() * ( 1.0 - $("#"+prefix+"line_discount_percent").val() / 100.0 );
    total       = $("#"+prefix+"line_quantity").val() * final_price;

    if ( PRICES_ENTERED_WITH_TAX ) {
        total_tax_inc = total;
        total_tax_exc = total / ( 1.0 + tax_percent / 100.0 );
    } else {
        total_tax_inc = total * ( 1.0 + tax_percent / 100.0 );
        total_tax_exc = total;
    }

    $("#"+prefix+"line_final_price").html( final_price.round( PRICE_DECIMAL_PLACES ) );
    $("#"+prefix+"line_total_tax_exc").html( total_tax_exc.round( PRICE_DECIMAL_PLACES ) );
    $("#"+prefix+"line_total_tax_inc").html( total_tax_inc.round( PRICE_DECIMAL_PLACES ) );
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
