      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_tab_index_1" href="{{ route('abcc.account.edit') }}" class="list-group-item @if ($tab_index=='account') active @endif">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Account Data', 'abcc/account') }}
            </a>
            <a id="b_tab_index_2" href="{{ route('abcc.customer.edit') }}" class="list-group-item @if ($tab_index=='customer') active @endif">
               <i class="fa fa-building"></i></span>
               &nbsp; {{ l('My Company', 'abcc/account') }}
            </a>
            <a id="b_tab_index_3" href="{{ route('abcc.customer.addresses.index') }}" class="list-group-item @if ($tab_index=='addresses') active @endif">
               <i class="fa fa-address-book"></i>
               &nbsp; {{ l('Address Book', 'abcc/account') }}
            </a>
         </div>
      </div>
