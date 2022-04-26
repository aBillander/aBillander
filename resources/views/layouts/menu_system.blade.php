
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> {{l('System', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('products') }}">
                                 {{l('Products', [], 'layouts')}}
                            </a>
                        </li>
                         <!-- li>
                            <a href="{{ URL::to('products') }}">
                                 {{l('Finished Products', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('ingredients') }}">
                                 {{l('Ingredients', [], 'layouts')}}
                            </a>
                        </li -->
@if ( AbiConfiguration::isTrue('ENABLE_MANUFACTURING') )
                         <li>
                            <a href="{{ URL::to('productboms') }}">
                                 {{l('Bills of Materials', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('tools') }}">
                                 {{l('Tools', [], 'layouts')}}
                            </a>
                        </li>
@endif
@if ( AbiConfiguration::isTrue('ENABLE_COMBINATIONS') )
                         <li>
                            <a href="{{ URL::to('optiongroups') }}">
                                 {{l('Product Options', [], 'layouts')}}
                            </a>
                        </li>
@endif
@if ( AbiConfiguration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
                         <li>
                            <a href="{{ URL::to('wooc/wproducts') }}">
                                 <i class="fa fa-cloud-upload btn-xs alert-info"></i> 
                                 {{l('Products', [], 'layouts')}} [WooC]
                            </a>
                        </li>
@endif

                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('measureunits') }}">
                                 {{l('Measure Units', [], 'layouts')}}
                            </a>
                        </li>

@if ( AbiConfiguration::isTrue('ENABLE_MANUFACTURING') )
                         <li>
                            <a href="{{ URL::to('workcenters') }}">
                                 {{l('Work Centers', [], 'layouts')}}
                            </a>
                        </li>
@endif
                         <li>
                            <a href="{{ URL::to('categories') }}">
                                 {{l('Product Categories', [], 'layouts')}}
                            </a>
                        </li>
@if ( AbiConfiguration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
                         <li>
                            <a href="{{ URL::to('wooc/wcategories') }}">
                                 <i class="fa fa-cloud-upload btn-xs alert-info"></i> 
                                 {{l('Product Categories', [], 'layouts')}} [WooC]
                            </a>
                        </li>
@endif
                         <li>
                            <a href="{{ URL::to('manufacturers') }}">
                                 {{l('Manufacturers', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('customergroups') }}">
                                 {{l('Customer Groups', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('paymentmethods') }}">
                                 {{l('Payment Methods', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('paymenttypes') }}">
                                 <i class="fa fa-btc btn-xs text-success"></i>
                                 {{l('Payment Types', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('taxes') }}">
                                 {{l('Taxes', [], 'layouts')}}
                            </a>
                        </li>
@if ( AbiConfiguration::isTrue('ENABLE_ECOTAXES') )
                         <li>
                            <a href="{{ URL::to('ecotaxes') }}">
                                 {{l('Ecotaxes', [], 'layouts')}}
                            </a>
                        </li>
@endif
{{--
                         <li>
                            <a href="{{ URL::to('cheques') }}">
                                 <i class="fa fa-money btn-xs text-success"></i> 
                                 {{l('Cheques', [], 'layouts')}}
                                 <!-- img src="{{ asset('assets/theme/self-distract-button-20.png') }}" --> 
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('supplier.downpayments.index') }}">
                                 <i class="fa fa-money btn-xs text-danger"></i> 
                                 {{l('Down Payments', [], 'layouts')}}
                                 <!-- img src="{{ asset('assets/theme/self-distract-button-20.png') }}" --> 
                            </a>
                        </li>
--}}
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('import') }}">
                                 <i class="fa fa-file-excel-o text-grey"></i>
                                 {{l('Import', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('activityloggers') }}">
                                 <i class="fa fa-clipboard text-warning"></i>
                                 {{l('aBillander LOG', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('emaillogs') }}">
                                 <i class="fa fa-clipboard text-info"></i>
                                 {{l('Email LOG', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
