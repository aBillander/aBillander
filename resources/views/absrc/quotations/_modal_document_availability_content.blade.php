

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_document_line_Label">
              <span class="label xlabel-default alert-warning" title="{{ l('Draft', 'layouts') }}">

                @if ($document->document_id>0)
                    {{ $document->document_reference }}
                @else
                    {{ l('Draft', 'layouts') }}
                @endif

              </span> &nbsp;
              {{l('Stock Availability')}}</h4>
         </div>

         <div class="modal-body">


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
                        <th class="text-right button-pad">


                  <!-- a class="btn btn-sm btn-info xbtn-pressure xbtn-sensitive xlines_quick_form" title="{{l('Full quantity')}}" sxtyle="opacity: 0.65;" onclick="getDocumentAvailability(0)"><i class="fa fa-th"></i> </a>

                  <a class="btn btn-sm btn-info xbtn-grey xbtn-sensitive xcreate-document-service" title="{{l('Quantity on-hand only')}}" xstyle="background-color: #2bbbad;" onclick="getDocumentAvailability(1)"><i class="fa fa-th-large"></i> </a -->


                        </th>
                      </tr>
                    </thead>

        <tbody>

    @if ($document->lines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($document->lines->where('line_type', 'product') as $line)

@php

$showoff = '';

if ( $line->product->quantity_available < 0 ) $showoff = 'alert-warning';    // Too many orders. Maybe not stock for all

if ( $line->quantity > $line->product->quantity_onhand ) $showoff = 'alert-danger';

@endphp
            <tr class="{{ $showoff }}">
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
                <!-- td class="text-right active">

<input name="dispatch[{{ $line->id }}]" class="form-control input-sm" type="text" size="3" maxlength="5" style="min-width: 0;
    xwidth: auto;
    display: inline;" value="{{ $line->as_quantityable($line->quantity_onhand) }}" onclick="this.select()">
    
                </td -->

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


         </div><!-- div class="modal-body" ENDS -->

           <!-- div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_document_line_productSubmit" id="modal_document_line_productSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div -->
