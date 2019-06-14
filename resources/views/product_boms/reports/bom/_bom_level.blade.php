
@if ($bom)

    @if ($bom->BOMlines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
 
 @php

 $pad=27*($level);

 $bom_qt = $qt;
 $bom_ratio = $product->bomitem()->quantity / $product->bom->quantity;

 global $node_id;

 
 $parent = '';

 if ($parent_node_id>0)
 $parent = "treegrid-parent-" . $parent_node_id ;


 @endphp

            @foreach ($bom->BOMlines as $line)
 @php

 $chevron = $line->product->bom ? '<span class="glyphicon glyphicon-chevron-down"></span>' : '<span class="glyphicon glyphicon-minus"></span><span class="hide" style="width:16px; height: 16px; display: inline-block; position: relative;"></span>' ;

 @endphp
            <tr class="treegrid-{{ $node_id }} {{ $parent }}">
                <!-- td style="padding-left: {{$pad}}px;">{{ $parent_node_id }} - {{ $node_id }} - {{ $line->line_sort_order }}</td -->
                <td class="button-pad" style="padding-left: {{$pad}}px;">{!! $chevron !!} [<a class="" href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View', [], 'layouts')}}" target="_blank"> {{ $line->product->reference }}</a>]</td>
                <td>{{ $line->product->name }}</td>
                <td class="text-right">{{ niceQuantity($bom_qt * $line->quantity * $bom_ratio, 3) }}</td>
                <td>{{ optional($line->measureunit)->name }}</td>
                <td>{{ $line->as_percent('scrap') }}</td>
                <!-- td class="text-center">
                @if ($line->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $line->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td -->
            </tr>
 @php

 $node_id++;

 @endphp


            @if ( $line->product->bom )

{{--
    @php
             
      $bomItem_qt = $line->product->bomitem()->quantity;
      $productBOM_qt = $line->product->bom->quantity;
     
      $ratio = $bomItem_qt / $productBOM_qt;
     
      @endphp
--}}      


                @include('product_boms.reports.bom._bom_level', ['product' => $line->product, 'qt' => $bom_qt * $line->quantity * $bom_ratio, 'bom' => $line->product->bom, 'level' => $level+1, 'node_id' => $node_id, 'parent_node_id' => $node_id - 1])

            @endif
            
            @endforeach

    @endif

@endif
