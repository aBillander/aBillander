<div class="panel panel-primary" id="panel_confirm">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>

{!! Form::open(array('route' => 'abcc.orders.store', 'id' => 'create_customer_order', 'name' => 'create_customer_order', 'class' => 'form')) !!}

   <div class="panel-body">
      <div xclass="row">

{{--
         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', Auth::user()->getAllowedAddressList(), old('shipping_address_id', Auth::user()->address_id ?: Auth::user()->customer->shipping_address_id), array('class' => 'form-control', 'id' => 'shipping_address_id')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
         </div>
--}}
{{--
         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            <div class="form-group drop-down-list">
                <!-- label for="test3">Select a value</label -->
                <input id="shipping_address_alias" class="form-control" value="{{  old('shipping_address_alias', Auth::user()->address_id > 0 ? Auth::user()->address->alias : Auth::user()->customer->shipping_address()->alias)  }}">
                <span class="ddl-caret"></span>
                <ul class="dropdown-menu">

@foreach ( Auth::user()->getAllowedAddresses() as $address )

                    <li data-text="{{ $address->alias }}" data-id="{{ $address->id }}">
                     <a>
                        <b>{{ $address->alias }}</b>
                        <div> &nbsp; {{ $address->address1 }}</div>
                        <div> &nbsp; {{ $address->city }}, {{ $address->postcode }} {{ $address->state->name }}</div>
                     </a>
                  </li>
@endforeach                    
                </ul>

                <input type="hidden" id="shipping_address_id" name="shipping_address_id" value="{{  old('shipping_address_id', Auth::user()->address_id ?: Auth::user()->customer->shipping_address_id)  }}" class="form-control-id">
            </div>
         </div>
--}}

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('reference') ? 'has-error' : '' }}">
            {{ l('My Reference / Project') }}
            {!! Form::text('reference', old('reference'), array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>
         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            <div class="form-control">{{ $cart->shippingaddress->alias }}</div>
         </div>


         <!-- div class="form-group col-lg-6 col-md-2 col-sm-2 ">
            Forma de pago
                    <div  class="form-control">Domiciliado</div>
            
         </div -->
      </div>
      <div xclass="row">

         <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes') }}
            {!! Form::textarea('notes', old('notes'), array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
            
         </div>
      </div>
   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">

@if( Auth::user()->canQuotations() )
                  <button class="btn btn-warning pull-left confirm-" type="button" data-content="{{l('You are going to Ask for Quotation. Are you sure?')}}" data-title="{{ l('Quotation Confirmation') }}" data-toggle="modal" data-target="#modal-confirm-submit" onclick="$('#process_as').val('quotation');">
                     <i class="fa fa-handshake-o"></i>
                     &nbsp; {{ l('Place Quotation') }}
                  </button>
@endif

                  <input type="hidden" id="process_as" name="process_as" value="order" />
                  
                  <button class="btn btn-info confirm-" type="button" data-content="" data-title="{{ l('Order Confirmation') }}" data-toggle="modal" data-target="#modal-confirm-submit" xonclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-check-circle"></i>
                     &nbsp; {{ l('Place Order') }}
                  </button>

                  <input type="hidden" id="is_billable" name="is_billable" value="" />

               </div>

{!! Form::close() !!}

</div>

@if( 0 && Auth::user()->canMinOrderValue() > 0.0 )

      <div class="panel-body">

         <div class="alert alert-block" id="can_min_order">
             <i class="fa fa-warning"></i>
            {{ l('Cart amount should be more than: :amount (Products Value)', ['amount' =>  abi_money( Auth::user()->canMinOrderValue(), $cart->currency )]) }}
         </div>
         
      </div>

@endif
