
@if ( $document->status == 'closed' )

    @if ($document->shipping_slip_at && $document->shippingslip)

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Shipping Slip', 'layouts') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      @if ( $document->close_date || 1)

                      <a href="{{ URL::to('customershippingslips/' . $document->shippingslip->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document->shippingslip->document_reference)
                            {{ $document->shippingslip->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document->shippingslip->document_date ) }}

                      @endif
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif

    @if ($document->backordered_at && $document->backorder)

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Backorder') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('customerorders/' . optional($document->backorder)->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if (optional($document->backorder)->document_reference)
                            {{ optional($document->backorder)->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( optional($document->backorder)->document_date ) }}
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif

    @if ($document->aggregated_at && $document->aggregateorder)

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Aggregate Order') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('customerorders/' . $document->aggregateorder->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document->aggregateorder->document_reference)
                            {{ $document->aggregateorder->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document->aggregateorder->document_date ) }}
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif

@endif

    @if ($document->created_via == 'backorder')

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Backorder by') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('customerorders/' . optional($document->backorderee)->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if (optional($document->backorderee)->document_reference)
                            {{ optional($document->backorderee)->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( optional($document->backorderee)->document_date ) }}
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif

    @if ($document->created_via == 'aggregate_orders')

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Customer Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Aggregated by') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('customerorders/' . $document->aggregateorderby->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document->aggregateorderby->document_reference)
                            {{ $document->aggregateorderby->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document->aggregateorderby->document_date ) }}
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif
    
@if ( $document->status != 'closed' )


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