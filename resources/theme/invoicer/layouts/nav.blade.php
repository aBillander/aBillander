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
                    @if ( Auth::check() && $img )

                        <img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;">

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

@if ( \App\Configuration::isTrue('ENABLE_MCRM') )
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> {{l('microCRM', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('crm/home') }}">
                                 <i class="fa fa-dashboard text-info"></i> 
                                 {{l('Dashboard', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('parties') }}">
                                 <!-- i class="fa fa-exclamation-triangle btn-xs btn-danger"></i --> 
                                 {{l('Parties', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('leads') }}">
                                 <!-- i class="fa fa-exclamation-triangle btn-xs btn-danger"></i --> 
                                 {{l('Leads', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('contacts') }}">
                                 <i class="fa fa-address-card-o text-success"></i --> 
                                 {{l('Contacts', [], 'layouts')}}
                            </a>
                        </li>
                    </ul>
                </li>
@endif

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-bag"></i> {{l('Sales', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
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

                         <li>
                            <a href="{{ URL::to('customers') }}">
                                 {{l('Customers', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('paymentmethods') }}">
                                 {{l('Payment Methods', [], 'layouts')}}
                            </a>
                        </li>
                    </ul>
                </li>


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

                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('measureunits') }}">
                                 {{l('Measure Units', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('categories') }}">
                                 {{l('Product Categories', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('customergroups') }}">
                                 {{l('Customer Groups', [], 'layouts')}}
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
                         <li>
                            <a href="{{ URL::to('emaillogs') }}">
                                 <i class="fa fa-clipboard text-info"></i>
                                 {{l('Email LOG', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>




                <li class=" hide dropdown">
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
                            <a href="{{ route('chart.customerorders.monthly', ['CustomerInvoice']) }}">
                                 {{l('Sales Invoices', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('jennifer/home') }}">
                                <i class="fa fa-user-secret text-success"></i> 
                                {{l('Accounting', [], 'layouts')}}</a>
                        </li>
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('helferin/home') }}">
                                <i class="fa fa-gears text-info"></i> 
                                {{l('Earns & Profit', [], 'layouts')}}</a>
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
                            <a href="{{ URL::to('dbbackups') }}">
                                 <i class="fa fa-database text-danger"></i> {{l('DB Backups', [], 'layouts')}}
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
