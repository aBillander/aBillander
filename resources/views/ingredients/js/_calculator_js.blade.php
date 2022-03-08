
{!! \App\Calculator::marginJSCode( AbiContext::getContext()->currency, true ) !!}

<script type="text/javascript">

function get_tax_percent_by_id(tax_id) 
{
   if (tax_id<=0) return 0.0;
   // http://stackoverflow.com/questions/18910939/how-to-get-json-key-and-value-in-javascript
   // var taxes = $.parseJSON( '{{ json_encode( $taxpercentList ) }}' );
   var taxes = {!! json_encode( $taxpercentList ) !!} ;

   if (typeof taxes[tax_id] == "undefined")   // or if (taxes[tax_id] === undefined) {
   {
        // variable is undefined
        alert('Tax code ['+tax_id+'] not found!');
   } else
        return taxes[tax_id];
}

/*
function new_price()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var margin = parseFloat( $("#margin").val() );

  if( isNaN( $("#margin").val() ) ) 
  { 
      new_margin(); 
      return;
  }

  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var price = pricecalc( cost_price, margin );
  var price_tax_inc = price*(1.0+tax/100.0);

  $("#price").val( price );
  $("#price_tax_inc").val( price_tax_inc );
}

function new_margin()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var price = parseFloat( $("#price").val() );
  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var margin = margincalc( cost_price, price );
  var price_tax_inc = price*(1.0+tax/100.0);

  $("#margin").val( margin );
  $("#price_tax_inc").val( price_tax_inc );
}
*/

// *****************************

function new_cost_price()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var price = parseFloat( $("#price").val() );
//  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var margin = margincalc( cost_price, price );
//  var price_tax_inc = price*(1.0+tax/100.0);

  $("#margin").val( margin );
//  $("#price_tax_inc").val( price_tax_inc );
}

function new_margin()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var margin = parseFloat( $("#margin").val() );

  if( isNaN( $("#margin").val() ) ) 
  { 
      new_cost_price(); 
      return ;
  }

  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var price = pricecalc( cost_price, margin );
  var price_tax_inc = price*(1.0+tax/100.0);

  $("#price").val( price );
  $("#price_tax_inc").val( price_tax_inc );
}

function new_price()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var price = parseFloat( $("#price").val() );
  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var margin = margincalc( cost_price, price );
  var price_tax_inc = price*(1.0+tax/100.0);

  $("#margin").val( margin );
  $("#price_tax_inc").val( price_tax_inc );
}

function new_tax()
{
  var tax_inc = {{ AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') }};
  var cost_price = parseFloat( $("#cost_price").val() );
  var price = parseFloat( $("#price").val() );
  var price_tax_inc = parseFloat( $("#price_tax_inc").val() );
  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );
  var margin;

  if ( tax_inc > 0 )
  {
      price = price_tax_inc/(1.0+tax/100.0);
      margin = margincalc( cost_price, price );

      $("#margin").val( margin );
      $("#price").val( price );
  } else {
      price_tax_inc = price*(1.0+tax/100.0);

      $("#price_tax_inc").val( price_tax_inc );
  }
}

function new_price_tax_inc()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var price_tax_inc = parseFloat( $("#price_tax_inc").val() );
  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var price = price_tax_inc/(1.0+tax/100.0);
  var margin = margincalc( cost_price, price );

  $("#price").val( price );
  $("#margin").val( margin );
}

</script>