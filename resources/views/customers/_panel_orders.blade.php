
<div id="panel_orders">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Orders') }}</h3>
               </div>
               <div class="panel-body">

<!-- ORDERS -->

<div class="content"></div>

<!-- ORDERS ENDS -->

               </div>
               
</div>



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });

   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			getCustomerOrders(page);
		});

		$(document).on('click','.pagination a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			getCustomerOrders(page);
			// location.hash = page;
		});

		function getCustomerOrders( page = 1 ){
			$.ajax({
				url: '{{ route( 'customer.getorders', [$customer->id] ) }}?page=' + page,
				data: {
					items_per_page: $("#items_per_page").val()
				}
			}).done(function(data){
				$('.content').html(data);
			});
		}

		$(document).on('keydown','.items_per_page', function(e){
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getCustomerOrders();
		   return false;
		  }

		});


</script>
@endsection