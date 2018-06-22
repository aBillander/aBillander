      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_tab_index_1" href="{{ URL::to('configurationkeys?tab_index=1') }}" class="list-group-item @if ($tab_index==1) active @endif">
               <i class="fa fa-building"></i>
               &nbsp; {{ l('My Company') }}
            </a>
            <a id="b_tab_index_2" href="{{ URL::to('configurationkeys?tab_index=2') }}" class="list-group-item @if ($tab_index==2) active @endif">
               <i class="fa fa-dashboard"></i></span>
               &nbsp; {{ l('Default Values') }}
            </a>
            <a id="b_tab_index_3" href="{{ URL::to('configurationkeys?tab_index=3') }}" class="list-group-item @if ($tab_index==3) active @endif">
               <i class="fa fa-th-large"></i>
               &nbsp; {{ l('Other') }}
            </a>
@if (\App\Configuration::get('SKU_AUTOGENERATE') )
            <a id="b_tab_index_4" href="{{ URL::to('configurationkeys?tab_index=4') }}" class="list-group-item @if ($tab_index==4) active @endif">
               <i class="fa fa-bookmark"></i></span>
               &nbsp; {{ l('Auto-SKU') }}
            </a>
@endif
            <a id="b_tab_index_none" href="" class="list-group-item" style="padding: 3px 15px;">
            </a>
            <a id="b_tab_index_" href="{{ URL::to('configurations') }}" class="list-group-item @if ($tab_index==-1) active @endif">
               <i class="fa fa-book"></i></span>
               &nbsp; {{ l('All Keys') }}
            </a>
         </div>
      </div>