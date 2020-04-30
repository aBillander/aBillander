
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

                      @if ( $document->invoiced_at )

                      <a href="{{ URL::to('customerinvoices/' . $document->customerinvoice()->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document->customerinvoice()->document_reference)
                            {{ $document->customerinvoice()->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft', 'layouts') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document->customerinvoice()->document_date ) }}

                      @else
                          <span class="btn btn-xs btn-grey">{{ l('Pending', 'layouts') }}</span>
                      @endif
                    
                  </li>

            </ul>

          </div>
          </div>

@endif

@if ( $document->created_via == 'aggregate_orders' || $document->created_via == 'production_sheet' )

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Order', 'layouts') }}</h4>
              </li>

@foreach ($document->leftOrders() as $order)
                  <li class="list-group-item">
                      <a href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($order->document_reference)
                            {{ $order->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $order->document_date ) }}
                    
                  </li>
@endforeach

            </ul>

          </div>
          </div>

@else

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Customer Infos') }}</h4>
              </li>
              <li class="list-group-item">
                {{l('Customer Group')}}:<br /> {{ $customer->customergroup->name ?? '-' }}
              </li>
              <li class="list-group-item">
                {{l('Price List')}}:<br /> {{ $customer->pricelist->name ?? '-' }}
              </li>
              <li class="list-group-item">
                {{l('Sales Representative')}}:<br />
                @if( $document->salesrep )
                <a href="{{ URL::to('salesreps/' . $document->salesrep->id . '/edit') }}" target="_new">{{ $document->salesrep->name }}</a>
                @else
                -
                @endif
              </li>
              <li class="list-group-item">
                {{l('Equalization Tax')}}:<br />
                @if( $document->customer->sales_equalization > 0 )
                {{l('Yes', 'layouts')}}
                @else
                {{l('No', 'layouts')}}
                @endif
              </li>

              <!-- li class="list-group-item">
                <h4 class="list-group-item-heading">{{l('Customer Group')}}</h4>
                <p class="list-group-item-text">{{ $customer->customergroup->name ?? '' }}</p>
              </li>
              <li class="list-group-item">
                <h4 class="list-group-item-heading">{{l('Price List')}}</h4>
                <p class="list-group-item-text">{{ $customer->pricelist->name ?? '' }}</p>
              </li -->
            </ul>

          </div>
          </div>
@endif

    @if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') && ($document->production_sheet_id > 0) )

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="font-weight: bold;
color: #ffffff;
background-color: #325d88; border-color: #772953;">
                <h4>{{ l('Production Sheet', 'productionsheets') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('productionsheets/' . $document->production_sheet_id) }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          
                            <span class="btn btn-xs btn-grey">#{{ $document->production_sheet_id }} ({{ abi_date_form_short( \App\ProductionSheet::find($document->production_sheet_id)->due_date ) }})</span> 

                      </a> 

                        <a class="btn btn-xs btn-warning" href="{{ URL::to('productionsheets/' . $document->production_sheet_id) }}" title="{{l('View Document', 'layouts')}}" target="_blank"><i class="fa fa-external-link"></i></a>
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif
