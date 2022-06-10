
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-street-view"></i> {!! l('Commercial / CRM', [], 'layouts') !!} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('customers/actions/dashboard') }}">
                                 <i class="fa fa-dashboard text-info"></i> 
                                 {{l('Customers Dashboard', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('actiontypes') }}">
                                 {{l('Action Types', [], 'layouts')}}
                            </a>
                        </li>
@if ( AbiConfiguration::isTrue('ENABLE_CUSTOMER_CENTER') )
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('abccbillboard/edit') }}">
                                 <!-- img src="{{ asset('assets/theme/new-badge-20.png') }}" --> 
                                 <i class="fa fa-desktop  text-muted"></i> 
                                 {{l('ABCC Billboard', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
@endif
                         <li>
                            <a href="{{ URL::to('shippingmethods') }}">
                                 {{l('Shipping Methods', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('carriers') }}">
                                 {{l('Carriers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('salesreps') }}">
                                 <i class="fa fa-binoculars text-success"></i> 
                                 {{l('Sales Representatives', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
