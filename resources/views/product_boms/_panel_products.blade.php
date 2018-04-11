

    <div id="div_bom_lines">
       <div class="table-responsive">

    <table id="bom_lines" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('SKU', [], 'layouts')}}</th>
                <th class="text-left">{{l('Product')}}</th>
                <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody>

    @if ($bom->products->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($bom->products as $line)
            <tr>
                <td>{{ $line->reference }}</td>
                <td>{{ $line->name }}</td>
                <td class="text-right">
                    <!-- a class="btn btn-sm btn-info" title="{{l('XXXXXS', [], 'layouts')}}" onClick="loadBOMlines();"><i class="fa fa-pencil"></i></a -->
                    
                    <a class="btn btn-sm btn-success" href="{{ route( 'products.edit', [$line->id] ) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>

                </td>
            </tr>
            
            @endforeach

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

       </div>
    </div>
