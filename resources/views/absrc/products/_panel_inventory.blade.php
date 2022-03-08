
<div class="panel with-nav-tabs panel-success" id="panel_inventory">

   <div class="panel-heading">
      <!-- h3 class="panel-title">{{ l('Stocks') }}</h3 -->


                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab">{{ l('Stocks') }}</a></li>
                            <li><a href="#tab2default_m" data-toggle="tab">{{ l('Stock Movements') }}</a></li>
                            <li><a href="#tab3default_p" data-toggle="tab">{{ l('Pending Movements') }}</a></li>

                            <!-- li><a href="#tab4default" data-toggle="tab">{{ l('Availability') }}</a></li>
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#tab4default" data-toggle="tab">Default 4</a></li>
                                    <li><a href="#tab5default" data-toggle="tab">Default 5</a></li>
                                </ul>
                            </li -->
                        </ul>

   </div>

  <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default">
                
                @include('absrc.products._tab_stock')

      </div>
      <div class="tab-pane fade" id="tab2default_m">
                
                @include('absrc.products._tab_stock_movements')

      </div>
      <div class="tab-pane fade" id="tab3default_p">
                
                @include('absrc.products._tab_pending_movements')

      </div>
      <!-- div class="tab-pane fade" id="tab4default">
                
                @ include('absrc.products._tab_availability')

      </div>
      <div class="tab-pane fade" id="tab4default">
                Default 4
      </div>
      <div class="tab-pane fade" id="tab5default">
                Default 5
      </div -->
  </div>





</div>    <!-- div class="panel with-nav-tabs panel-primary" id="panel_inventory" ENDS -->



@section('scripts')     @parent

<script type="text/javascript">

</script>

@endsection


@section('styles') @parent

<link href="{{ asset('assets/theme/css/nav-tabs.css') }}" rel="stylesheet" type="text/css"/>

@endsection
