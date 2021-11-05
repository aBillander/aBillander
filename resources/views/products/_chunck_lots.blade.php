

<div id="div_lots">

@if ($lots->count())

   <div class="table-responsive">

<table id="lots" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{-- !! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !! --}} 
                    <a href="javascript:void(0);" data-toggle="popover" data-placement="right" 
                              data-content="{{ l('Select Lot and Amount.', 'lots') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                    </a>
            </th>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Lot Number', 'lots')}}</th>
            <th>{{l('Warehouse', 'lots')}}</th>
            <th class="text-right">{{l('Quantity', 'lots')}}</th>
            <th>{{l('Measure Unit', 'lots')}}</th>
            <th class="text-right">{{l('Allocated Quantity', 'lots')}}
                    <a href="javascript:void(0);" data-toggle="popover" data-placement="right" 
                              data-content="{{ l('Quantity includes this Document.', 'lots') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                    </a>
            </th>
            <th>{{l('Manufacture Date', 'lots')}}</th>
            <th>{{l('Expiry Date', 'lots')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-center" style="min-width: 64px"><div id="balance" class="alert-warning">{{ $lots->sum('allocated_to_line') }}</div></th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($lots as $lot)
        <tr>

@if ( $lot->not_allocable )
      <td class="text-center danger"></td>
@else
      <td class="text-center warning">{!! Form::checkbox('lot_group[]', $lot->id, $lot->allocated_to_line > 0, ['class' => 'case xcheckbox', 'onchange' => 'calculateSelectedAmount()']) !!}</td>
@endif

      <td>{{ $lot->id }}</td>
      <td>{{ $lot->reference }}</td>
      <td>{{ $lot->warehouse->alias_name ?? '-' }}</td>
      <td class="text-right">{{ $lot->measureunit->quantityable( $lot->quantity ) ?? $lot->as_quantity('quantity') }}</td>
      <td>{{ optional($lot->measureunit)->sign }}</td>
      <td class="text-right">{{ $lot->as_quantityable( $lot->allocatedQuantity(), $lot->measureunit->decimalPlaces ) }}</td>
      <td>{{ abi_date_short( $lot->manufactured_at ) }}</td>
      <td>{{ abi_date_short( $lot->expiry_at ) }}</td>
            <td class="text-center">
                @if ($lot->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $lot->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>


@if ( $lot->not_allocable )
            <td class="text-right danger">
            </td>
@else
            <td class="text-right">
              <input name="lot_amount[{{ $lot->id }}]" id="lot_amount[{{ $lot->id }}]" class=" selectedamount form-control input-sm" type="text" size="3" maxlength="7" style="min-width: 0; xwidth: auto; display: inline;" value="{{ $lot->as_quantityable($lot->allocated_to_line, $lot->measureunit->decimalPlaces) }}" onclick="this.select()" onkeyup="calculateSelectedAmount()">
            </td>
@endif

        </tr>
        @endforeach

    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->


{{--
<div class="pagination_lots">
{{ $lots->appends( Request::all() )->render() }}
</div>
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $lots->total() ], 'layouts')}} </span></li></ul>
<ul class="pagination" style="float:right;"><li xclass="active" style="float:right;"><span style="color:#333333;border-color:#ffffff"> <div class="input-group"><span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Per page', 'layouts')}}</span><input id="items_per_page_lots" name="items_per_page_lots" class="form-control input-sm items_per_page_lots" style="width: 50px !important;" type="text" value="{{ $items_per_page_lots }}" onclick="this.select()">
    <span class="input-group-btn">
      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getlots(); return false;"><i class="fa fa-refresh"></i></button>
    </span>
  </div></span></li></ul>
--}}



@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_lots" ENDS -->



{{-- *************************************** --}}

