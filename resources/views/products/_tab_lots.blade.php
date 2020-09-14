<!-- Lots -->

               <div class="panel-body">


<div id="panel_lots" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('products._panel_lots') --}}

</div>

               </div><!-- div class="panel-body" -->


<!-- Profitability ENDS -->



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });


   		
//   		$(window).on('hashchange',function(){
//			page = window.location.hash.replace('#','');
//			if (page == 'inventory') getLots(page);
//		});

		$(document).on('click','.pagination_lots a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			// if (page == 'lots') getLots(page);
			getLots(page);
			// location.hash = page;
		});

		$(document).on('keydown','.items_per_page_lots', function(e)
		{
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getLots();
		   return false;
		  }

		});
		

		function getLots( page = 1 )
		{
           var panel = $("#panel_lots");
           var url = '{{ route( 'products.lots', [$product->id] ) }}?page=' + page;

           panel.addClass('loading');

			$.ajax({
				type: "GET",
				url: url,
				data: {
					items_per_page_lots: $("#items_per_page_lots").val()
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
		  if (target == '#tab4default_l')
		  {
		  		getLots();
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