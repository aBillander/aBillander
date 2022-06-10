
<div id="panel_products">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Products') }}</h3>
               </div>
               <div class="panel-body">


<div name="search_filter" id="search_filter">
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">
{{--
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
--}}
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('name', l('Product Name')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body" 
                                    data-content="{{ l('Search by Name or Reference.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-target="body" 
                                    data-content="{{ l('Reset search: empty field plus press [return].') }}">
                        <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                 </a>
    {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name', 'onclick' => 'this.select()')) !!}

    {{ Form::hidden('line_product_id',     null, array('id' => 'line_product_id'    )) }}
</div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('line_sales_model', l('Document')) !!}
        {!! Form::select('line_sales_model', ['' => l('-- All --', 'layouts')] + $modelList, $default_model, array('id' => 'line_sales_model', 'class' => 'form-control')) !!}
    </div>
{{--
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('stock', l('Stock')) !!}
    {!! Form::select('stock', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('category_id', l('Category')) !!}
    {!! Form::select('category_id', array('0' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('procurement_type', l('Procurement type'), ['class' => 'control-label']) !!}
    {!! Form::select('procurement_type', ['' => l('All', [], 'layouts')] + $product_procurementtypeList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="display: none">
    {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
    {!! Form::select('active', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
</div>
--}}

</div>

                
            </div>
        </div>
    </div>
</div>
</div>


<!-- ProductS -->

<div class="content_products"></div>

<!-- ProductS ENDS -->

               </div>
            </div>
               
</div>



@section('scripts')     @parent


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">
   
   $(document).ready(function() {


        $("#line_autoproduct_name").val('');
        $('#line_product_id').val('');
        // To get focus;
        $("#line_autoproduct_name").focus();

        $("#line_autoproduct_name").autocomplete({
            source : "{{ route('products.ajax.nameLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#line_autoproduct_name").val(str);
                $('#line_product_id').val(value.item.id);
//                $('#measure_unit_id').val(value.item.measure_unit_id);

                getCustomerProducts();

                // getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };
      

   });

   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			if (page == 'inventory') getCustomerProducts(page);
			// getCustomerProducts(page);
		});

		$(document).on('click','.pagination_recentsales a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!


			// if (page == 'products') getCustomerProducts(page);
			getCustomerProducts(page);
			// location.hash = page;
		});

		function getCustomerProducts( page = 1 ){
           var panel = $('.content_products');
           var url;
           var product_id = '';

           if ( $("#line_autoproduct_name").val() )
           {
           		product_id = $("#line_product_id").val();
           }


           url = '{{ route( 'customer.recentsales', [$customer->id] ) }}?product_id=' + product_id + '&page=' + page;

			$.ajax({
				type: "GET",
				url: url,
				data: {
					items_per_page_products: $("#items_per_page_products").val(),
					sales_model: $("#line_sales_model").val()
				}
			}).done(function(data){
				panel.html(data);
				panel.removeClass('loading');

                $("[data-toggle=popover]").popover();
			});
		}

		$(document).on('keydown','.items_per_page_products', function(e){
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getCustomerProducts();

		   return false;
		  }

		});


		$(document).on('keydown','#line_autoproduct_name', function(e){
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getCustomerProducts();

		   return false;
		  }

		});

		$('#line_sales_model').change(function(){
		    getCustomerProducts();

		   return false;
		});


</script>
@endsection