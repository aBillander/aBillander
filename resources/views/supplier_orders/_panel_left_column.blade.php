
{{--
    @if ($document->quotation)

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Quotation', 'layouts') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      @if ( $document->close_date || 1)

                      <a href="{{ URL::to('supplierquotations/' . $document->quotation->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document->quotation->document_reference)
                            {{ $document->quotation->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document->quotation->document_date ) }}

                      @endif
                    
                  </li>

            </ul>

          </div>
          </div>

    @endif
--}}

@if ( $document->status == 'closed' )

    @if ($document->shipping_slip_at && $document->shippingslip)

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Shipping Slip', 'layouts') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      @if ( $document->close_date || 1)

                      <a href="{{ URL::to('suppliershippingslips/' . $document->shippingslip->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

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

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Backorder') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('supplierorders/' . optional($document->backorder)->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

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

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Aggregate Order') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('supplierorders/' . $document->aggregateorder->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

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

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Backorder by') }}</h4>
              </li>
              
                  <li class="list-group-item">

                      <a href="{{ URL::to('supplierorders/' . optional($document->backorderee)->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

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

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Aggregated by') }}</h4>
              </li>

@foreach ($document->leftAggregateOrders() as $order)
                  <li class="list-group-item">

                      <a href="{{ URL::to('supplierorders/' . $order->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

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

    @endif
    
@if ( 0 && $document->status != 'closed' )


          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Supplier Infos') }}</h4>
              </li>
{{--
              <li class="list-group-item">
                {{l('Supplier Group')}}:<br /> {{ $supplier->suppliergroup->name ?? '-' }}
              </li>
              <li class="list-group-item">
                {{l('Price List')}}:<br /> {{ $supplier->pricelist->name ?? '-' }}
              </li>
              <li class="list-group-item">
                {{l('Sales Representative')}}:<br />
                @if( $document->salesrep )
                <a href="{{ URL::to('salesreps/' . $document->salesrep->id . '/edit') }}" target="_new">{{ $document->salesrep->name }}</a>
                @else
                -
                @endif
              </li>
--}}
              <li class="list-group-item">
                {{l('Equalization Tax')}}:<br />
                @if( $document->supplier->sales_equalization > 0 )
                {{l('Yes', 'layouts')}}
                @else
                {{l('No', 'layouts')}}
                @endif
              </li>

              <!-- li class="list-group-item">
                <h4 class="list-group-item-heading">{{l('Supplier Group')}}</h4>
                <p class="list-group-item-text">{{ $supplier->suppliergroup->name ?? '' }}</p>
              </li>
              <li class="list-group-item">
                <h4 class="list-group-item-heading">{{l('Price List')}}</h4>
                <p class="list-group-item-text">{{ $supplier->pricelist->name ?? '' }}</p>
              </li -->
            </ul>

          </div>
          </div>
@endif


{{-- Attach Documnets --}}

          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <ul class="list-group">
              <li class="list-group-item" style="color: #333333;background-color: #e7e7e7;border-color: #cccccc;">
                <h4>{{ l('Attachments', 'layouts') }}</h4>
              </li>

              @foreach( $document->attachments as $attachment )
                  <li class="list-group-item">
@php
$label = $attachment->name ?: $attachment->filename;
$label_short = strlen($label) > 11 ? substr($label, 0, 11)."&hellip;" : $label;
@endphp
                      <a href="{{ route( $model_path.'.attachment.show', [$document->id, $attachment->id] ) }}" title="{{l('View Document', 'layouts')}}">

                          {{ $label_short }}

                      </a> 
                      <span class="pull-right">
                        <a class="btn btn-xs alert-danger delete-item" data-html="false" data-toggle="modal" 
                        data-id="{{$attachment->id}}" 
                        href="{{ route( $model_path.'.attachment.destroy', [$document->id, $attachment->id] ) }}" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$attachment->id.') '.$label }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                      </span>

                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-html="true" data-container="body" 
                                    data-content="{!! $label !!}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
                    
                  </li>
              @endforeach

                  <li class="list-group-item">
                          
                      {!! Form::open(array('route' => [$model_path.'.attachment.store', $document->id], 'title' => l('Upload an Attach Files', 'layouts'), 'class' => '', 'id' => 'add-attachment-action', 'files' => true)) !!}

                      <input type="hidden" value="{{ $document->getClassName() }}"     name="model_class"     id="model_class">
                      <input type="hidden" value="{{ $document->id }}"                 name="model_id"        id="model_id">
                      <input type="hidden" value="{{ $document->document_reference ?: $document->id }}" name="model_reference" id="model_reference">


            <div class="input-group {{ $errors->has('attachment_file') ? 'has-error' : '' }}" style="margin-top: 10px; margin-bottom: 10px;">
                <label class="input-group-btn">
                    <span class="btn btn-blue btn-sm">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="attachment_file" id="attachment_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control input-sm" readonly>
            </div>

            {{ l('Description') }}
            {!! Form::text('attachment_name', null, array('class' => 'form-control input-sm', 'style' => 'margin-top: 10px; margin-bottom: 10px;', 'id' => 'attachment_name')) !!}

    <div class="text-center">
                      {!! Form::submit(l('Upload File', 'layouts'), array('class' => 'btn btn-sm alert-success')) !!}
    </div>
                      {!! Form::close() !!}

                  </li>
            </ul>

          </div>
          </div>


@include('model_attachments/_form_attachments')

