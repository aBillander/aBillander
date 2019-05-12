


                  <h4>
                      <span style="color: #dd4814;">{{ l('Recent Sales', 'products') }}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h4>


<div id="div_recentsales">



   <div class="table-responsive">

<table id="recentsales" class="table table-hover">
    <thead>
        <tr>
            <th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th style="text-transform: none;">{{l('Date')}}</th>
            <th style="text-transform: none;">{{l('Document', 'products')}}</th>
            <th style="text-transform: none;">{{l('Customer')}}</th>
            <th style="text-transform: none;">{{l('Quantity')}}</th>

            <th style="text-transform: none;">{{l('Price', 'products')}}</th>
            <th style="text-transform: none;">{{l('Customer Price', 'products')}}</th>
            <th style="text-transform: none;">{{l('Customer Final Price', 'products')}}</th>
        </tr>
    </thead>
    <tbody id="order_lines">
@if ($lines->count())


    @foreach ($lines as $line)
        <tr>
            <td>{{ $line->id }}</td>
            <td>{{ abi_date_short( $line->document->document_date ) }}</td>
            <td>
                <a href="{{ route($line->route.'.edit', [$line->document->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">
                        @if ( $line->document->document_reference )
                            {{ $line->document->document_reference}}
                        @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft', 'layouts') }}</span>
                        @endif
                </a>
            </td>
            <td>
                <a href="{{ route('customers.edit', [$line->document->customer->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">
                        {{ $line->document->customer->name_commercial }}
                </a>
            </td>

            <td>{{ $line->as_quantity('quantity') }}</td>

            <td>{{ $line->as_price('unit_price') }}</td>
            <td>{{ $line->as_price('unit_customer_price') }}</td>
            <td>{{ $line->as_price('unit_customer_final_price') }}</td>
        </tr>
    @endforeach

@else
    <tr><td colspan="10">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td>
    <td></td></tr>
@endif

    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->



</div><!-- div id="div_stockmovements" ENDS -->



{{-- *************************************** --}}



