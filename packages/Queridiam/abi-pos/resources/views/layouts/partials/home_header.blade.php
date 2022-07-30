<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">{{config('app.name', 'ultimatePOS')}}</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        @if(Auth::check())
            <li><a href="{{ action('HomeController@index') }}">@lang('home.home')</a></li>
        @endif
        @if(Route::has('frontend-pages') && config('app.env') != 'demo' 
        && !empty($frontend_pages))
            @foreach($frontend_pages as $page)
                <li><a href="{{ action('\Modules\Superadmin\Http\Controllers\PageController@showPage', $page->slug) }}">{{$page->title}}</a></li>
            @endforeach
        @endif
        @if(Route::has('pricing') && config('app.env') != 'demo')
        <li><a href="{{ action('\Modules\Superadmin\Http\Controllers\PricingController@index') }}">@lang('superadmin::lang.pricing')</a></li>
        @endif
        @if(Route::has('repair-status'))
        <li>
          <a href="{{ action('\Modules\Repair\Http\Controllers\CustomerRepairStatusController@index') }}">
            @lang('repair::lang.repair_status')
          </a>
        </li>
        @endif
      </ul>
      <ul class="nav navbar-nav navbar-right">
        @if (Route::has('login'))
            @if(!Auth::check())
                <li><a href="{{ route('login') }}">@lang('lang_v1.login')</a></li>
                @if(config('constants.allow_registration'))
                    <li><a href="{{ route('business.getRegister') }}">@lang('lang_v1.register')</a></li>
                @endif
            @endif
        @endif
      </ul>
    </div><!-- nav-collapse -->
  </div>
</nav>