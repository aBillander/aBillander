

    <div id="div_bom_product_boms">
       <div class="table-responsive">

    <table id="bom_lines" class="table table-hover">
        <thead>
            <tr>
            <th>{{ l('BOM Alias') }}</th>
            <th>{{ l('BOM Name') }}</th>
                <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody>

    @if ($product->productBOMlines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($product->productBOMlines as $line)
            <tr>
                <td>{{ $line->productBOM->alias }}</td>
                <td>{{ $line->productBOM->name }}</td>
                <td class="text-right">
                    <!-- a class="btn btn-sm btn-info" title="{{l('XXXXXS', [], 'layouts')}}" onClick="loadBOMlines();"><i class="fa fa-pencil"></i></a -->
                    
                    <a class="btn btn-sm btn-success" href="{{ route( 'productboms.edit', [$line->productBOM->id] ) }}" title="{{l('Show', [], 'layouts')}}" target="_blank"><i class="fa fa-eye"></i></a>

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
