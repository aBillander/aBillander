
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('chart.customerorders.monthly', ['CustomerOrder']) }}">
                                 {{l('Sales Orders', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('chart.customerorders.monthly', ['CustomerShippingSlip']) }}">
                                 {{l('Sales Shipping Slips', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('chart.customerorders.daily', ['CustomerShippingSlip']) }}">
                                <i class="fa fa-long-arrow-right text-info"></i> 
                                 {{l('Sales Shipping Slips (daily)', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('chart.customerorders.monthly', ['CustomerInvoice']) }}">
                                 {{l('Sales Invoices', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ route('chart.customervouchers.monthly') }}">
                                 {{l('Customer Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('chart.suppliervouchers.monthly') }}">
                                 {{l('Supplier Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('chart.allvouchers.monthly') }}">
                                 {{l('All Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('jennifer/home') }}">
                                <i class="fa fa-user-secret text-success"></i> 
                                {{l('Accounting', [], 'layouts')}}</a>
                        </li>
                         <li>
                            <a href="{{ route('accounting.customerinvoices.index') }}">
                                 {{l('Customer Invoices', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('accounting.customers.index') }}">
                                 {{l('Customers', [], 'layouts')}}
                            </a>
                        </li>

                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('helferin/home') }}">
                                <i class="fa fa-gears text-info"></i> 
                                {{l('Earns & Profit', [], 'layouts')}}</a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('reports/home') }}">
                                <i class="fa fa-coffee text-danger"></i> 
                                {{l('Analysis', [], 'layouts')}}</a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
