<nav class="navbar navbar-default" role="navigation" style="margin: 0px 0px 5px 0px;">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                @if ( \App\Configuration::isEmpty('HEADER_TITLE') )
                    <?php $img = \App\Context::getContext()->company->company_logo ?? ''; ?>
                    @if ( $img )

                        <img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ URL::to( \App\Company::$company_path . $img ) }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;">

                        <!-- img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ asset('assets/theme/images/company_logo.png') }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;" -->
                    @else
                        <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> </span>
                    @endif
                @else
                    {!! \App\Configuration::get('HEADER_TITLE') !!}
                    {{-- <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> </span> --}}
                @endif
            </a>
        </div>
        <div class="collapse navbar-collapse" role="navigation">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">

                @if( Auth::check() )

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
@if ( \App\Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') )
                         <li>
                            <a href="{{ URL::to('wooc/worders') }}">
                                 <i class="fa fa-cloud-download btn-xs btn-warning"></i> 
                                 {{l('Sale Orders', [], 'layouts')}} [WooC]
                            </a>
                        </li>
                        <li class="divider"></li>
@endif
                         <li>
                            <a href="{{ URL::to('customerorders') }}">
                                 <i class="fa fa-keyboard-o btn-xs btn-success"></i> 
                                 {{l('Sale Orders', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
@if ( \App\Configuration::isTrue('DEVELOPER_MODE') )
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
                                 <i class="fa fa-exclamation-triangle btn-xs btn-danger"></i> 
                                 {{l('Customer Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
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

@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cubes"></i> {{l('Manufacturing', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('productionsheets') }}">
                                 {{l('Production Sheets', [], 'layouts')}}
                            </a>
                        </li>

                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('productionorders') }}">
                                 {{l('Production Orders', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
@endif




@if ( \App\Configuration::isTrue('DEVELOPER_MODE') )
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-th"></i> {{l('Warehouse', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('stockmovements') }}">
                                 {{l('Stock Movements', [], 'layouts')}}
                            </a>
                        </li>
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
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('suppliers') }}">
                                 {{l('Suppliers', [], 'layouts')}}
                            </a>
                        </li>
                    </ul>
                </li>
@endif



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
@if ( \App\Configuration::isTrue('ENABLE_MANUFACTURING') )
                         <li>
                            <a href="{{ URL::to('productboms') }}">
                                 {{l('Bills of Materials', [], 'layouts')}}
                            </a>
                        </li>
@endif
@if ( \App\Configuration::isTrue('ENABLE_COMBINATIONS') )
                         <li>
                            <a href="{{ URL::to('optiongroups') }}">
                                 {{l('Product Options', [], 'layouts')}}
                            </a>
                        </li>
@endif

                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('measureunits') }}">
                                 {{l('Measure Units', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('workcenters') }}">
                                 {{l('Work Centers', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('categories') }}">
                                 {{l('Product Categories', [], 'layouts')}}
                            </a>
                        </li>
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
                            <a href="{{ URL::to('taxes') }}">
                                 {{l('Taxes', [], 'layouts')}}
                            </a>
                        </li>
@if ( \App\Configuration::isTrue('ENABLE_ECOTAXES') )
                         <li>
                            <a href="{{ URL::to('ecotaxes') }}">
                                 {{l('Ecotaxes', [], 'layouts')}}
                            </a>
                        </li>
@endif
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
                    </ul>
                </li>


@if ( 0 )
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i> {{l('Invoicing', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('wooc/worders') }}">
                                 {{l('Sale Orders', [], 'layouts')}} [WooC]
                            </a>
                        <li class="divider"></li>
                        </li>
                         <li>
                            <a href="{{ URL::to('customerinvoices') }}">
                                 {{l('Customer Invoices', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('customervouchers') }}">
                                 {{l('Customer Vouchers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
@endif


                <li class="dropdown" style="display:none;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('chart.customerorders.monthly') }}">
                                 {{l('Sales Orders', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getFullName() }}  
@if ( \App\Todo::pending() )
                    <span id="nbr_todos" class="badge" title="{{l('Pending Todos', [], 'layouts')}}">
                        {{ \App\Todo::pending() }}
                    </span> 
@endif
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="https://abillander.gitbook.io" target="_blank">
                                 {{l('Documentation', [], 'layouts')}}
                            </a>
                        </li>
                         <!-- li>
                            <a data-target="#contactForm" data-toggle="modal" onclick="return false;" href="">
                                 {{l('Support & feed-back', [], 'layouts')}}
                            </a>
                        </li -->
                         <li>
                            <a data-target="#aboutLaraBillander" data-toggle="modal" onclick="return false;" href="">
                                 {{l('About ...', [], 'layouts')}}
                            </a>
                        </li>

                        @if ( Auth::user()->isAdmin() )
                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('todos') }}">
                                 <i class="fa fa-tags text-success"></i> {{l('Todos', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('configurationkeys') }}">
                                 {{l('Configuration', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('languages') }}">
                                 {{l('Languages', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('currencies') }}">
                                 {{l('Currencies', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('countries') }}">
                                 {{l('Countries', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('warehouses') }}">
                                 {{l('Warehouses', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('sequences') }}">
                                 {{l('Document sequences', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('helpcontents') }}">
                                 <i class="fa fa-life-saver text-info"></i> {{l('Help Contents', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('companies') }}">
                                 {{l('Company', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('dbbackup') }}">
                                 <i class="fa fa-database text-danger"></i> {{l('DB Backup', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('users') }}">
                                 {{l('Users', [], 'layouts')}}
                            </a>
                        </li>
                        @endif
                        <li class="divider"></li>

                        <li>
                            <a href="javascript:void(0);"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> {{l('Logout', [], 'layouts')}}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
                @else
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <a href="{{ URL::to('login') }}">
                    <button class="btn btn-default navbar-btn">
                        <i class="fa fa-user"></i> {{l('Login', [], 'layouts')}} 
                    </button>
                </a>
                @endif
            </ul>
        </div>
    </div>
</nav>

{{--

Multi-Level Dropdowns

https://www.w3schools.com/Bootstrap/tryit.asp?filename=trybs_ref_js_dropdown_multilevel_css&stacked=h

https://github.com/almasaeed2010/AdminLTE/issues/1275
^-- "this requires extra JS code to get it to work since Bootstrap propagates the click event and closes the dropdown"

https://bootsnipp.com/snippets/featured/multi-level-dropdown-menu-bs3

--}}