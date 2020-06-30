@extends('layouts.master')

@section('title') {{ l('Customer Cheque Details') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <!-- a href="{ { route('cheques.pdf', $cheque->id) } }" class="btn xbtn-sm btn-grey" style="margin-right: 32px" 
                title="{{l('Export', 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i> {{l('Export', 'layouts')}}</a -->

        <a href="{{ URL::to('cheques/'.$cheque->id.'/chequedetails/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ URL::to('cheques') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customer Order Templates') }}</a> 
    </div>
    <h2>
        <a href="{{ URL::to('cheques') }}">{{ l('Customer Order Templates') }}</a> <span style="color: #cccccc;">/</span> {{ $cheque->name }}
    </h2>        
</div>

<div id="div_cheques">
   <div class="table-responsive">

@if ($chequedetails->count())
<table id="cheques" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Position')}}</th>
            <th>{{l('Product')}}</th>
            <th>{{l('Quantity')}}</th>
            <th>{{l('Measure Unit')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody class="sortable">
	@foreach ($chequedetails as $chequedetail)
		<tr data-id="{{ $chequedetail->id }}" data-sort-order="{{ $chequedetail->line_sort_order }}">
            <td>{{ $chequedetail->id }}</td>
            <td>{{ $chequedetail->line_sort_order }}</td>
            <td>[<a href="{{ URL::to('products/' . $chequedetail->product_id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $chequedetail->product->reference }}</a>] {{ $chequedetail->product->name }}</td>
            <td>{{ $chequedetail->quantity }}</td>
            <td>{{ $chequedetail->product->measureunit->name }}</td>

            <td class="text-center">@if ($chequedetail->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

              <td class="text-center">
                  @if ($chequedetail->notes)
                   <a href="javascript:void(0);">
                      <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                              data-content="{{ $chequedetail->notes }}">
                          <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                      </button>
                   </a>
                  @endif
              </td>

			<td class="text-right">
                @if (  is_null($chequedetail->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('cheques/' . $cheque->id.'/chequedetails/' . $chequedetail->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('cheques/' . $cheque->id.'/chequedetails/' . $chequedetail->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Customer Cheque Details') }} :: ({{$chequedetail->id}}) {{{ $chequedetail->name }}} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('chequedetails/' . $chequedetail->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('chequedetails/' . $chequedetail->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

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

