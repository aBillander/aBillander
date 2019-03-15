<!-- Profitability -->

               <div class="panel-body">


<div id="panel_stock_movements" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('absrc.products._panel_stock_movements') --}}

</div>

               </div><!-- div class="panel-body" -->


<!-- Profitability ENDS -->



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });


   		
   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			if (page == 'inventory') getStockMovements(page);
		});

		$(document).on('click','.pagination_stockmovements a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			// if (page == 'stockmovements') getStockMovements(page);
			getStockMovements(page);
			// location.hash = page;
		});

		$(document).on('keydown','.items_per_page_stockmovements', function(e)
		{
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getStockMovements();
		   return false;
		  }

		});
		

		function getStockMovements( page = 1 )
		{
           var panel = $("#panel_stock_movements");
           var url = '{{ route( 'absrc.products.stockmovements', [$product->id] ) }}?page=' + page;

           panel.addClass('loading');

			$.ajax({
				type: "GET",
				url: url,
				data: {
					items_per_page_stockmovements: $("#items_per_page_stockmovements").val()
				}
			}).done(function(data){
				panel.html(data);
				panel.removeClass('loading');

                $("[data-toggle=popover]").popover();
			});
                 
		}

		// See: https://stackoverflow.com/questions/20705905/bootstrap-3-jquery-event-for-active-tab-change
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		  var target = $(e.target).attr("href") // activated tab
		  if (target == '#tab2default_m')
		  {
		  		getStockMovements();
		  }
		  /*
		  if ($(target).is(':empty')) {
		    $.ajax({
		      type: "GET",
		      url: "/article/",
		      error: function(data){
		        alert("There was a problem");
		      },
		      success: function(data){
		        $(target).html(data);
		      }
		  })
		 }
		 */
		});


</script>
@endsection