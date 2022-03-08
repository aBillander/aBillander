
@if ( $document->status == 'closed' )

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Invoice', 'layouts') }}</h4>
              </li>
              
                  <li class="list-group-item">

@if ( $document->is_invoiceable )

                      @if ( $document->invoiced_at )

                      <a href="{{ URL::to('customerinvoices/' . $document->customerinvoice()->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document->customerinvoice()->document_reference)
                            {{ $document->customerinvoice()->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft', 'layouts') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document->customerinvoice()->document_date ) }}

                          @if ($document->customerinvoice()->status != 'closed')

                          <br /><br />

    <div class="text-center">
                  <a class="btn btn-sm alert-danger undo-invoice-document" data-href="{{ route('customershippingslip.invoice.undo', [$document->id]) }}" title="{{l('Undo Invoice')}}" 
                        data-content="{{l('You are going to remove :slip from Invoice :inv . Are you sure?', ['slip' => $document->document_reference, 'inv' => $document->customerinvoice()->document_reference])}}" 
                        data-title="{{ l('Document') }} :: {{ $document->document_reference }}" 
                        onClick="return false;"><i class="fa fa-undo"></i> {{l('Undo Invoice')}} </a>
    </div>


                          @endif

                      @else
                          <span class="btn btn-xs btn-grey">{{ l('Pending', 'layouts') }}</span>
                      @endif

@else
              <span class="label alert-warning">{{l('Not Invoiceable Document')}}</span>
@endif
                    
                  </li>

            </ul>

          </div>
          </div>

@endif