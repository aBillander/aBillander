
@if ( 1 || AbiConfiguration::isTrue('ENABLE_MCRM') )
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> {!! l('microCRM', [], 'layouts') !!} <span class="caret"></span></a>
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
