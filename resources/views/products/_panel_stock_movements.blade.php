

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

<div id="div_stockmovements">



   <div class="table-responsive">

@if ($mvts->count())
<table id="stockmovements" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Date', 'stockmovements')}}</th>
            <th>{{l('Type', 'stockmovements')}}</th>
            <th>{{l('Warehouse', 'stockmovements')}}</th>
            <th class="text-right">{{l('Quantity', 'stockmovements')}}
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('A positive value means stock increases.', 'stockmovements') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
            </th>
            <th class="text-right">{{l('Stock after', 'stockmovements')}}</th>
            <th class="text-right">{{l('Price', 'stockmovements')}}</th>
            <th class="text-right">{{l('Document', 'stockmovements')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($mvts as $stockmovement)
        <tr>
            <td>{{ $stockmovement->id }}</td>
            <td>{{ abi_date_short( $stockmovement->date ) }}</td>
            <td>[{{ $stockmovement->movement_type_id }}] - 
                 {{ \App\StockMovement::getTypeName($stockmovement->movement_type_id) }}
            </td>

            <td>{{ $stockmovement->warehouse->alias }}</td>

            <td class="text-right">{{ $stockmovement->as_quantityable( $stockmovement->quantity_after_movement - $stockmovement->quantity_before_movement ) }}</td>
            <td class="text-right">{{ $stockmovement->as_quantity( 'quantity_after_movement' ) }}</td>
            <td class="text-right">{{ $stockmovement->as_price( 'price' ) }}</td>
            <td class="text-right">

@if ( $route = $stockmovement->getStockmovementableDocumentRoute() )
{{-- optional(optional($stockmovement->stockmovementable)->document)->id --} }
        <!-- a href="{{ route($route.'.edit', ['0']).'?document_reference='.$stockmovement->document_reference }}" title="{{l('Open Document', [], 'layouts')}}" target="_new" -->  --}}
        <a href="{{ route($route.'.edit', [$stockmovement->stockmovementable->document->id]) }}" title="{{l('Open Document', [], 'layouts')}}" target="_new">{{ $stockmovement->document_reference }}</a>
@else
      {{ $stockmovement->document_reference }}
@endif

            </td>
            <td class="text-center">
                @if ($stockmovement->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $stockmovement->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            <td class="text-right">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

<div class="pagination_stockmovements">
{{ $mvts->appends( Request::all() )->render() }}
</div>
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $mvts->total() ], 'layouts')}} </span></li></ul>
<ul class="pagination" style="float:right;"><li xclass="active" style="float:right;"><span style="color:#333333;border-color:#ffffff"> <div class="input-group"><span class="input-group-addon" style="border: 0;background-color: #ffffff" title="{{l('Items per page', 'layouts')}}">{{l('Per page', 'layouts')}}</span><input id="items_per_page_stockmovements" name="items_per_page_stockmovements" class="form-control input-sm items_per_page_stockmovements" style="width: 50px !important;" type="text" value="{{ $items_per_page_stockmovements }}" onclick="this.select()">
    <span class="input-group-btn">
      <button class="btn btn-info btn-sm" type="button" title="{{l('Refresh', 'layouts')}}" onclick="getStockMovements(); return false;"><i class="fa fa-refresh"></i></button>
    </span>
  </div></span></li></ul>




@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_stockmovements" ENDS -->



{{-- *************************************** --}}

