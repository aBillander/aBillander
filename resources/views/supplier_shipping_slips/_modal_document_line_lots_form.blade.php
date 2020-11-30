
@section('modals')    @parent

<div class="modal" id="modal_document_line_lots" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" xstyle="width: 99%; max-width: 1000px;">
      <div class="modal-content">


      <div class="modal-header btn-grey" xstyle="background-color: #fb3;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{{l('Add Lots to Line')}} :: &nbsp; <span id="line_lots_form_title"></span><br>
          {{l('Quantity')}} :: &nbsp; <span id="line_lots_form_quantity"></span>
        </h4>
      </div>

      <div class="modal-body" id="document_line_lots_form">
        <p>One fine body…</p>
      </div>

      <div class="modal-footer">        
            <button type="button" class="btn btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
      </div>

      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">


// $(document).ready(function() {

// });

{{--

function calculate_line_product() {

   var QUANTITY_DECIMAL_PLACES = parseInt($('#line_quantity_decimal_places').val());
   var PRICE_DECIMAL_PLACES = $('#currency_decimalPlaces').val();
   var tax_percent;

    // parseFloat

    var total;
    var total_tax_exc;
    var total_tax_inc;
//    var tax_percent = $('#line_tax_percent').val();

    if ($("#line_tax_id").val()>0) { 
      tax_percent = parseFloat( 
        get_tax_percent_by_id( $("#line_tax_id").val(), $("input[name='line_is_sales_equalization']:checked").val() ) 
      ); 
      $('#line_tax_percent').val( tax_percent );
    }
    else { return false; }

    if ( nbr_decimals( $("#line_quantity").val() ) > QUANTITY_DECIMAL_PLACES )
        $("#line_quantity").val( $("#line_quantity").val().round( QUANTITY_DECIMAL_PLACES ) );

    //

    final_price = $("#line_price").val() * ( 1.0 - $("#line_discount_percent").val() / 100.0 );
    total       = $("#line_quantity").val() * final_price;

    if ( $('#line_is_prices_entered_with_tax').val() > 0 ) {
        total_tax_inc = total;
        total_tax_exc = total / ( 1.0 + tax_percent / 100.0 );
    } else {
        total_tax_inc = total * ( 1.0 + tax_percent / 100.0 );
        total_tax_exc = total;
    }

    //

    $("#line_final_price").html( final_price.round( PRICE_DECIMAL_PLACES ) );
    $("#line_total_tax_exc").html( total_tax_exc.round( PRICE_DECIMAL_PLACES ) );
    $("#line_total_tax_inc").html( total_tax_inc.round( PRICE_DECIMAL_PLACES ) );
/*
    $("#line_final_price").html( final_price );
    $("#line_total_tax_exc").html( total_tax_exc );
    $("#line_total_tax_inc").html( total_tax_inc );
*/
}


function calculate_service_price()
{
   calculate_line_product();

   return ;
}



function get_tax_percent_by_id(tax_id, se = 0) 
{
   // http://stackoverflow.com/questions/18910939/how-to-get-json-key-and-value-in-javascript
   // var taxes = $.parseJSON( '{{ json_encode( $taxpercentList ) }}' );

/*   var taxes = { ! ! json_encode( $supplier->sales_equalization
                                  ? $taxeqpercentList 
                                  : $taxpercentList 
                              ) ! ! } ;
*/
//   var se;
   var taxes   = {!! json_encode( $document->taxingaddress->getTaxPercentList() ) !!} ;
   var retaxes = {!! json_encode( $document->taxingaddress->getTaxWithREPercentList() ) !!} ;

   // Skip sales equalization
   // se = 0;

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
--}}
</script>

@endsection
