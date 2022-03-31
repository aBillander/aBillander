
<div id="panel_actions">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Commercial Actions') }}</h3>
               </div>
               <div class="panel-body">

<!-- ORDERS -->

<div class="action-content"></div>

<!-- ORDERS ENDS -->

               </div>
            </div>
               
</div>



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });

   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			if (page == 'actions') getCustomerActions(page);
		});

		$(document).on('click','.pagination_actions a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			// if (page = 'actions') getCustomerActions(page);
			getCustomerActions(page);
			// location.hash = page;
		});

		function getCustomerActions( page = 1 ){
			return true;
		}

		$(document).on('keydown','.items_per_page', function(e){
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getCustomerActions();
		   return false;
		  }

		});


</script>
@endsection