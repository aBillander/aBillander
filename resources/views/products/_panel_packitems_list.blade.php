

<div id="div_product_packitems">



   <div class="table-responsive">

@if ($packitems->count())
<table id="product_packitems" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{{l('ID', [], 'layouts')}}</th>
              <th>{{l('Position')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Drag to Sort.', 'layouts') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
              <th>{{l('Product Name')}}</th>
              <th>{{l('Quantity')}}</th>
              <th>{{l('Measure Unit')}}</th>
              <th>{{l('Notes', 'layouts')}}</th>
            <th>
                <a href="javascript:void(0);" class="btn xbtn-sm btn-success create-packitem pull-right" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
            </th>
        </tr>
    </thead>
    <tbody id="pricerule_lines" class="sortable">
        @foreach ($packitems as $packitem)
        <tr data-id="{{ $packitem->id }}" data-sort-order="{{ $packitem->line_sort_order }}">
      <td>{{ $packitem->id }}</td>
      <td>{{ $packitem->line_sort_order }}</td>
      <td>
            [<a href="{{ URL::to('products/' . $packitem->product->id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $packitem->product->reference }}</a>] {{ $packitem->product->name }}
      </td>
      <td>{{ $packitem->measureunit->quantityable($packitem->quantity) }}</td>
      <td>{{ $packitem->measureunit->name }}</td>
      <td>@if ($packitem->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($packitem->notes) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
      </td>

            <td class="text-right button-pad">

                <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('pricerules/' . $packitem->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ route('products.packitems.destroy', [$product->id, $packitem->id]) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Products in this Pack') }} :: ({{$packitem->id}}) [{{$packitem->reference}}] {{$packitem->name}}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
            
      </td>
        </tr>
        @endforeach

    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->


@else
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn xbtn-sm btn-success create-packitem pull-right" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

            </div>

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}


</div>
@endif

    <input type="hidden" name="next_packitem_line_sort_order" id="next_packitem_line_sort_order" value="{{ ($packitems->max('line_sort_order') ?? 0) + 10 }}">

</div><!-- div id="div_packitems" ENDS -->


{{-- *************************************** --}}

