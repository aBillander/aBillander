<nav class="navbar navbar-other" role="navigation" style="margin: 0px 0px 5px 0px;">
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
            <a class="navbar-brand" href="{{ url('/absrc') }}">
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
{{--
                <a href="{{ URL::to('/absrc') }}" class="navbar-brand">

                    @if ( \App\Configuration::isEmpty('ABSRC_HEADER_TITLE') )
                        <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> Sales Representative Center</span>
                    @else
                        {!! \App\Configuration::get('ABSRC_HEADER_TITLE') !!}
                    @endif

                </a>
--}}
        </div>
        {{-- abi_r(Auth::user()) --}}
        <nav class="collapse navbar-collapse" role="navigation">
            <ul class="nav navbar-nav navbar-right">

                @if( Auth::guard('salesrep')->check() )

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-bag"></i> {{l('Sales', [], 'absrc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('absrc.orders.index') }}">
                                 {{l('Orders', [], 'absrc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('absrc.shippingslips.index') }}">
                                 {{l('Shipping Slips', [], 'absrc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('absrc.invoices.index') }}">
                                 {{l('Invoices', [], 'absrc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('absrc.vouchers.index') }}">
                                 {{l('Vouchers', [], 'absrc/layouts')}}
                            </a>
                        </li>
                        <!-- li class="divider"></li -->
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="{{ route('absrc.customers.index') }}" class="dropdown-toggle"><i class="fa fa-user"></i> {{l('Customers', [], 'absrc/layouts')}} </a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book"></i> {{l('Catalogue', [], 'absrc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('absrc.catalogue') }}">
                                 {{l('Categories', [], 'absrc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('absrc.products.index') }}">
                                 {{l('Products', [], 'absrc/layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>



{{--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'absrc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="divider"></li>
                         <!-- li>
                            <a href="https://abillander.gitbooks.io/abillander-tutorial-spanish/content/" target="_blank">
                                 {{l('Documentation', [], 'absrc/layouts')}}
                            </a>
                        </li -->
                    </ul>
                </li>
--}}

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getFullName() }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <!-- li>
                            <a href="{ { route('absrc.account.edit') }}">
                                 {{l('My Account', [], 'absrc/layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li -->
                         <!-- li>
                            <a href="https://abillander.gitbooks.io/abillander-tutorial-spanish/content/" target="_blank">
                                 {{l('Documentation', [], 'absrc/layouts')}}
                            </a>
                        </li -->
                         <!-- li>
                            <a data-target="#contactForm" data-toggle="modal" onclick="return false;" href="">
                                 {{l('Contact', [], 'absrc/layouts')}}
                            </a>
                        </li -->
                         <!-- li>
                            <a data-target="#aboutLaraBillander" data-toggle="modal" onclick="return false;" href="">
                                 {{l('About ...', [], 'absrc/layouts')}}
                            </a>
                        </li -->

                        <li class="divider"></li>

                        <li>
                            <a href="javascript:void(0);"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> {{l('Logout', [], 'absrc/layouts')}}
                            </a>

                            <form id="logout-form" action="{{ route('salesrep.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
                @else
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <a href="{{ URL::to('absrc/login') }}">
                    <button class="btn btn-default navbar-btn">
                        <i class="fa fa-user"></i> {{l('Login', [], 'absrc/layouts')}} 
                    </button>
                </a>
                    @if( isset($languages) )
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-language"></i> {{ \App\Context::getContext()->language->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @foreach ($languages as $language)
                                <li>
                                    <a href="{{ URL::to('language/'.$language->id) }}">
                                         {{$language->name}}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endif
            </ul>
        </nav>
    </div>
</nav>