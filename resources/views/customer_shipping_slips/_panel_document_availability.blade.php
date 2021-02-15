



               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Stock Availability')}}
               </h4><br -->
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Stock Availability')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3><br>        
              </div>


    <div id="div_document_availability_details">
       <div class="table-responsive">

    <table id="document_lines_availability" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">{{l('Line #')}}</th>
                        <th class="text-center">{{l('Quantity')}}</th>
                        <th class="text-left">{{l('Reference')}}</th>
               			<th class="text-left">{{l('Description')}}</th>

                        <th class="text-right">{{l('On hand')}}</th>
                        <th class="text-right">{{l('On order')}}</th>
                        <th class="text-right">{{l('Allocated')}}</th>
                        <th class="text-right">{{l('Available')}}</th>
                        <th class="text-right">{{l('-')}}</th>
                      </tr>
                    </thead>

        <tbody>

    @if ($document->lines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

        @foreach ($document->lines->where('line_type', 'product') as $line)
            <tr>
                <td>{{$line->line_sort_order }} 
                  @if ( $line->product->isPack() )
                    <i class="fa fa-gift btn-xs alert-warning" title="{{l('This Product is of Type "Grouped"', 'products')}}"></i>
                  @endif
                </td>
                <td class="text-center">{{ $line->as_quantity('quantity') }}</td>
                <td>
                <a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                {{ $line->name }}</td>

                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_onhand    ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_onorder   ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_allocated ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_available ) }}</td>
                <td class="text-right">{{ '-' }}</td>

            </tr>

            @if ( $line->product->isPack() )

            @foreach( $line->product->packitems as $packitem )
            <tr class="warning">
                <td> </td>
                <td class="text-center" title="{{ $line->as_quantity('quantity') }}x{{ $line->as_quantityable( $packitem->quantity ) }}">{{ $line->as_quantityable( $line->quantity * $packitem->quantity ) }}</td>
                <td>
                <a href="{{ URL::to('products/' . $packitem->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $packitem->product->reference }}</a></td>
                <td>
                {{ $packitem->product->name }}</td>

                <td class="text-right">{{ $line->as_quantityable( $packitem->product->quantity_onhand    ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $packitem->product->quantity_onorder   ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $packitem->product->quantity_allocated ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $packitem->product->quantity_available ) }}</td>
                <td class="text-right active">
                </td>

            </tr>

            @endforeach

            @endif
            
        @endforeach

    @else
    <tr><td colspan="9">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td></tr>
    @endif

        </tbody>
    </table>

       </div>
    </div>


{{-- Stock Movements --}}


              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Stock Movements')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3><br>        
              </div>


    <div id="div_document_availability_stockmovements">
       <div class="table-responsive">
@if ($stockmovements->count())
<table id="stockmovements" class="table table-hover">
  <thead>
    <tr>
      <th class="text-left">{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Date')}}</th>{{l('')}}
      <th>{{l('Type')}}</th>
      <th>{{l('Warehouse')}}</th>
            <th>{{l('Reference')}}</th>
            <th>{{l('Product')}}</th>
            <th class="text-right">{{l('Quantity')}}
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('A positive value means stock increases.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
            </th>
            <th class="text-right">{{l('Stock after')}}</th>
      <th class="text-right">{{l('Price')}}</th>
      <th class="text-right">{{l('Document')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th> </th>
    </tr>
  </thead>
  <tbody>

  @foreach ($stockmovements as $stockmovement)
    <tr>
      <td>{{ $stockmovement->id }}</td>
      <td>{{ abi_date_short( $stockmovement->date ) }}</td>
      <td>[{{ $stockmovement->movement_type_id }}] - 
           {{ \App\StockMovement::getTypeName($stockmovement->movement_type_id) }}
      </td>

      <td>{{ $stockmovement->warehouse->alias }}</td>
      <td><a href="{{ URL::to('products/' . $stockmovement->product->id . '/edit') }}#inventory" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $stockmovement->reference }}</a>
{{--
                    @if ( $stockmovement->combination_id > 0 )
                        {{ $stockmovement->combination->reference }}
                    @else
                        {{ $stockmovement->product->reference }}
                    @endif
--}}
            </td>
      <td>{{ $stockmovement->name }}
{{--
                    <a href="{{ URL::to('products/' . $stockmovement->product->id . '/edit') }}#inventory" title="{{l('Edit', [], 'layouts')}}" target="_new">{{ $stockmovement->product->name }}</a>
                    @if ( $stockmovement->combination_id > 0 )
                        <br />{{ $stockmovement->combination->name() }}
                    @endif
--}}
            </td>
            <td class="text-right">{{ $stockmovement->as_quantityable( $stockmovement->quantity_after_movement - $stockmovement->quantity_before_movement ) }}</td>
            <td class="text-right">{{ $stockmovement->as_quantity( 'quantity_after_movement' ) }}</td>
      <td class="text-right">{{ $stockmovement->as_price( 'price' ) }}</td>
      <td class="text-right">

@if ( $route = $stockmovement->getStockmovementableDocumentRoute() )
{{-- optional(optional($stockmovement->stockmovementable)->document)->id --} }
        <!-- a href="{{ route($route.'.edit', ['0']).'?document_reference='.$stockmovement->document_reference }}" title="{{l('Open Document', [], 'layouts')}}" target="_new" -->  --}}
        <a href="{{ route($route.'.edit', [optional(optional($stockmovement->stockmovementable)->document)->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $stockmovement->document_reference }}</a>
    @if ( !optional(optional($stockmovement->stockmovementable)->document)->id ) 
        <i class="fa fa-exclamation-triangle btn-xs btn-danger" title="{{l('Document ID not found', 'layouts')}}"></i>
    @endif
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
                @if (  is_null($stockmovement->deleted_at))
                <!-- a class="btn btn-sm btn-success" href="{{ URL::to('stockmovements/' . $stockmovement->id) }}" title=" Ver "><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id . '/edit') }}" title=" Modificar "><i class="fa fa-pencil"></i></a -->
                <!-- a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('stockmovements/' . $stockmovement->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Stock Movements') }} ::  ({{$stockmovement->id}}) {{ $stockmovement->date }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->
                @else
                <a class="btn btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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
    </div>


