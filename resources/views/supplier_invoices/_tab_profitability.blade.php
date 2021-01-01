<!-- Profitability -->

               <div class="panel-body">


<div id="panel_document_profitability" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('supplier_orders._panel_supplier_order_profitability') --}}

</div>

               </div><!-- div class="panel-body" -->


<!-- Profitability ENDS -->



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });

{{--
   		/*
   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			getSupplierOrders(page);
		});

		$(document).on('click','.pagination a', function(e){
			e.preventDefault();
			var stubs;
			var page;

			stubs = $(this).attr('href').split('page=');
			page = stubs[ stubs.length - 1 ];	// Like a BOSS!!!!

			getSupplierOrders(page);
			// location.hash = page;
		});

		$(document).on('keydown','.items_per_page', function(e)
		{
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getSupplierOrders();
		   return false;
		  }

		});
		*/
--}}
		function getDocumentProfit()
		{
           var panel = $("#panel_document_profitability");
           var url = "{{ route( $model_path.'.profit', [$document->id] ) }}";

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
			});
                 
		}


</script>
@endsection