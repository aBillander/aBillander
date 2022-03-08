<!-- Profitability -->

               <div class="panel-body">


<div id="panel_recent_sales" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('products._panel_recent_sales') --}}

</div>

               </div><!-- div class="panel-body" -->


<!-- Profitability ENDS -->



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });


   		
   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			// alert(page);
			if (page == 'sales') getRecentSales(page);
		});

		$(document).on('click','.pagination_recentsales a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			getRecentSales(page);
			// location.hash = page;
		});

		$(document).on('keydown','.items_per_page', function(e)
		{
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getRecentSales();
		   return false;
		  }

		});
		

		function getRecentSales( page = 1 )
		{
           var panel = $("#panel_recent_sales");
           var url = '{{ route( 'products.recentsales', [$product->id] ) }}?page=' + page;

           panel.addClass('loading');

			$.ajax({
				type: "GET",
				url: url,
				data: {
					items_per_page: $("#items_per_page").val()
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
		  if (target == '#tab2default_s')
		  {
		  		getRecentSales();
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