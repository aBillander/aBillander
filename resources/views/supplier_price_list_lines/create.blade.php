@extends('layouts.master')

@section('title') {{ l('Price List Lines - Create') }} :: @parent @endsection


@section('content')

<div class="row" id="pricelistlineCreate">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $supplier->name_regular }}</strong> :: {{ l('New Price List Line') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('suppliers.supplierpricelistlines.store', $supplier->id))) !!}

                    {!! Form::hidden('supplier_id', $supplier->id, array('id' => 'supplier_id')) !!}

			        <div class="row" id="product-search-autocomplete">

		                  <div class="form-group col-lg-10 col-md-10 col-sm-10">
		                     {{ l('Product Name') }}
		                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name')) !!}
		                  </div>

			        </div>

					@include('supplier_price_list_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection




@section('scripts')    @parent

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->
    {{-- See: Laravel 5.4 ajax todo project: Autocomplete search #7 --}}

    <script type="text/javascript">

        $(document).ready(function() {

            $("#from_quantity").val({{old('from_quantity', '1')}});

            $("#price").val({{old('price', '0.0')}});

            $("#discount_percent").val({{old('discount_percent', '0.0')}});

            // $("#currency_id").val({{ old('currency_id', $currencyDefault) }});

        	$("#line_autoproduct_name").focus();

        });     // ENDS      $(document).ready(function() {



        $("#line_autoproduct_name").autocomplete({
            source : "{{ route('supplier.pricelistline.searchproduct', $supplier->id) }}",
            minLength : 1,
            appendTo : "#pricelistlineCreate",

            select : function(key, value) {
                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#line_autoproduct_name").val(str);
                $('#product_id').val(value.item.id);

                // getSupplierProductReference( {{ $supplier->id }}, value.item.id );

                $('#price').focus();
                $('#price').select();

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };


        getSupplierProductReference( supplier_id, product_id )
        {            
              var url = "{{ route('supplier.product.reference', [$supplier->id, '']) }}/"+product_id;
              
              $.get(url, function(result){
                    var reference = result.reference;

                    console.log(result);
              });
        }

    </script>

@endsection

@section('styles')    @parent

  {!! HTML::style('assets/plugins/AutoComplete/styles.css') !!}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>

@endsection
