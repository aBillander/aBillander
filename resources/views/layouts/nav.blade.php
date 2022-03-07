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

                @if ( $img = AbiContext::getContext()->company->company_logo ?? '' )

                        <img class="navbar-brand img-rounded" height="{{ '40' }}" src="{{ URL::to( AbiCompany::imagesPath() . $img ) }}" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;">
                
                @else
                    @if ( ! AbiConfiguration::isEmpty('HEADER_TITLE') )

                        {!! AbiConfiguration::get('HEADER_TITLE') !!}
                    
                    @else
                        <span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> </span>
                    @endif
                @endif
            </a>

        </div>

        <div class="collapse navbar-collapse" role="navigation">

            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp; {{-- Empty menu --}}
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">

                @if( Auth::check() )

                    @include('layouts/menu_crm')

                    @include('layouts/menu_sales')

                    @include('layouts/menu_warehouse')

                    @include('layouts/menu_system')

                    @include('layouts/menu_reports')

                    @include('layouts/menu_user')

                @else

                    @include('layouts/menu_default')

                @endif
            </ul>
        </div>
    </div>
</nav>

{{--
    @include('layouts/modal_feedback')
--}}
