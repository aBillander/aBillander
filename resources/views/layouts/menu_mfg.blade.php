
@if ( AbiConfiguration::isTrue('ENABLE_MANUFACTURING') )
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cubes"></i> {{l('Manufacturing', [], 'layouts')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <li>
                            <a href="{{ URL::to('productionsheets') }}">
                                 {{l('Production Sheets', [], 'layouts')}}
                            </a>
                        </li>

                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('productionorders') }}">
                                 {{l('Production Orders', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('/helferin/home/mfg') }}">
                                 <i class="fa fa-cubes text-success"></i>
                                 {{l('Reports', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>

                         <li>
                            <a href="{{ URL::to('deliverysheets') }}">
                                 <i class="fa fa-truck text-info"></i> 
                                 {{l('Delivery Sheets', [], 'layouts')}}
                            </a>
                        </li>

                         <li>
                            <a href="{{ URL::to('deliveryroutes') }}">
                                 <i class="fa fa-map-marker text-danger"></i> 
                                 {{l('Delivery Routes', [], 'layouts')}}
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                </li>
@endif
