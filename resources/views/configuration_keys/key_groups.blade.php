      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_tab_index_1" href="{{ URL::to('configurationkeys?tab_index=1') }}" class="list-group-item @if ($tab_index==1) active @endif">
               <i class="fa fa-building"></i>
               &nbsp; {{ l('My Company') }}
            </a>
            <a id="b_tab_index_2" href="{{ URL::to('configurationkeys?tab_index=2') }}" class="list-group-item @if ($tab_index==2) active @endif">
               <i class="fa fa-dashboard"></i>
               &nbsp; {{ l('Default Values') }}
            </a>
            <a id="b_tab_index_3" href="{{ URL::to('configurationkeys?tab_index=3') }}" class="list-group-item @if ($tab_index==3) active @endif">
               <i class="fa fa-th-large"></i>
               &nbsp; {{ l('Other') }}
            </a>
@if (\App\Configuration::get('SKU_AUTOGENERATE') )
            <a id="b_tab_index_4" href="{{ URL::to('configurationkeys?tab_index=4') }}" class="list-group-item @if ($tab_index==4) active @endif">
               <i class="fa fa-bookmark"></i>
               &nbsp; {{ l('Auto-SKU') }}
            </a>
@endif

@if (\App\Configuration::isTrue('ENABLE_CUSTOMER_CENTER') )
            <a id="b_tab_index_5" href="{{ URL::to('configurationkeys?tab_index=5') }}" class="list-group-item @if ($tab_index==5) active @endif">
               <i class="fa fa-user-circle"></i>
               &nbsp; {{ l('Customer Center') }}
            </a>
@endif

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )
            <a href="{{ route('fsxconfigurationkeys.index') }}" class="list-group-item">
               <i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i>
               &nbsp; {{l('Enlace FactuSOL', 'layouts')}}
            </a>
@endif
            <a id="b_tab_index_none" href="" class="list-group-item" style="padding: 3px 15px;">
            </a>
            <a id="b_tab_index_" href="{{ URL::to('configurations') }}" class="list-group-item @if ($tab_index==-1) active @endif">
               <i class="fa fa-book"></i>
               &nbsp; {{ l('All Keys') }}
            </a>

@if (\App\Configuration::isTrue('DEVELOPER_MODE') )
            <a id="b_tab_index_none_2" href="" class="list-group-item" style="padding: 3px 15px;">
            </a>
            <a href="http://bootswatch.com/3/united/" target="_blank" class="list-group-item">
               - {{ l('Template BS3') }}
            </a>
            <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank" class="list-group-item">
               - {{ l('Font-Awesome') }}
            </a>
            <a href="https://woocommerce.github.io/woocommerce-rest-api-docs/" target="_blank" class="list-group-item">
               - {{ l('Woo Rest Api') }}
            </a>
            <a id="b_tab_index_none_2" href="" class="list-group-item" style="padding: 3px 15px;">
            </a>
            <a href="{{ URL::to('translations') }}" class="list-group-item">
               <i class="fa fa-wrench"></i>
               &nbsp; {{l('Translations', [], 'layouts')}}
            </a>
            <a href="{{ URL::to('templates') }}" class="list-group-item">
               <i class="fa fa-wrench"></i>
               &nbsp; {{l('Document templates', [], 'layouts')}}
            </a>
@endif
         </div>
      </div>