<ul class="list-group">
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::welcome' ? 'active' : '' }}">
        <i class="fa fa-globe"></i>
        &nbsp; {{ __('installer::main.overview.welcome') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::license' ? 'active' : '' }}">
        <i class="fa fa-file"></i>
        &nbsp; {{ __('installer::main.overview.license') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::requirements' ? 'active' : '' }}">
        <i class="fa fa-server"></i>
        &nbsp; {{ __('installer::main.overview.requirements') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::configuration' ? 'active' : '' }}">
        <i class="fa fa-database"></i>
        &nbsp; {{ __('installer::main.overview.config') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::mail' ? 'active' : '' }}">
        <i class="fa fa-envelope"></i>
        &nbsp; {{ __('installer::main.overview.mail') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::install' ? 'active' : '' }}">
        <i class="fa fa-wrench"></i>
        &nbsp; {{ __('installer::main.overview.install') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::company' ? 'active' : '' }}">
        <i class="fa fa-building"></i>
        &nbsp; {{ __('installer::main.overview.company') }}
    </li>
    <li class="list-group-item {{ Route::currentRouteName() == 'installer::done' ? 'active' : '' }}">
        <i class="fa fa-star"></i>
        &nbsp; {{ __('installer::main.overview.done') }}
    </li>
</ul>
