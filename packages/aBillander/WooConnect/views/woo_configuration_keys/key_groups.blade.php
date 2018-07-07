      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_tab_index_1" href="{{ URL::to('wooc/wooconnect/wooconfigurationkeys?tab_index=1') }}" class="list-group-item @if ($tab_index==1) active @endif">
               <i class="fa fa-dashboard"></i>
               &nbsp; {{ l('General') }}
            </a>
            <a id="b_tab_index_2" href="{{ URL::to('wooc/wooconnect/wooconfigurationkeys?tab_index=2') }}" class="list-group-item @if ($tab_index==2) active @endif">
               <i class="fa fa-bank"></i></span>
               &nbsp; {{ l('Taxes') }}
            </a>
            <a id="b_tab_index_3" href="{{ URL::to('wooc/wooconnect/wooconfigurationkeys?tab_index=3') }}" class="list-group-item @if ($tab_index==3) active @endif">
               <i class="fa fa-money"></i></span>
               &nbsp; {{ l('Payment Gateways') }}
            </a>
            <a id="b_tab_index_4" href="{{ URL::to('wooc/wooconnect/wooconfigurationkeys?tab_index=4') }}" class="list-group-item @if ($tab_index==4) active @endif">
               <i class="fa fa-truck"></i>
               &nbsp; {{ l('Shipping Methods') }}
            </a>
            <a id="b_tab_index_5" href="{{ URL::to('wooc/wooconnect/wooconfigurationkeys?tab_index=5') }}" class="list-group-item @if ($tab_index==5) active @endif">
               <i class="fa fa-th-large"></i>
               &nbsp; {{ l('Other') }}
            </a>
            <a id="b_tab_index_none" href="" class="list-group-item" style="padding: 3px 15px;">
            </a>
            <a id="b_tab_index_" href="{{ URL::to('configurations') }}" class="list-group-item @if ($tab_index==-1) active @endif">
               <i class="fa fa-book"></i></span>
               &nbsp; {{ l('All Keys') }}
            </a>
         </div>
      </div>