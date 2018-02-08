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
                <img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ asset('assets/theme/images/xtralogo.png') }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;">
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
                <!-- li><a href="{{ URL::to('clients') }}"><i class="fa fa-dashboard"></i> Sistema</a></li -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> {{l('Basic Data', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('currencies') }}">
                                 {{l('Currencies', [], 'layouts')}}
                            </a>
                        </li>
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
                         <!-- li>
                            <a href="{{ URL::to('account') }}">
                                 Bancos
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('account') }}">
                                 Cuentas Remesas
                            </a>
                        </li -->
                        <li class="divider"></li>
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
                        <li class="divider"></li>
                         <!-- li>
                            <a href="{{ URL::to('account') }}">
                                 Países
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('account') }}">
                                 Provincias
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('account') }}">
                                 Zonas
                            </a>
                        </li -->
                        <li class="divider"></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dashboard"></i> {{l('System', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <!-- li class="disabled">
                            <a href="" onClick="return false;">
                                 Tablas Generales
                            </a>
                        </li>
                        <li class="divider"></li -->
                         <li>
                            <a href="{{ URL::to('customers') }}">
                                 {{l('Customers', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('salesreps') }}">
                                 {{l('Sales Representatives', [], 'layouts')}}
                            </a>
                        </li>
                         <!-- li>
                            <a href="{{ URL::to('account') }}">
                                 Proveedores
                            </a>
                        </li -->
                         <li>
                            <a href="{{ URL::to('carriers') }}">
                                 Transportistas
                            </a>
                        </li>
                         <!-- li>
                            <a href="{{ URL::to('manufacturers') }}">
                                 Fabricantes
                            </a>
                        </li -->
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('warehouses') }}">
                                 {{l('Warehouses', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('pricelists') }}">
                                 Tarifas
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('optiongroups') }}">
                                 {{l('Product Options', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('products') }}">
                                 {{l('Products', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
                <!-- li><a href="{{ URL::to('clients') }}"><i class="fa fa-shopping-cart"></i> Almacén</a></li -->


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> {{l('Warehouse', [], 'layouts')}} <span class="caret"></span></a>
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
                    </ul>
                </li>


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


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="divider"></li>
                    </ul>
                </li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getFullName() }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="https://abillander.gitbooks.io/abillander-tutorial-spanish/content/" target="_blank">
                                 {{l('Documentation', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a data-target="#contactForm" data-toggle="modal" onclick="return false;" href="">
                                 {{l('Support & feed-back', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a data-target="#aboutLaraBillander" data-toggle="modal" onclick="return false;" href="">
                                 {{l('About ...', [], 'layouts')}}
                            </a>
                        </li>
@if (config('app.url') =='http://localhost/enatural') {{-- or Config::get('app.myVarname'); see https://laracasts.com/discuss/channels/general-discussion/ho-to-access-config-variables-in-laravel-5 --}}
                        <li class="divider"></li>
                         <li>
                            <a href="http://bootswatch.com/3/united/" target="_blank">
                                 Plantilla BS3
                            </a>
                        </li>
                         <!-- li>
                            <a href="http://getbootstrap.com/components/" target="_blank">
                                 Glyphicons
                            </a>
                        </li -->
                         <li>
                            <a href="http://fontawesome.io/icons/" target="_blank">
                                 Font-Awesome
                            </a>
                        </li>
@endif
                        @if ( Auth::user()->isAdmin() )
                        <li class="divider"></li>
                         <li>
                            <a href="{{ URL::to('companies') }}">
                                 {{l('Company', [], 'layouts')}}
                            </a>
                        </li>
                         <!-- li>
                            <a href="{{ URL::to('configurations') }}">
                                 {{l('Configuration - All keys', [], 'layouts')}}
                            </a>
                        </li -->
                         <li>
                            <a href="{{ URL::to('configurations') }}">
                                 {{l('Configuration', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('languages') }}">
                                 {{l('Languages', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('translations') }}">
                                 {{l('Translations', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('countries') }}">
                                 {{l('Countries', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('sequences') }}">
                                 {{l('Document sequences', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('templates') }}">
                                 {{l('Document templates', [], 'layouts')}}
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