
<div class="panel-body" id="div_customer_orders">
   <div class="table-responsive">


@if ($sheet->customerorders()->count())

{{-- !! Form::open( ['route' => ['fsxorders.export.orders'], 'method' => 'POST', 'id' => 'form-export'] ) !! --}}
{{-- !! csrf_field() !! --}}

<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Customer External Reference')}}</th>
      <th>{{l('Order Date')}}</th>
      <th>{{l('Customer')}}</th>
      <th class="text-left">{{ l('Deliver to') }}</th>
      <th class="text-left">{{l('Reference')}}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th>{{l('Shipping Method')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody id="order_lines">
  @foreach ($sheet->customerorders as $order)
    <tr>
      @if ( $order->export_date )
      <td> </td>
      @else
      <td class="text-center warning">{!! Form::checkbox('worders[]', $order->id, false, ['class' => 'case checkbox']) !!}</td>
      @endif
      <td><a href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('View Order')}}" target="_blank"> {{ $order->document_reference ?: $order->id}} </a></td>
      <td>{{ $order->customer->reference_external }}</td>
      <td title="{{ abi_date_form_full($order->created_at) }}">{{ abi_toLocale_date_short($order->created_at) }}</td>
      <td><a href="{{ URL::to('customers/' . $order->customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{!! $order->customerInfo() !!}</a>
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customerCardFull() }}">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      </td>
      <td>
          @if ( $order->hasShippingAddress() )



          {{ $order->shippingaddress->alias }} 
           <a href="javascript:void(0);">
              <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $order->shippingaddress->firstname }} {{ $order->shippingaddress->lastname }}<br />{{ $order->shippingaddress->address1 }}<br />{{ $order->shippingaddress->city }} - {{ $order->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $order->shippingaddress->phone }}</a>" data-original-title="" title="">
                  <i class="fa fa-address-card-o"></i>
              </button>
           </a>


          @endif
      </td>
      <td>{{ $order->reference }}</td>
      <td class="text-center">
                @if ($order->notes_from_customer && 0)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->notes_from_customer }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif

                @if ($order->all_notes && 0)
                 <a href="javascript:void(0);">
                    <button type="button" style="padding: 3px 8px;
