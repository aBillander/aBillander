<!-- Reorder Form -->

               <div class="panel-body">


<div id="panel_reorder" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('suppliers._panel_reorder') --}}

</div>

               </div><!-- div class="panel-body" -->


<!-- Reorder Form ENDS -->



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });


   		
//   		$(window).on('hashchange',function(){
//			page = window.location.hash.replace('#','');
//			if (page == 'inventory') getReorderForm(page);
//		});

		$(document).on('click','.pagination_reorder a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			// if (page == 'reorder') getReorderForm(page);
			getReorderForm(page);
			// location.hash = page;
		});

		$(document).on('keydown','.items_per_page_reorder', function(e)
		{
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getReorderForm();
		   return false;
		  }

		});
		

		function getReorderForm( page = 1 )
		{
           var panel = $("#panel_reorder");
           var url = '{{ route( 'supplier.reorder.form', [$supplier->id] ) }}?page=' + page;

           panel.html(' &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}');

           panel.addClass('loading');

			$.ajax({
				type: "GET",
				url: url,
				data: {
					items_per_page_reorder: $("#items_per_page_reorder").val()
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
		  if (target == '#tab2default_p')
		  {
		  		getReorderForm();
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


		function getDocumentAvailability(onhand_only=0)
		{
		}
{{--
		function getDocumentAvailability(onhand_only=0)
		{
           var panel = $("#panel_document_availability");
           var url = "{{ route( $model_path.'.availability', [$document->id] ) }}";

           panel.addClass('loading');

			$.ajax({
				type: "GET",
				url: url,
				data: {
					onhand_only: onhand_only,
					items_per_page: $("#items_per_page").val()
				}
			}).done(function(data){
				panel.html(data);
				panel.removeClass('loading');

                $("[data-toggle=popover]").popover();
			});
                 
		}
--}}

</script>
@endsection