
<div id="panel_document"> 

<div class="panel with-nav-tabs panel-info" id="panel_update_order">

   <div class="panel-heading">
      <!-- h3 class="panel-title collapsed" data-toggle="collapse" data-target="#header_data">{{ l('Header Data') }} :: <span class="label label-warning" title="{ { l ('O rder Date') } }">{{ $document->document_date_form }}</span> - <span class="label label-info" title="{{ l('Delivery Date') }}">{{ $document->delivery_date_form ?? ' -- / -- / -- '}}</span></h3 -->

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab">{{ l('Header Data') }}</a></li>
                            <li><a href="#tab2default" data-toggle="tab">{{ l('Lines') }}</a></li>
                            <li><a href="#tab3default" data-toggle="tab">{{ l('Profitability') }}</a></li>

@if ( $document->status != 'closed' )
                            <li><a href="#tab4default" data-toggle="tab">{{ l('Availability') }}</a></li>
@endif

                            <!-- li><a href="#tab5default" data-toggle="tab">{{ l('Payments') }}</a></li -->
                            <!-- li class="dropdown">
                                <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#tab4default" data-toggle="tab">Default 4</a></li>
                                    <li><a href="#tab5default" data-toggle="tab">Default 5</a></li>
                                </ul>
                            </li -->
                            <li class="pull-right">


                        <h4 style="margin-right: 15px;">
                            <span class="label label-warning" title="{{ l('Document Date') }}">{{ $document->document_date_form }}</span> - 
                            <span class="label label-info" title="{{ l('Delivery Date') }}">{{ $document->delivery_date_form ?? ' -- / -- / -- '}}</span>
                        </h4>

                            </li>
                        </ul>

   </div>

  <div class="tab-content">
      <div class="tab-pane fade in active" id="tab1default">
                
                @include($view_path.'._tab_edit_header')

      </div>
      <div class="tab-pane fade" id="tab2default">
                
                @include($view_path.'._tab_edit_lines')

      </div>
      <div class="tab-pane fade" id="tab3default">
                
                @include($view_path.'._tab_profitability')

      </div>

@if ( $document->status != 'closed' )
      <div class="tab-pane fade" id="tab4default">
                
                @include($view_path.'._tab_availability')

      </div>
@endif

      <!-- div class="tab-pane fade" id="tab5default">
                
                @ include($view_path.'._tab_edit_payments')

      </div -->
      <!-- div class="tab-pane fade" id="tab4default">
                Default 4
      </div>
      <div class="tab-pane fade" id="tab5default">
                Default 5
      </div -->
  </div>


</div>    <!-- div class="panel panel-info" id="panel_update_order" ENDS -->


</div>



{{-- *************************************** --}}



@section('styles')    @parent

{{-- 
 - Fix drop down button menu scroll
 - https://stackoverflow.com/questions/26018756/bootstrap-button-drop-down-inside-responsive-table-not-visible-because-of-scroll
--}}

<style>
    .table-responsive {
      overflow-x: visible !important;
      overflow-y: visible !important;
    }
</style>

@endsection


@section('scripts')    @parent

    @include($view_path.'.js.document')

    @include($view_path.'.js.document_service_lines')

    @include($view_path.'.js.document_comment_lines')

    @include($view_path.'.js.document_shipping_cost_line')

@if ( AbiConfiguration::isTrue('ENABLE_LOTS') )

    @include($view_path.'.js.document_line_lots')

@endif


<script>

  $(document).ready(function() {

    // Jump to tab
    var myurl = 'tab2default';
    $("a[href$='"+myurl+"']:first")[0].click();
//    $("a[href$='tab2default']").css("background-color", "yellow");

  });
  
</script>

@endsection


@include($view_path.'.plugins.document_plugins')
