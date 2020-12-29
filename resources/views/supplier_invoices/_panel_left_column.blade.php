

@if ( $document->status == 'closed' )

            <ul class="list-group">
              <li class="list-group-item" style="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h4>{{ l('Payment status') }}</h4>
              </li>

                  <li class="list-group-item text-center">
@if ( $document->payment_status == 'pending' )
                      <span class="label label-danger">
@endif
@if ( $document->payment_status == 'halfpaid' )
                      <span class="label label-warning">
@endif
@if ( $document->payment_status == 'paid' )
                      <span class="label label-success">
@endif
                      {{ $document->payment_status_name }}</span><br />
                      {{ l('Open Balance') }}:<br />
                      <p class="text-success">{{ $document->as_price('open_balance') }} / {{ $document->as_price('total_tax_incl') }} {{ $document->currency->iso_code }}</p>
                    
                  </li>

            </ul>

@endif

{{--
            <ul class="list-group">
              <li class="list-group-item" style="background-color: #fcf8e3;
border-color: #fbeed5;
color: #c09853;">
                <h4>{{ l('Stock Status') }}</h4>
              </li>

                  <li class="list-group-item">
                      {{ $document->stock_status}}
                    
                  </li>

            </ul>
--}}
{{--
@if ( ($document->created_via == 'aggregate_shipping_slips') && $document->leftShippingSlips()->count() )
--}}
          <div class="xpanel xpanel-default">
          <div class="xpanel-body">

            <!-- h4>{{ l('Supplier Risk') }}</h4>
            <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" style="width: 60%">60%</div>
            </div -->
            <ul class="list-group">
              <li class="list-group-item" style="color: #333333;background-color: #e7e7e7;border-color: #cccccc;">
                <h4><span class="btn btn-xs btn-grey" style="background-color: whitesmoke"><i class="fa fa-truck" style="color: #3a87ad"></i></span> {{ l('Shipping Slips') }}</h4>
              </li>
              @foreach( $document->leftShippingSlips() as $document_item )
                  <li class="list-group-item">
                      <a href="{{ URL::to('suppliershippingslips/' . $document_item->id . '/edit') }}" title="{{l('View Document', 'layouts')}}" target="_blank">

                          @if ($document_item->document_reference)
                            {{ $document_item->document_reference }}
                          @else
                            <span class="btn btn-xs btn-grey">{{ l('Draft') }}</span>
                          @endif

                      </a> - {{ abi_date_short( $document_item->document_date ) }}
                    
                  </li>
              @endforeach

                          @if ($document->status != 'closed')

                  <li class=" hide  list-group-item">
                          
                      {!! Form::open(array('route' => ['supplierinvoice.shippingslip.add', $document->id], 'title' => l('Add Supplier Shipping Slip to this Supplier Invoice'), 'class' => '', 'id' => 'add-invoiceable-action')) !!}

                      {!! Form::text('invoiceable', null, array('class' => 'form-control input-sm', 'style' => 'margin-top: 10px; margin-bottom: 10px;', 'id' => 'invoiceable')) !!}

    <div class="text-center">
                      {!! Form::submit(l('Add', 'layouts'), array('class' => 'btn btn-sm alert-success add-invoiceable-document')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="bottom" data-html="true" data-container="body" 
                                    data-content="{!! l('Use Supplier Shipping Slip <i>ID</i> or ID as seen in Shipping Slip url on your browser.') !!}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
    </div>
                      {!! Form::close() !!}

                  </li>

                          @endif


            </ul>

          </div>
          </div>

@if ( 0 && $document->leftShippingSlips()->count() == 0 )

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

