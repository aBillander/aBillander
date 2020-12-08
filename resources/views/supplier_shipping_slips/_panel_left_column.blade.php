
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


{{--
  
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

@if ( $document->created_via == 'aggregate_orders' )

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

--}}