font-size: 12px;
line-height: 1.5;
border: 1px solid #adadad;
border-radius: 3px;" xclass="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($order->all_notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
                {!! nl2br($order->all_notes) !!}
      </td>
      <td title="{{ $order->carrier->name ?? '' }}">{{ $order->shippingmethod->name ?? '' }}</td>

           <td class="text-right" style="width:1px; white-space: nowrap;">

                <a class="btn btn-sm btn-lightblue show-customer-order-products" title="{{l('Show', [], 'layouts')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning move-customer-order" href="{{ URL::to('customerorders/' . $order->id . '/move') }}" title="{{l('Move')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-external-link"></i></a>

                <a class="btn btn-sm btn-danger unlink-customer-order" href="{{ URL::to('customerorders/' . $order->id . '/unlink') }}" title="{{l('Unlink')}}" data-oid="{{ $order->id }}" data-oreference="{{ $order->reference }}" onClick="return false;"><i class="fa fa-unlink"></i></a>
{{--
                @if ($order->export_date)
                <a class="btn btn-sm btn-default" style="display:none;" href="javascript:void(0);" title="{{$order->export_date}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('fsxorders.export', [$order->id] ) }}" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @endif
--}}
            </td>
    </tr>
  @endforeach
    </tbody>
</table>


{{-- !! Form::close() !! --}}


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
{{--
        <a class="btn btn-sm btn-grey pull-left" style="margin-right: 21px" href="javascript:void(0);" title="{{l('Exportar Pedidos seleccionados')}}" onclick = "this.disabled=true;$('#form-export').attr('action', '{{ route( 'fsxorders.export.orders' )}}');$('#form-export').submit();return false;"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i>  {{l('Exportar a FactuSOL')}}</a>
--}}
         <div><span class="label label-success">{{ $sheet->customerorders()->count() }}</span> pedido(s) en total.
         <br />
         <span class="label label-danger"> {{ $sheet->customerorders()->where('export_date', null)->count() }}</span> pedido(s) pendientes descargar a FactuSOL.</div>
         </div>

         <div class="col-lg-6 col-md-6 col-sm-6 text-right">

                <!-- a class="btn btn-sm btn-grey" href="{ { URL::route('fsxorders.export', [$order->id] ) } }" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Exportar a FactuSOL')}}</a -->



        <!-- a class="btn btn-sm btn-grey" style="margin-right: 21px" href="javascript:void(0);" title="{{l('Exportar Pedidos seleccionados')}}" onclick = "this.disabled=true;$('#form-export').attr('action', '{ { route( 'fsxorders.export.orders' )} }');$('#form-export').submit();return false;"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i>  {{l('Exportar a FactuSOL')}}</a -->

  <a href="{{ route('productionsheet.pickinglist', [$sheet->id]) }}" class="btn btn-sm btn-info hidden" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Picking List')}}</a>

  <a href="{{ route('productionsheet.products', [$sheet->id]) }}" class="btn btn-sm btn-warning hidden" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Show Products')}}</a>

  <div class="btn-group">
    <a href="#" class="btn btn-sm xbtn-default btn-grey dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-file-pdf-o"></i> {{ l('Reports', 'layouts') }}</a>
    <a href="#" class="btn btn-sm xbtn-default btn-grey dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></a>
    <ul class="dropdown-menu" style="background-color: #ffffff;
border: 1px solid rgba(0,0,0,0.15);">
      <li><a href="{{ route('productionsheet.orders.pdf', [$sheet->id]) }}" target="_blank"><i class="fa fa-align-justify"></i> &nbsp;{{ l('Customer Orders') }}</a> </li>
      <li><a href="{{ route('productionsheet.orders.pdf', [$sheet->id, 'extended']) }}" target="_blank"><i class="fa fa-th-list text-info"></i> &nbsp;{{ l('Customer Orders') }} Ext.</a> </li>
      <li><a href="{{ route('productionsheet.products.pdf', [$sheet->id]) }}" target="_blank"><i class="fa fa-th-large"></i> &nbsp;{{ l('Products') }}</a> </li>
      <li><a href="{{ route('productionsheet.products.pdf', [$sheet->id, 'extended']) }}" target="_blank"><i class="fa fa-th text-info"></i> &nbsp;{{ l('Products') }} Ext.</a> </li>
      <li >
        
              <ul class="-dropdown-menu" style="margin-left:16px;">
                @foreach($work_centerList as $id => $name)
                <li><a href="{{ route('productionsheet.products.pdf', [$sheet->id, 'extended', 'work_center_id' => $id]) }}" target="_blank">{{ $name }}</a></li>
                @endforeach
                <li><a href="{{ route('productionsheet.products.pdf', [$sheet->id, 'extended', 'work_center_id' => 0]) }}" target="_blank">{{ 'Todos' }}</a></li>
              </ul>

      </li>
      <li class="divider"></li>
      <li><a href="{{ route('productionsheet.shippingslips.pdf', [$sheet->id]) }}" target="_blank"><i class="fa fa-truck"></i> &nbsp;{{ l('Customer Shipping Slips') }}</a> </li>
      <li class="divider"></li>
    </ul>
  </div>

  <a class="btn btn-sm btn-success show-order-products-summary" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('Show Order Summary')}}</a>


<div class="btn-group">
  <a href="#" title="{{l('Show', [], 'layouts')}}" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-table"></i> {{l('Show Summary Table')}}</a>
  <a href="#" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></a>
  <ul class="dropdown-menu">
    @foreach($work_centerList as $id => $name)
    <li><a href="{{ route('productionsheet.summary', [$sheet->id, 'work_center_id' => $id]) }}">{{ $name }}</a></li>
    @endforeach
    <!-- li class="divider"></li>
    <li><a href="{{ route('productionsheet.summary', [$sheet->id, 'work_center_id' => 0]) }}">{{ l('All', 'layouts') }}</a></li -->
  </ul>
</div>

         </div>

</div>
</div>


@include('production_sheets._modal_customer_order_show')

@include('production_sheets._modal_customer_order_move')

@include('production_sheets._modal_customer_order_unlink')

@include('production_sheets._modal_customer_order_summary')



{{-- *************************************** --}}


@section('scripts') @parent 

<script type="text/javascript">

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#order_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#order_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->


/*
$(document).ready(function() {

});
*/

</script>

@endsection

