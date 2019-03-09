<div class="panel panel-success" id="panel_order">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>

   <div class="panel-body">
      <div xclass="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6">
            <label class="control-label">{{ l('Date') }}</label>
            <div class="form-control">
                {{ abi_date_short($order->document_date) }} 
            </div>
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6">
            <label class="control-label">{{ l('Shipping Address') }}</label>
            <div class="form-control">

                {{ $order->shippingaddress->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" xdata-trigger="hover" data-container="body" data-placement="top" data-content="{{ $order->shippingaddress->firstname }} {{ $order->shippingaddress->lastname }}<br />{{ $order->shippingaddress->address1 }}<br />{{ $order->shippingaddress->city }} - {{ $order->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $order->shippingaddress->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
            </div>
         </div>

         <!-- div class="form-group col-lg-6 col-md-2 col-sm-2 ">
            Forma de pago
                    <div  class="form-control">Domiciliado</div>
            
         </div -->
      </div>
      <div xclass="row">

         <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label class="control-label">{{ l('Notes') }}</label>
            <div style="height: auto;" class="form-control">{!! nl2p($order->notes_from_customer) !!}</div>
            
         </div>
      </div>
   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <!-- button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-check-circle"></i>
                     &nbsp; {{ l('Place Order') }}
                  </button -->
               </div>


</div>
