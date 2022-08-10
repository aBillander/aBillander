@inject('request', 'Illuminate\Http\Request')
<!-- Main Header -->
  <header class="main-header no-print">
    <a href="{{route('pos::home')}}" class="logo">
      
      <span class="logo-lg">{{ 'Wath, Ltd.' }} <i class="fa fa-circle text-success" id="online_indicator"></i></span> 

    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        &#9776;
        <span class="sr-only">Toggle navigation</span>
      </a>

      @if(0 && "Module::has('Superadmin')")
        @includeIf('superadmin::layouts.partials.active_subscription')
      @endif

        @if(!empty(session('previous_user_id')) && !empty(session('previous_username')))
            <a href="{{route('sign-in-as-user', session('previous_user_id'))}}" class="btn btn-flat btn-danger m-8 btn-sm mt-10"><i class="fas fa-undo"></i> @lang('lang_v1.back_to_username', ['username' => session('previous_username')] )</a>
        @endif

      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">

        @if(0 && "Module::has('Essentials')")
          @includeIf('essentials::layouts.partials.header_part')
        @endif

        <div class="btn-group">
          <button id="header_shortcut_dropdown" type="button" class="btn btn-success dropdown-toggle btn-flat pull-left m-8 btn-sm mt-10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-plus-circle fa-lg"></i>
          </button>
          <ul class="dropdown-menu">
            
              <li><a href="{ {route('calendar')} }">
                  <i class="fas fa-calendar-alt" aria-hidden="true"></i> @lang('lang_v1.calendar')
              </a></li>
            
            @if("Module::has('Essentials')")
              <li><a href="#" class="btn-modal" data-href="{ {action('\Modules\Essentials\Http\Controllers\ToDoController@create')} }" data-container="#task_modal">
                  <i class="fas fa-clipboard-check" aria-hidden="true"></i> @lang( 'essentials::lang.add_to_do' )
              </a></li>
            @endif
            <!-- Help Button -->
            @if("auth()->user()->hasRole('Admin#' . auth()->user()->business_id)")
              <li><a id="start_tour" href="#">
                  <i class="fas fa-question-circle" aria-hidden="true"></i> @lang('lang_v1.application_tour')
              </a></li>
            @endif
          </ul>
        </div>
        <button id="btnCalculator" title="{{ l('Calculator') }}" type="button" class="btn btn-success btn-flat pull-left m-8 btn-sm mt-10 popover-default hidden-xs" data-toggle="popover" data-trigger="click" data-content='@include("pos::layouts.partials.calculator")' data-html="true" data-placement="bottom">
            <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
        </button>
        
        
          <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-flat pull-left m-8 btn-sm mt-10 btn-modal" data-container=".register_details_modal" 
          data-href="{ { action('CashRegisterController@getRegisterDetails')} }">
            <strong><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></strong>
          </button>

          <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-flat pull-left m-8 btn-sm mt-10 btn-modal" data-container=".close_register_modal" 
          data-href="{ { action('CashRegisterController@getCloseRegister')} }">
            <strong><i class="fa fa-window-close fa-lg"></i></strong>
          </button>

          <a href="{{ route('pos::interface') }}" title="{{ l('POS Interface') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-flat pull-left m-8 btn-sm mt-10 btn-success">
            <strong><i class="fa fa-th-large"></i> &nbsp; {{ l('POS') }}</strong>
          </a>

        
          <button type="button" id="view_todays_profit" title="{{ __('home.todays_profit') }}" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-flat pull-left m-8 btn-sm mt-10">
            <strong><i class="fas fa-money-bill-alt fa-lg"></i></strong>
          </button>

        <div class="m-8 pull-left mt-15 hidden-xs" style="color: #fff;"><strong>{{ \Carbon\Carbon::now()->format(auth()->user()->language->date_format_full) }}</strong></div>

        <ul class="nav navbar-nav">
          @include('pos::layouts.partials.header-notifications')
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              @php
                $profile_photo = auth()->user()->media;
              @endphp
              @if(!empty($profile_photo))
                <img src="{{$profile_photo->display_url}}" class="user-image" alt="User Image">
              @endif
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span>{{ Auth::User()->firstname }} {{ Auth::User()->lastname }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                @if(1||!empty(Session::get('business.logo')))
                  <img src="{{ asset( 'assets/abi-pos/uploads/business_logos/' . 'cashier_user_default.jpg' ) }}" alt="Logo" style="max-width: 120px"> {{-- 260×120 --}}
                @endif
                <p>
                  {{ Auth::User()->firstname }} {{ Auth::User()->lastname }}
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('pos::account.edit') }}" class="btn btn-default btn-flat">{{ l('My Profile') }}</a>
                </div>
                <div class="pull-right">
                    <a class="btn btn-default btn-flat" 
                        href="javascript:void(0);"
                        onclick="event.preventDefault();
                                 document.getElementById('cashier-logout-form').submit();">
                        <i class="fa fa-power-off"></i> {{ l('Sign Out') }}
                    </a>

                    <form id="cashier-logout-form" action="{{ route('pos::cashier.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>