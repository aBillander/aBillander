<nav class="navbar navbar-inverse" role="navigation" style="margin: 0px 0px 5px 0px;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
{{--
            @auth('customer')
                <a href="{{ URL::to( (Auth::user()->home_page ? '/abcc' : '/abcc') ) }}" class="navbar-brand" style="xposition: relative;">
{ {--
                @if ( 0 )
<!--                @ i f ($img = \App\Context::getContext()->company->company_logo)          -->
                    <img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ URL::to( \App\Company::$company_path . $img ) }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;">
                    <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> LXVII</span>
--} }
                @if ( \App\Configuration::isEmpty('ABCC_HEADER_TITLE') )
                    <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> LXVII</span>
                @else
                    {!! \App\Configuration::get('ABCC_HEADER_TITLE') !!}
                @endif
                </a>
            @else
                <a href="{{ URL::to('/abcc') }}" class="navbar-brand"><span style="color:#dddddd"><i class="fa fa-bolt"></i> a<span style="color:#fff">Billander</span></span></a>
            @endauth
--}}

                <a href="{{ URL::to('/abcc') }}" class="navbar-brand">

                    @if ( \App\Configuration::isEmpty('ABCC_HEADER_TITLE') )
                        <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> Customer Center</span>
                    @else
                        {!! \App\Configuration::get('ABCC_HEADER_TITLE') !!}
                    @endif

                </a>

        </div>
        {{-- abi_r(Auth::user()) --}}
        <nav class="collapse navbar-collapse" role="navigation">
            <ul class="nav navbar-nav navbar-right">

                @if( Auth::guard('customer')->check() )

                <li class="dropdown">
                    <a href="{{ route('abcc.customer.pricerules') }}" class="dropdown-toggle"><i class="fa fa-thumbs-o-up"></i> {{l('Price Rules', [], 'abcc/layouts')}} </a>
                </li>

@if( \App\Configuration::isTrue('ABCC_ENABLE_NEW_PRODUCTS') )
                <li class="dropdown">
                    <a href="{{ route('abcc.catalogue.newproducts') }}" class="dropdown-toggle"><i class="fa fa-bullhorn"></i> {{l('New Products', [], 'abcc/layouts')}} </a>
                </li>
@endif
                <li class="dropdown">
                    <a href="{{ route('abcc.cart') }}" class="dropdown-toggle"><i class="fa fa-shopping-cart"></i> {{l('Shopping Cart', [], 'abcc/layouts')}}  <span id="badge_cart_nbr_items" class="badge">
                        {{ \App\Context::getContext()->cart->nbrItems() }}
                    </span> </a>
                </li>

                <li class="dropdown">
                    <a href="{{ route('abcc.catalogue') }}" class="dropdown-toggle"><i class="fa fa-book"></i> {{l('Catalogue', [], 'abcc/layouts')}} </a>
                </li>

@if ( \App\Configuration::isTrue('ABCC_ENABLE_SHIPPING_SLIPS') || \App\Configuration::isTrue('ABCC_ENABLE_INVOICES') )

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-history"></i> {{l('Order History', [], 'abcc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('abcc.orders.index') }}">
                                 {{l('Orders', [], 'abcc/layouts')}}
                            </a>
                        </li>

@if ( \App\Configuration::isTrue('ABCC_ENABLE_SHIPPING_SLIPS') )

                        <li class="divider"></li>
                         <li>
                            <a href="{{ route('abcc.shippingslips.index') }}">
                                 {{l('Shipping Slips', [], 'abcc/layouts')}}
                            </a>
                        </li>
@endif

@if ( \App\Configuration::isTrue('ABCC_ENABLE_INVOICES') )

                        <li class="divider"></li>
                         <li>
                            <a href="{{ route('abcc.invoices.index') }}">
                                 {{l('Invoices', [], 'abcc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('abcc.vouchers.index') }}">
                                 {{l('Vouchers', [], 'abcc/layouts')}}
                            </a>
                        </li>
@endif

@if ( \App\Configuration::isTrue('ABCC_ENABLE_QUOTATIONS') )

                        <li class="divider"></li>
                         <li>
                            <a href="{{ route('abcc.quotations.index') }}">
                                 {{l('Quotations', [], 'abcc/layouts')}}
                            </a>
                        </li>
@endif
                        <!-- li class="divider"></li -->
                    </ul>
                </li>

@else

                <li class="dropdown">
                    <a href="{{ route('abcc.orders.index') }}" class="dropdown-toggle"><i class="fa fa-history"></i> {{l('Order History', [], 'abcc/layouts')}} </a>
                </li>

@endif



{{--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'abcc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="divider"></li>
                    </ul>
                </li>
--}}

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getFullName() }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('abcc.account.edit') }}">
                                 {{l('My Account', [], 'abcc/layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <!-- li>
                            <a href="https://abillander.gitbooks.io/abillander-tutorial-spanish/content/" target="_blank">
                                 {{l('Documentation', [], 'abcc/layouts')}}
                            </a>
                        </li -->
                         <li>
                            <a data-target="#contactForm" data-toggle="modal" onclick="return false;" href="">
                                 {{l('Contact', [], 'abcc/layouts')}}
                            </a>
                        </li>
                         <!-- li>
                            <a data-target="#aboutLaraBillander" data-toggle="modal" onclick="return false;" href="">
                                 {{l('About ...', [], 'abcc/layouts')}}
                            </a>
                        </li -->

                        <li class="divider"></li>

                         <li>
                            <a href="{{ asset('uploads/documents/Privacy_Policy.pdf') }}" target="_blank">
                                 {{l('Privacy Policy', [], 'abcc/layouts')}}
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="javascript:void(0);"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> {{l('Logout', [], 'abcc/layouts')}}
                            </a>

                            <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
                @else
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <a href="{{ URL::to('abcc/login') }}">
                    <button class="btn btn-default navbar-btn">
                        <i class="fa fa-user"></i> {{l('Login', [], 'abcc/layouts')}} 
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