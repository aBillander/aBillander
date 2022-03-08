
<div id="panel_pricerules">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Price Rules') }}</h3>
               </div>
               <div class="panel-body">


<div id="msg-pricerule-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-pricerule-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<!-- ORDERS -->

<div class="content_pricerules"></div>

<!-- ORDERS ENDS -->

               </div>
            </div>
               
</div>

{{-- --}}
		@include('products/_modal_pricerule_create')
{{-- --}}

@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {


        $(document).on('click', '.create-pricerule', function(evnt) {

          	// Initialize
          	$('#autocustomer_name').val('');
          	$('#customer_id').val('');
          	$('#rule_price').val('');
          	$('#rule_currency_id').val("{{ \App\Configuration::getInt('DEF_CURRENCY')}}");
          	$('#from_quantity').val('1');
          	$('#date_from_form').val('');
          	$('#date_to_form').val('');

          	// Open popup
            $('#priceruleModal').modal({show: true});
            $("#autocustomer_name").focus();


            return false;
        });
      

   });

   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			if (page == 'pricerules') getProductPriceRules(page);
		});

		$(document).on('click','.pagination_pricerules a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			// if (page = 'pricerules') getCustomerPriceRules(page);
			getProductPriceRules(page);
			// location.hash = page;
		});

		function getProductPriceRules( page = 1 ){

			$.ajax({
				url: '{{ route( 'product.getpricerules', [$product->id] ) }}?page=' + page,
				data: {
					items_per_page_pricerules: $("#items_per_page_pricerules").val()
				}
			}).done(function(data){
				$('.content_pricerules').html(data);
			});
		}

		$(document).on('keydown','.items_per_page_pricerules', function(e){
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getProductPriceRules();
		   return false;
		  }

		});


</script>
@endsection