
<div class="panel-body" id="div_customer_orders">
   <div class="table-responsive">


@if ($sheet->customerorders()->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th class="text-left">{{l('Reference')}}</th>
      <th>{{l('Customer')}}</th>
      <th>{{l('Order Date')}}</th>
      <th>{{l('Total')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->customerorders as $order)
    <tr>
      <td>{{ $order->id }}</td>
      <td>{{ $order->reference }}</td>
      <td>{!! $order->customerInfo() !!}
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customerCardFull() }}">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      </td>
      <td>{{ abi_date_form_full($order->created_at) }}</td>
      <td>{{ $order->total_tax_incl }}</td>
            <td class="text-center">
                @if ($order->customer_note)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customer_note }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

           <td class="text-right" style="width:1px; white-space: nowrap;">

                <a class="btn btn-sm btn-lightblue show-customer-order-products" title="{{l('Show', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning move-customer-order" href="{{ URL::to('customerorders/' . $order->id . '/move') }}" title="{{l('Move')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-external-link"></i></a>

                <a class="btn btn-sm btn-danger unlink-customer-order" href="{{ URL::to('customerorders/' . $order->id . '/unlink') }}" title="{{l('Unlink')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-unlink"></i></a>

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
  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button -->

  <a class="btn btn-sm btn-success show-order-products-summary" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Show Summary')}}</a></th>
</div>


@include('production_sheets._modal_customer_order_show')

@include('production_sheets._modal_customer_order_move')

@include('production_sheets._modal_customer_order_unlink')

@include('production_sheets._modal_customer_order_summary')


@section('scripts') @parent 

<!-- script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script -->

@endsection

