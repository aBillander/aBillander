

<div id="div_lots">

@if ($lots->count())

   <div class="table-responsive">

<table id="stockmovements" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Warehouse', 'lots')}}</th>
            <th>{{l('Lot Number', 'lots')}}</th>
            <th class="text-right">{{l('Quantity', 'lots')}}</th>
            <th>{{l('Measure Unit', 'lots')}}</th>
@if ( $product->procurement_type != 'purchase' )
            <th>{{l('Manufacture Date', 'lots')}}</th>
@endif
            <th>{{l('Expiry Date', 'lots')}}</th>
            <th class="text-center">{{ l('Blocked', [], 'layouts') }}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($lots as $lot)
        <tr>
      <td>{{ $lot->id }}</td>
      <td>{{ $lot->warehouse->alias_name ?? '-' }}</td>
      <td>
        <a href="{{ route( 'lot.stockmovements', $lot->id ) }}" title="{{ l('Go to', 'layouts') }}" target="_blank">{{ $lot->reference }}</a>
      </td>
      <td class="text-right">{{ $lot->as_quantity('quantity') }}</td>
      <td>{{ optional($lot->measureunit)->sign }}</td>
@if ( $product->procurement_type != 'purchase' )
      <td>{{ abi_date_short( $lot->manufactured_at ) }}</td>
@endif
      <td>{{ abi_date_short( $lot->expiry_at ) }}</td>

            <td class="text-center">@if ($lot->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>

            <td class="text-center">
                @if ($lot->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $lot->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

            <td class="text-right button-pad">
                @if (  is_null($lot->deleted_at))
                <a class="btn btn-sm btn-info" href="{{ route( 'stockmovements.index', ['search_status' => 1, 'lot_id' => $lot->id, 'lot_reference' => $lot->reference] ) }}" title="{{ l('Stock Movements') }}" target="_stockmovements"><i class="fa fa-outdent"></i></a>
{{--                       
                <a class="btn btn-sm btn-warning" href="{{ URL::to('lots/' . $lot->id . '/edit') }}"  title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                        href="{{ URL::to('lots/' . $lot->id ) }}" 
                        data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ l('Lots') }} ::  ({{$lot->id}}) {{ $lot->reference }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
--}}
                @else
                <a class="btn btn-warning" href="{{ URL::to('lots/' . $lot->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('lots/' . $lot->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

<div class="pagination_lots">
{{ $lots->appends( Request::all() )->render() }}
</div>
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $lots->total() ], 'layouts')}} </span></li></ul>
<ul class="pagination" style="float:right;"><li xclass="active" style="float:right;"><span style="color:#333333;border-color:#ffffff"> <div class="input-group"><span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Per page', 'layouts')}}</span><input id="items_per_page_lots" name="items_per_page_lots" class="form-control input-sm items_per_page_lots" style="width: 50px !important;" type="text" value="{{ $items_per_page_lots }}" onclick="this.select()">
    <span class="input-group-btn">
      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getlots(); return false;"><i class="fa fa-refresh"></i></button>
    </span>
  </div></span></li></ul>




@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_lots" ENDS -->



{{-- *************************************** --}}

