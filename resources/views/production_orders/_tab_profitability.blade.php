<!-- Profitability -->

               <div class="panel-body">


<div id="panel_document_profitability" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('customer_orders._panel_customer_order_profitability') --}}

</div>

               </div><!-- div class="panel-body" -->


	   <div class="panel-footer text-right">

	    	<a class="btn xbtn-sm btn-success" style="margin-right: 36px;" href="{{ URL::to('productionorders/' . $document->id . '/reload/commissions') }}" title="{{l('Update Commissions')}}"><i class="fa fa-refresh"></i> {{l('Update Commissions')}}</a>

@if ( \App\Configuration::isTrue('ENABLE_ECOTAXES') )

	    	<a class="btn xbtn-sm btn-success" style="margin-right: 36px;" href="{{ URL::to('productionorders/' . $document->id . '/reload/ecotaxes') }}" title="{{l('Update Line Ecotaxes')}}"><i class="fa fa-refresh"></i> {{l('Update Line Ecotaxes')}}</a>

@endif

	    	<a class="btn xbtn-sm btn-success" href="{{ URL::to('productionorders/' . $document->id . '/reload/costs') }}" title="{{l('Update Cost Prices')}}"><i class="fa fa-refresh"></i> {{l('Update Cost Prices')}}</a>

	   </div>


<!-- Profitability ENDS -->



@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {
      

   });

{{--
   		/*
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

		$(document).on('keydown','.items_per_page', function(e)
		{
  
		  if (e.keyCode == 13) {
		   // console.log("put function call here");
		   e.preventDefault();
		   getCustomerOrders();
		   return false;
		  }

		});
		*/
--}}
		function getDocumentProfit()
		{
           var panel = $("#panel_document_profitability");
           var url = "{{ route( 'productionorders.profit', [$document->id] ) }}";

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


</script>
@endsection