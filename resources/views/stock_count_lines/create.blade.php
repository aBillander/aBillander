@extends('layouts.master')

@section('title') {{ l('Stock Count Lines - Create') }} :: @parent @stop


@section('content')

<div class="row" id="stockcountlineCreate">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title"><strong>{{ $list->name }}</strong> :: {{ l('New Stock Count Line') }}</h3></div>
			<div class="panel-body">
				{!! Form::open(array('route' => array('stockcounts.stockcountlines.store', $list->id))) !!}


			        <div class="row" id="product-search-autocomplete">

                          <div class="form-group col-lg-2 col-md-2 col-sm-2 {{-- $errors->has('row_reference') ? 'has-error' : '' --}}">
                             {{ l('Reference') }}
                             {!! Form::text('line_reference', null, ['class' => 'form-control', 'id' => 'line_reference',  'onfocus' => 'this.blur()' ]) !!}
                             {{-- !! $errors->first('row_reference', '<span class="help-block">:message</span>') !! --}}
                          </div>

		                  <div class="form-group col-lg-10 col-md-10 col-sm-10">
		                     {{ l('Product Name') }}
		                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name')) !!}
		                  </div>

			        </div>

					@include('stock_count_lines._form')

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

        	if ( !$("#price").val() )
        	{
        		$("#price").val('0.0');
        	}

        	$("#line_autoproduct_name").focus();

        });     // ENDS      $(document).ready(function() {



        $("#line_autoproduct_name").autocomplete({
            source : "{{ route('stockcountline.searchproduct', $list->id) }}",
            minLength : 1,
            appendTo : "#stockcountlineCreate",

            select : function(key, value) {
                var str = '[' + value.item.reference+'] ' + value.item.name;

                $('#line_reference').val(value.item.reference);
                $("#line_autoproduct_name").val(value.item.name);
                $('#product_id').val(value.item.id);
                $('#combination_id').val(0);
                $('#quantity').focus();
 //               $('#quantity').select();

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };

 //       alert('hhhhhhhh');

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
