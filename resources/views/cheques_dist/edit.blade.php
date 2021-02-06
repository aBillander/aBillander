@extends('layouts.master')

@section('title') {{ l('Customer Cheques - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Customer Cheque') }} :: ({{$cheque->id}}) <span class="lead well well-sm alert-warning">{{$cheque->document_number}}</span></h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($cheque, array('method' => 'PATCH', 'route' => array('cheques.update', $cheque->id))) !!}

					@include('cheques._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-10 col-md-offset-1" style="margin-top: 20px">

					@include('cheque_details.embed_index')

	</div>
</div>

@endsection

@include('layouts/modal_delete')


@section('scripts')    @parent

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->

    <script type="text/javascript">

        $(document).ready(function() {

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

                  saveNewPositions();
              }
          });



        });     // ENDS      $(document).ready(function() {


        function saveNewPositions() {
            var positions = [];
            var token = "{{ csrf_token() }}";

            $('.updated').each(function() {
                positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: "{{ route('cheque.sortlines') }}",
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

{{--
        function loadBOMlines() {
           
           var panel = $("#panel_bom_lines");
           var url = "{{ route('productbom.getlines', $bom->id) }}";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');

                 $("[data-toggle=popover]").popover();
                 sortableBOMlines();
           }, 'html');

        }

        function sortableBOMlines() {

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

                  saveNewPositions();
              }
          });

        }
--}}
    </script>

@endsection

@section('styles')    @parent

  {!! HTML::style('assets/plugins/AutoComplete/styles.css') !!}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>

@endsection

