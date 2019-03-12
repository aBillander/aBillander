

<!-- div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a href="{{ URL::to('customerorders/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a class="btn btn-sm btn-grey" xstyle="margin-right: 152px" href="{{ route('fsxconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}} {{l('Enlace FactuSOL', 'layouts')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Configuration', [], 'layouts')}}</a> 

    </div>
    <h2>
        {{ l('Customer Orders') }}
    </h2>        
</div -->

<div id="div_customer_orders">



   <div class="table-responsive">

@if ($customer_orders->count())
<table id="customer_orders" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('Order #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <!-- th class="text-left">{{ l('Production Date')}}</th -->
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <!-- th class="text-left">{{ l('Customer') }}</th -->
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($customer_orders as $order)
        <tr>
            <td>
                <a href="{{ URL::to('absrc/orders/' . $order->id . '/edit') }}" title="{{l('View Order')}}" target="_blank"> 
                        @if ( $order->document_reference )
                            {{ $order->document_reference}}
                        @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft', 'layouts') }}</span>
                        @endif
                </a>
            </td>
            <td>{{ abi_date_short($order->document_date) }}</td>
            <td>{{ abi_date_short($order->delivery_date_real) }}</td>

              <!-- td>
              
        @if ($order->production_sheet_id)
                        {{ abi_date_form_short($order->productionsheet->due_date) }} 
                        <a class="btn btn-xs btn-warning" href="{{ URL::to('productionsheets/' . $order->production_sheet_id) }}" title="{{l('Go to Production Sheet')}}"><i class="fa fa-external-link"></i></a>
        @endif
              </td -->
            
            <td>{{ $order->paymentmethod->name ?? '' }}
            </td>
            <td>{{ $order->created_via }}
            </td>
            <td class="text-right">{{ $order->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerorders/' . $order->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>  

                @if ($order->export_date)
                <a class="btn btn-sm btn-default" href="javascript:void(0);" title="{{$order->export_date}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('fsxorders.export', [$order->id] ) }}" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @endif

                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id . '/duplicate') }}" title="{{l('Copy Order')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if( $order->editable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customerorders/' . $order->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Orders') }} :: ({{$order->id}}) {{ $order->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif             
                -->
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

<span class="pagination_orders">
{{ $customer_orders->appends( Request::all() )->render() }}
</span>
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customer_orders->total() ], 'layouts')}} </span></li></ul>
<ul class="pagination" style="float:right;"><li xclass="active" style="float:right;"><span style="color:#333333;border-color:#ffffff"> <div class="input-group"><span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Per page', 'layouts')}}</span><input id="items_per_page" name="items_per_page" class="form-control input-sm items_per_page" style="width: 50px !important;" type="text" value="{{ $items_per_page }}" onclick="this.select()">
    <span class="input-group-btn">
      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getCustomerOrders(); return false;"><i class="fa fa-refresh"></i></button>
    </span>
  </div></span></li></ul>




@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_customer_orders" ENDS -->



{{-- *************************************** --}}

