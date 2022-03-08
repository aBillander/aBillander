
<div class="with-nav-tabs panel panel-info" id="panel_products">     

               <div class="panel-heading">
                  <!-- h3 class="panel-title">{{ l('Products') }}</h3 -->

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default_p" data-toggle="tab">{{ l('Products') }}</a></li>
                            <li><a href="#tab2default_p" data-toggle="tab">{{ l('Re-Ordering') }}</a></li>
                            <!-- li><a href="#tab3default_s" data-toggle="tab">{{ l('Pending Movements') }}</a></li>

                            <li><a href="#tab4default_s" data-toggle="tab">{{ l('Availability') }}</a></li>
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#tab4default_s" data-toggle="tab">Default 4</a></li>
                                    <li><a href="#tab5default_s" data-toggle="tab">Default 5</a></li>
                                </ul>
                            </li -->
                        </ul>

               </div>

  <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default_p">
                
                @include('suppliers._tab_products_data')

      </div>
      <div class="tab-pane fade" id="tab2default_p">
                
                @include('suppliers._tab_reorder')

      </div>
      <!-- div class="tab-pane fade" id="tab3default_s">
                
                @ include('products._tab_pending_movements')

      </div>
      <div class="tab-pane fade" id="tab4default_s">
                
                @ include('products._tab_availability')

      </div>
      <div class="tab-pane fade" id="tab4default_s">
                Default 4
      </div>
      <div class="tab-pane fade" id="tab5default_s">
                Default 5
      </div -->
  </div>

{{--
      <div class="panel-body">
               
               
               <!-- ProductS -->
               
               <div class="content_products"></div>
               
               <!-- ProductS ENDS -->
               
      </div>
--}}

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