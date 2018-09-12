
<div class="panel-body" id="div_customer_orders">
   <div class="table-responsive">


@if ($sheet->customerorders()->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Customer External Reference')}}</th>
      <th>{{l('Order Date')}}</th>
      <th>{{l('Customer')}}</th>
      <th class="text-left">{{l('Reference')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th>{{l('Shipping Method')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->customerorders as $order)
    <tr>
      <td><a href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('View Order')}}" target="_blank"> {{ $order->document_reference }} </a></td>
      <td>{{ $order->customer->reference_external }}</td>
      <td>{{ abi_date_form_full($order->created_at) }}</td>
      <td><a href="{{ URL::to('customers/' . $order->customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{!! $order->customerInfo() !!}</a>
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customerCardFull() }}">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      </td>
      <td>{{ $order->reference }}</td>
      <td class="text-center">
                @if ($order->customer_note)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customer_note }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
      </td>
      <td title="{{ $order->carrier->name ?? '' }}">{{ $order->shippingmethod->name ?? '' }}</td>

           <td class="text-right" style="width:1px; white-space: nowrap;">

                <a class="btn btn-sm btn-lightblue show-customer-order-products" title="{{l('Show', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning move-customer-order" href="{{ URL::to('customerorders/' . $order->id . '/move') }}" title="{{l('Move')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-external-link"></i></a>

                <a class="btn btn-sm btn-danger unlink-customer-order" href="{{ URL::to('customerorders/' . $order->id . '/unlink') }}" title="{{l('Unlink')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-unlink"></i></a>

                @if ($order->export_date)
                <a class="btn btn-sm btn-default" style="display:none;" href="javascript:void(0);" title="{{$order->export_date}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('fsxorders.export', [$order->id] ) }}" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
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


</div><!-- div class="panel-body" -->

<div class="panel-footer">
  <!-- a class="btn btn-link" data-dismiss="modal" href="{{ URL::to('workcenters') }}">{{l('Cancel', [], 'layouts')}}</a>
  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
     <i class="fa fa-floppy-o"></i>
     &nbsp; {{ l('Save', [], 'layouts') }}
  </button -->


<div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6">
         <span class="label label-success">{{ $sheet->customerorders()->count() }}</span> pedido(s) en total.
         <br />
         <span class="label label-danger"> {{ $sheet->customerorders()->where('export_date', null)->count() }}</span> pedido(s) pendientes descargar a FactuSOL.
         </div>

         <div class="col-lg-6 col-md-6 col-sm-6 text-right">

  <a href="{{ route('productionsheet.pickinglist', [$sheet->id]) }}" class="btn btn-sm btn-info hidden" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Picking List')}}</a>

  <a href="{{ route('productionsheet.products', [$sheet->id]) }}" class="btn btn-sm btn-warning hidden" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Show Products')}}</a>

  <a class="btn btn-sm btn-success show-order-products-summary" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Show Summary')}}</a>

         </div>

</div>
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

