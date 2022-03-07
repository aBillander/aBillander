
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-th"></i> {{l('Warehouse', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('stockmovements') }}">
                                 {{l('Stock Movements', [], 'layouts')}}
                            </a>
                        </li>
@if ( AbiConfiguration::isTrue('ENABLE_LOTS') )
                         <li>
                            <a href="{{ URL::to('lots') }}">
                                 <!-- img src="{{ asset('assets/theme/self-distract-button-20.png') }}" --> 
                                 <i class="fa fa-window-restore btn-xs text-muted"></i> 
                                 {{l('Lots', [], 'layouts')}}
                            </a>
                        </li>
@endif
                         <li>
                            <a href="{{ URL::to('stockcounts') }}">
                                 {{l('Inventory Count', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('stockadjustments/create') }}">
                                 {{l('Inventory Adjustments', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('warehouseshippingslips') }}">
                                 {{l('Warehouse Transfers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ route('supplierorders.index') }}"> 
                                 {{l('Purchase Orders', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('suppliershippingslips.index') }}">
                                 <i class="fa fa-truck btn-xs alert-info"></i> 
                                 {{l('Supplier Shipping Slips', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('supplierinvoices.index') }}">
                                 <!-- i class="fa fa-money btn-xs alert-warning"></i --> 
                                 {{l('Supplier Invoices', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('suppliervouchers.index') }}">
                                 <i class="fa fa-credit-card btn-xs alert-success"></i> 
                                 {{l('Supplier Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ route('products.reorder.index') }}">
                                 <i class="fa fa-calculator btn-xs text-warning"></i> 
                                 {{l('Products with Low Stock', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('products.stock.index') }}">
                                 <i class="fa fa-calculator btn-xs text-danger"></i> 
                                 {{l('Products with no Stock', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('suppliers') }}">
                                 {{l('Suppliers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('assemblyorders') }}">
                                 <i class="fa fa-gift text-info"></i> 
                                 {{l('Assembly Orders', [], 'layouts')}}
                            </a>
                        </li>
                    </ul>
                </li>
