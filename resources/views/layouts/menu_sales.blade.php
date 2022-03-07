
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-bag"></i> {{l('Sales', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('customerquotations') }}">
                                 <!-- i class="fa fa-exclamation-triangle btn-xs btn-danger"></i --> 
                                 {{l('Quotations', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
@if ( AbiConfiguration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
                         <li>
                            <a href="{{ URL::to('wooc/worders') }}">
                                 <i class="fa fa-cloud-download btn-xs alert-warning"></i> 
                                 {{l('Sale Orders', [], 'layouts')}} [WooC]
                            </a>
                        </li>
                        <!-- li class="divider"></li -->
@endif
                         <li>
                            <a href="{{ URL::to('customerorders') }}">
                                 <i class="fa fa-keyboard-o btn-xs alert-success"></i> 
                                 {{l('Sale Orders', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('customerordertemplates') }}">
                                 <!-- img src="{{ asset('assets/theme/self-distract-button-20.png') }}" --> 
                                 {{l('Sale Order Templates', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('customershippingslips') }}">
                                 {{l('Shipping Slips', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('customerinvoices') }}">
                                 {{l('Customer Invoices', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('customervouchers') }}">
                                 <!-- i class="fa fa-exclamation-triangle btn-xs btn-danger"></i --> 
                                 <i class="fa fa-credit-card btn-xs alert-info"></i> 
                                 {{l('Customer Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                        
@if ( AbiConfiguration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
                         <li>
                            <a href="{{ URL::to('wooc/wcustomers') }}">
                                 <i class="fa fa-cloud-download btn-xs alert-warning"></i> 
                                 {{l('Customers', [], 'layouts')}} [WooC]
                            </a>
                        </li>
                        <!-- li class="divider"></li -->
@endif

                         <li>
                            <a href="{{ URL::to('customers') }}">
                                 {{l('Customers', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('pricelists') }}">
                                 {{l('Price Lists', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('pricerules') }}">
                                 <i class="fa fa-gavel text-info"></i> 
                                 {{l('Price Rules', [], 'layouts')}}
                            </a>
                        </li>
@if ( AbiConfiguration::isTrue('ENABLE_CUSTOMER_CENTER') )
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('abccbillboard/edit') }}">
                                 <!-- img src="{{ asset('assets/theme/new-badge-20.png') }}" --> 
                                 &nbsp; {{l('ABCC Billboard', [], 'layouts')}}
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
                                 {{l('Sales Representatives', [], 'layouts')}}
                            </a>
                        </li>
                    </ul>
                </li>
