      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="tab_index_1" href="{{ URL::route('wooconfigurationkeys.index') }}" class="list-group-item @if ($tab_index==1) active @endif">
               <i class="fa fa-dashboard"></i>
               &nbsp; {{ l('General') }}
            </a>
            <a id="tab_index_5" href="{{ URL::route('wooconnect.configuration') }}" class="list-group-item @if ($tab_index==5) active @endif">
               <i class="fa fa-th-large"></i>
               &nbsp; {{ l('Shop') }}
            </a>
            <a id="tab_index_2" href="{{ URL::route('wooconnect.configuration.taxes') }}" class="list-group-item @if ($tab_index==2) active @endif">
               <i class="fa fa-bank"></i></span>
               &nbsp; {{ l('Taxes') }}
            </a>
            <a id="tab_index_3" href="{{ URL::route('wooconnect.configuration.paymentgateways') }}" class="list-group-item @if ($tab_index==3) active @endif">
               <i class="fa fa-money"></i></span>
               &nbsp; {{ l('Payment Gateways') }}
            </a>
            <a id="tab_index_4" href="{{ URL::route('wooconnect.configuration.shippingmethods') }}" class="list-group-item @if ($tab_index==4) active @endif">
               <i class="fa fa-truck"></i>
               &nbsp; {{ l('Shipping Methods') }}
            </a>

            <a id="tab_index_none" href="" class="list-group-item" style="display:none;" style="padding: 3px 15px;">
            </a>

            <a id="tab_index_" href="{{ URL::to('configurations') }}" style="display:none;" class="list-group-item @if ($tab_index==-1) active @endif">
               <i class="fa fa-book"></i></span>
               &nbsp; {{ l('All Keys') }}
            </a>
         </div>
      </div>