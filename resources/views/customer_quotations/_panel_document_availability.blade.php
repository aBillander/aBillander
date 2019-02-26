



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
                <td>{{$line->line_sort_order }}</td>
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



