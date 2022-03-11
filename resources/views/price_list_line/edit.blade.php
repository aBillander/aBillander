@extends('layouts.master')

@section('title') {{ l('Prices - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
		          <h3 class="panel-title">{{ l('Edit Price') }} :: ({{$price->product->id}}) {{$price->product->name}} [ {{ $price->product->as_price('price') }} {{ AbiContext::getContext()->currency->name }} ]</h3>
		          <h3 class="panel-title" style="margin-top:10px;">{{ l('Price List') }}: ({{$price->pricelist->id}}) {{ $price->pricelist->name }}
		          	<span class="label label-success">{{ $price->pricelist->getTypeVerbose() }}</span>
                    <!-- span class="label label-warning"> </span -->
                    <span class="label label-default">{{ $price->pricelist->currency->name }}</span></h3>
		    </div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($price, array('method' => 'PATCH', 'route' => array('pricelistlines.update', $price->id))) !!}

					@include('price_list_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')    @parent

{!! \App\Models\Calculator::marginJSCode( $price->pricelist->currency, true ) !!}

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
  discountcalc();
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
  discountcalc()
}

function new_margin_price()
{
  var cost_price = parseFloat( $("#cost_price").val() );
  var price_tax_inc = parseFloat( $("#price_tax_inc").val() );
  var tax = parseFloat(  get_tax_percent_by_id( $("#tax_id").val() ) );

  var price = price_tax_inc/(1.0+tax/100.0);
  var margin = margincalc( cost_price, price );

  $("#price").val( price );
  $("#margin").val( margin );
  discountcalc();
}

function apply_discount()
{
   var discount = parseFloat( $("#discount").val() );
   var base_price = parseFloat( $("#base_price").val() );

   if (isNaN( discount )) {discount =  0.0};
   $("#price").val( base_price*(1.0-discount/100.0) );

   new_margin();   
}

</script>

<script type="text/javascript">

$(document).ready(function() {
   new_margin();
});

</script>

@endsection