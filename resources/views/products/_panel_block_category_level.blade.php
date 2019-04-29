
@if ($bom)

    @if ($bom->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
 
 @php

 $pad=36*($level);

 global $node_id;

 
 $parent = '';

 if ($parent_node_id>0)
 $parent = "treegrid-parent-" . $parent_node_id ;


 @endphp

            @foreach ($bom as $line)
            <tr id="node-{{$line->id}}" class="treegrid-{{ $node_id }} {{ $parent }}">
                <!-- td style="padding-left: {{$pad}}px;">{{ $parent_node_id }} - {{ $node_id }} - {{ $line->line_sort_order }}</td -->
@if ($parent_node_id>0)
                <td>
@php

$max=24;

$l = strlen($line->name);
if ($l>$max)
{
    $line_name = substr($line->name, 0, $max-2).'...';
} else {
    $line_name = $line->name;
}

@endphp
                    @if ( $line->id == $category_id )
                        <i class="fa fa-mail-forward"></i> 
                        <a class="" xstyle="display: inline-block;overflow: hidden;max-height: 16px !important; max-width: 182px !important;"  href="{{ route('products.index', ['search_status' => 0, 'category_id' => $line->id]) }}" title=""> <em>{{ $line_name }}</em> </a>
                    @else
                        <a class="" xstyle="display: inline-block;overflow: hidden;max-height: 16px !important; max-width: 182px !important;" href="{{ route('products.index', ['search_status' => 0, 'category_id' => $line->id]) }}" title="{{l('Go to', [], 'layouts')}}: {{ $line->name }}"> {{ $line_name }} </a>
                    @endif
                     <span class="badge" title="{{l('Products in this Category')}}">{{ $line->products()->count() }}</span>
                </td>
@else
                <td> {{ $line->name }} </td>
@endif
                <td> </td>
            </tr>
 @php

 $node_id++;

 @endphp


            @if ( $line->children )


                @include('products._panel_block_category_level', ['bom' => $line->children, 'level' => $level+1, 'node_id' => $node_id, 'parent_node_id' => $node_id - 1])

            @endif
            
            @endforeach

    @endif

@endif
