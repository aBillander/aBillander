
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getFullName() }}  
@if ( \App\Models\Models\Todo::pending() )
                    <span id="nbr_todos" class="badge" title="{{l('Pending Todos', [], 'layouts')}}">
                        {{ \App\Models\Models\Todo::pending() }}
                    </span> 
@endif
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ AbiConfiguration::get('URL_ABILLANDER_DOCS') }}" target="_blank">
                                 <i class="fa fa-life-saver text-info"></i> {{l('Documentation', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
{{--
                            <a data-target="#feedbackForm" data-toggle="modal" onclick="return false;" href="">
--}}
                            <a href="{{ AbiConfiguration::get('URL_ABILLANDER_SUPPORT') }}" target="_blank">
                                 {{l('Support & feed-back', [], 'layouts')}}
                            </a>
                        </li>
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
{{--
                         <li>
                            <a href="{{ URL::to('helpcontents') }}">
                                 <i class="fa fa-life-saver text-info"></i> {{l('Help Contents', [], 'layouts')}}
                            </a>
                        </li>
--}}
                         <li>
                            <a href="{{ URL::to('companies') }}">
                                 {{l('Company', [], 'layouts')}}
                            </a>
                        </li>
                         <li>
                            <a href="{{ URL::to('banks') }}">
                                 {{l('Banks', [], 'layouts')}}
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
