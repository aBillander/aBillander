<nav class="navbar navbar-inverse" role="navigation" style="margin: 0px 0px 5px 0px;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            @auth('customer')
                <a href="{{ URL::to( (Auth::user()->home_page ? Auth::user()->home_page : '/abcc') ) }}" class="navbar-brand" style="xposition: relative;">
                @if ( 0 )
<!--                @ i f ($img = \App\Context::getContext()->company->company_logo)          -->
                    <img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ URL::to( \App\Company::$company_path . $img ) }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;">
                    <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> LXVII</span>
                @else
                    <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> LXVII</span>
                @endif
                </a>
            @else
                <a href="{{ URL::to('/') }}" class="navbar-brand"><span style="color:#dddddd"><i class="fa fa-bolt"></i> a<span style="color:#fff">Billander</span></span></a>
            @endauth
        </div>
        <nav class="collapse navbar-collapse" role="navigation">
            <ul class="nav navbar-nav navbar-right">

                @if( Auth::guard('customer')->check() )

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i> {{l('Invoicing', [], 'abcc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ route('abcc.invoices.index') }}">
                                 {{l('Orders', [], 'abcc/layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                         <!-- li>
                            <a href="{{ route('abcc.invoices.index') }}">
                                 {{l('Customer Invoices', [], 'abcc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('abcc.vouchers.index') }}">
                                 {{l('Customer Vouchers', [], 'abcc/layouts')}}
                            </a>
                        </li -->
                        <li class="divider"></li>
                    </ul>
                </li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'abcc/layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="divider"></li>
                    </ul>
                </li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getFullName() }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="https://abillander.gitbooks.io/abillander-tutorial-spanish/content/" target="_blank">
                                 {{l('Documentation', [], 'abcc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a data-target="#contactForm" data-toggle="modal" onclick="return false;" href="">
                                 {{l('Support & feed-back', [], 'abcc/layouts')}}
                            </a>
                        </li>
                         <li>
                            <a data-target="#aboutLaraBillander" data-toggle="modal" onclick="return false;" href="">
                                 {{l('About ...', [], 'abcc/layouts')}}
                            </a>
                        </li>
                        
@if (config('app.url') =='http://localhost/aBillander55') {{-- or Config::get('app.myVarname'); see https://laracasts.com/discuss/channels/general-discussion/ho-to-access-config-variables-in-laravel-5 --}}
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
                <a href="{{ URL::to('login') }}">
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