<div class="panel panel-primary" id="panel_confirm">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>

{!! Form::open(array('route' => 'abcc.orders.store', 'id' => 'create_customer_order', 'name' => 'create_customer_order', 'class' => 'form')) !!}

   <div class="panel-body">
      <div xclass="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', Auth::user()->customer->getAddressList(), old('shipping_address_id'), array('class' => 'form-control', 'id' => 'shipping_address_id')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
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
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-check-circle"></i>
                     &nbsp; {{ l('Place Order') }}
                  </button>
               </div>

{!! Form::close() !!}

</div>
