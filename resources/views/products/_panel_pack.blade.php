
<div id="panel_pack">     

         

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Products in this Pack') }}</h3>
               </div>
               <div class="panel-body">


<div id="msg-pack-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-pack-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<!-- ORDERS -->

<div class="content_packitems"></div>

<!-- ORDERS ENDS -->

               </div>
            </div>
               
</div>

{{-- --}}
		@include('products/_modal_packitem_create')
{{-- --}}

@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {


        $(document).on('click', '.create-packitem', function(evnt) {

          	// Initialize
          	$('#packitem_line_sort_order').val( $('#next_packitem_line_sort_order').val() );
	        $("#autopackitem_name").val('');
	        $("#packitem_product_id").val('');
	        $("#autopackitem_name").focus();
          	$('#packitem_measureunit_name').html('');
	        $("#packitem_quantity").val('1');
	        $("#packitem_measureunit_id").val('');
	        $("#packitem_notes").val('');

          	// Open popup
            $('#packitemModal').modal({show: true});
            $("#autocustomer_name").focus();


            return false;
        });
      

   });

   		$(window).on('hashchange',function(){
//			page = window.location.hash.replace('#','');
			if (page == 'pack') getProductPackItems();
		});

		function getProductPackItems(){

           $('.content_packitems').addClass('loading');

			$.ajax({
				url: '{{ route( 'product.getpackitems', [$product->id] ) }}',
				data: {}
			}).done(function(data){
				$('.content_packitems').html(data);

                 $('.content_packitems').removeClass('loading');
                 $("[data-toggle=popover]").popover();

                 sortablePacklines();
			});
		}


        function sortablePacklines() {

          // Sortable :: http://codingpassiveincome.com/jquery-ui-sortable-tutorial-save-positions-with-ajax-php-mysql
          // See: https://stackoverflow.com/questions/24858549/jquery-sortable-not-functioning-when-ajax-loaded
          $('.sortable').sortable({
              cursor: "move",
              update:function( event, ui )
              {
                  $(this).children().each(function(index) {
                      if ( $(this).attr('data-sort-order') != ((index+1)*10) ) {
                          $(this).attr('data-sort-order', (index+1)*10).addClass('updated');
                      }
                  });

                  saveNewPackPositions();
              }
          });

        }

        function saveNewPackPositions() {
            var positions = [];
            var token = "{{ csrf_token() }}";

            $('.updated').each(function() {
                positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: "{{ route('products.packitems.sortlines') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'POST',
                dataType: 'json',
                data: {
                    positions: positions
                },
                success: function (response) {
                    console.log(response);
                }
            });
        }

</script>
@endsection