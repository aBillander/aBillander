
<div id="panel_products">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Products') }}</h3>
               </div>
               <div class="panel-body">


<!-- ProductS -->

<div class="content_products"></div>

<!-- ProductS ENDS -->

               </div>
            </div>
               
</div>



@section('scripts')     @parent


<script type="text/javascript">
   
   $(document).ready(function() {


        // $("#line_autoproduct_name").focus();
    

   });

   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			if (page == 'inventory') getSupplierProducts(page);
			// getSupplierProducts(page);
		});

		$(document).on('click','.pagination_recentsales a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!


			// if (page == 'products') getSupplierProducts(page);
			getSupplierProducts(page);
			// location.hash = page;
		});

		function getSupplierProducts( page = 1 ){
           var panel = $('.content_products');
           var url;
           var product_id = '';

           if ( $("#line_autoproduct_name").val() )
           {
           		product_id = $("#line_product_id").val();
           }


           url = '{{ route( 'supplier.products', [$supplier->id] ) }}?page=' + page;

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
		   getSupplierProducts();

		   return false;
		  }

		});


		$(document).on('keydown','#line_autoproduct_name', function(e){
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getSupplierProducts();

		   return false;
		  }

		});

		$('#line_sales_model').change(function(){
		    getSupplierProducts();

		   return false;
		});


</script>
@endsection