
<div class="with-nav-tabs panel panel-info" id="panel_sales"> 

   <div class="panel-heading">
      <!-- h3 class="panel-title">{{ l('Sales') }}</h3 -->

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default_s" data-toggle="tab">{{ l('Sales') }}</a></li>
                            <li><a href="#tab2default_s" data-toggle="tab">{{ l('Recent Sales') }}</a></li>
                            <!-- li><a href="#tab3default_s" data-toggle="tab">{{ l('Pending Movements') }}</a></li>

                            <li><a href="#tab4default_s" data-toggle="tab">{{ l('Availability') }}</a></li>
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#tab4default_s" data-toggle="tab">Default 4</a></li>
                                    <li><a href="#tab5default_s" data-toggle="tab">Default 5</a></li>
                                </ul>
                            </li -->
                        </ul>

   </div>

  <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default_s">
                
                @include('absrc.products._tab_sales_data')

      </div>
      <div class="tab-pane fade" id="tab2default_s">
                
                @include('absrc.products._tab_recent_sales')

      </div>
      <!-- div class="tab-pane fade" id="tab3default_s">
                
                @ include('absrc.products._tab_pending_movements')

      </div>
      <div class="tab-pane fade" id="tab4default_s">
                
                @ include('absrc.products._tab_availability')

      </div>
      <div class="tab-pane fade" id="tab4default_s">
                Default 4
      </div>
      <div class="tab-pane fade" id="tab5default_s">
                Default 5
      </div -->
  </div>


</div>
