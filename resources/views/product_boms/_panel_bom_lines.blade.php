
    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Ingredients') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $bom->name }} -->
        </h3>        
    </div>

    <div id="div_bom_lines">
       <div class="table-responsive">

    <table id="bom_lines" class="table table-hover">
        <thead>
            <tr>
                <!-- th class="text-left">{{l('Position', [], 'layouts')}}</th -->
                <th class="text-left">{{l('Product')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Arrastre para reordenar.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
                <th class="text-left">{{l('Quantity')}}</th>
                <th class="text-left">{{l('Measure Unit')}}</th>
                <th class="text-left">{{l('Scrap (%)')}}</th>
                <th class="text-left">{{l('Notes', [], 'layouts')}}</th>
                <th class="text-right"> 
                  <a class="btn btn-sm btn-success create-bom-line" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a></th>
            </tr>
        </thead>
        <tbody class="sortable">

    @if ($bom->BOMlines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($bom->BOMlines as $line)
            <tr data-id="{{ $line->id }}" data-sort-order="{{ $line->line_sort_order }}">
                <!-- td>{{ $line->id }} - {{ $line->line_sort_order }}</td -->
                <td>{{ '['.$line->product->reference.'] '.$line->product->name }}</td>
                <td>{{ $line->as_quantity('quantity') }}</td>
                <td>{{ $line->measureunit->name }}</td>
                <td>{{ $line->as_percent('scrap') }}</td>
                <td class="text-center">
                @if ($line->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $line->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
                <td class="text-right">
                    <!-- a class="btn btn-sm btn-info" title="{{l('XXXXXS', [], 'layouts')}}" onClick="loadBOMlines();"><i class="fa fa-pencil"></i></a -->
                    
                    <a class="btn btn-sm btn-warning edit-bom-line" data-id="{{$line->id}}" title="{{l('Edit', [], 'layouts')}}" onClick="return false;"><i class="fa fa-pencil"></i></a>
                    
                    <a class="btn btn-sm btn-danger delete-bom-line" data-id="{{$line->id}}" title="{{l('Delete', [], 'layouts')}}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '['.$line->product->reference.'] '.$line->product->name }}" 
                        onClick="return false;"><i class="fa fa-trash-o"></i></a>

                </td>
            </tr>
            
            @endforeach

            @php
                $max_line_sort_order = $line->line_sort_order;
            @endphp

    @else
    <tr><td colspan="7">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td></tr>
    @endif

        </tbody>
    </table>

    <input type="hidden" name="next_line_sort_order" id="next_line_sort_order" value="{{ ($line->line_sort_order ?? 0) + 10 }}">

       </div>
    </div>
