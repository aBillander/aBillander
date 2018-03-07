
<div class="panel-body" id="div_production_orders">
   <div class="table-responsive">


@if ($sheet->productionorders->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <!-- th>{{l('Product ID')}}</th -->
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
      <th>{{l('Work Center')}}</th>
      <th>{{l('Provenience')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->productionorders as $order)
    <tr>
      <td>{{ $order->id }}</td>
      <!-- td>{{ $order->product_id }}</td -->
      <td>{{ $order->product_reference }}</td>
      <td>{{ $order->product_name }}</td>
      <td>{{ $order->planned_quantity }}</td>
      <td>{{ $order->workcenter->name ?? '' }}</td>
      <td>{{ $order->created_via }}</td>
      <td class="text-center">
          @if ($order->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $order->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif</td>

           <td class="text-right" style="width:1px; white-space: nowrap;">

                <a class="btn btn-sm btn-blue show-production-order-products" title="{{l('Show', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning edit-production-order" href="{{ URL::to('productionorders/' . $order->id . '/productionsheetedit') }}" title="{{l('Edit', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->product_reference }}" data-oname="{{ $order->product_name }}" data-oquantity="{{ $order->planned_quantity }}" data-oworkcenter="{{ $order->work_center_id }}" data-onotes="{{ $order->notes }}" onClick="return false;"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-production-order" href="{{ URL::to('productionorders/' . $order->id . '/productionsheetdelete') }}" title="{{l('Delete', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-trash-o"></i></a>

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


</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">
  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a -->
  <!-- button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-plus"></i>
     &nbsp; {{ l('Add Production Order') }}
  </button -->

  <a class="btn btn-sm btn-info create-production-order" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add Production Order')}}</a></th>
</div>


@include('production_sheets._modal_production_order_show')

@include('production_sheets._modal_production_order_edit')

@include('production_sheets._modal_production_order_delete')


@section('scripts') @parent 

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->
    {{-- See: Laravel 5.4 ajax todo project: Autocomplete search #7 --}}

<script type="text/javascript">

$(document).ready(function() {

   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });


    $(document).on('click', '.create-production-order', function(evnt) {
        var url = "{ { route('productbom.storeline', [$bom->id]) } }";
        var next = $('#next_line_sort_order').val();
        var label = "{{ l('Add new Production Order') }}";

              $('#modalProductionOrderLabel').text(label);

              $('#product_id').val('');
              $('#planned_quantity').val(1);
              $('#work_center_id').val(0);
              $('#notes').val('');

          $("#order_autoproduct_name").val('');

          $("#msg-error").hide();
          $("#msg-error-text").text('');

        $('#modalProductionOrder').modal({show: true});
        return false;
    });

});   // $(document).ready ENDS

        $("#modalProductionOrderSubmit").click(function() {

//            var id = $('#line_id').val();
            var url = "{{ route('productionorder.storeorder') }}";
            var token = "{{ csrf_token() }}";

            var payload = { 
                              product_id : $('#product_id').val(),
                              planned_quantity : $('#planned_quantity').val(),
                              work_center_id : $('#work_center_id').val(),
                              due_date : $('#due_date').val(),
                              production_sheet_id : $('#production_sheet_id').val(),
                              notes : $('#notes').val()
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function($data){
                    if ($data.status == 'OK') location.reload();

                    $("#msg-error-text").text($data.message);
                    $("#msg-error").fadeIn();

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

 //                   $('#modalProductionOrder').modal('toggle');
 //                   $("#msg-success").fadeIn();
                }
            });
        });

        $("#order_autoproduct_name").autocomplete({
            source : "{{ route('productionorder.searchproduct') }}",
            minLength : 1,
            appendTo : "#modalProductionOrder",

            select : function(key, value) {
                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#order_autoproduct_name").val(str);
                $('#product_id').val(value.item.id);
//                $('#pid').val(value.item.id);
                $('#work_center_id').val(value.item.work_center_id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };

</script>

@endsection

@section('styles')    @parent

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

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
