
<div class="panel-body" id="div_orders">
   <div class="table-responsive">


@if ($sheet->customerorders()->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th class="text-left">{{l('Reference')}}</th>
      <th>{{l('Customer')}}</th>
      <th>{{l('Order Date')}}</th>
      <th>{{l('Total')}} ({{ \aBillander\WooConnect\WooConnector::getWooSetting( 'woocommerce_currency' ) }})</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sheet->customerorders as $order)
    <tr>
      <td>{{ $order->id }}</td>
      <td>{{ $order->reference }}</td>
      <td>{!! $order->customerCardMini() !!}
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->customerCard() }}">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      </td>
      <td>{{ $order->date_created }}</td>
      <td>{{ $order->total }}</td>
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

                <a class="btn btn-sm btn-lightblue" href="{{ URL::to('productionsheets/' . $sheet->id . '/show') }}" title="{{l('Show Products')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('sheets/' . $sheet->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Production Sheets') }} :: ({{$sheet->id}}) {{{ $sheet->name }}}" 
                    onClick="return false;" title="{{l('Unlink')}}"><i class="fa fa-unlink"></i></a>

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
</div>
