@extends('layouts.master')

@section('title') {{ l('Production Sheets - Show') }} @parent @endsection


@section('content')

<div class="row hide" id="cssload">
    <div class="col-md-12">

{{--
<div class="cssload-dots">
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
  <div class="cssload-dot"></div>
</div>

<svg version="1.1" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <filter id="goo">
      <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="12" ></feGaussianBlur>
      <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0 0 0 1 0 0 0 0 0 18 -7" result="goo" ></feColorMatrix>
      <!--<feBlend in2="goo" in="SourceGraphic" result="mix" ></feBlend>-->
    </filter>
  </defs>
</svg>
--}}

<div class="page-header">
    <h2>
        <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheets') }}</a> <span style="color: #cccccc;">::</span> <span title="{{ $sheet->name }}">{{ abi_date_form_short($sheet->due_date) }}</span>

        <a href="" class="btn btn-success disabled" onclick="return false;" style="margin-left: 72px;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> {{ l('Processing...', 'layouts') }}</a>

    </h2>     
</div>

    </div>
</div> 



<div class="row" id="content-header">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

@if( AbiConfiguration::isTrue('ENABLE_LOTS') && ($sheet->nbr_customerorders() > 0) )
        <a href="{{ URL::to('productionsheets/'.$sheet->id.'/assign/lots') }}" class="btn btn-grey" onclick="loadingpage();"><i class="fa fa-window-restore"></i> {{ l('Assign Lots to Orders') }}</a>
@endif

        <a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}" class=" hide  btn btn-success" onclick="loadingpage();"><i class="fa fa-cog"></i> {{ l('Update Sheet') }}</a>


<div class="btn-group">
  <a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}" class="btn btn-success xdropdown-toggle" xdata-toggle="dropdown" xaria-expanded="false" onclick="loadingpage();"><i class="fa fa-cog"></i> {{ l('Update Sheet') }}</a>
  <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></a>

  <ul class="dropdown-menu">
    <li><a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}?mrp_type=onorder" xclass="btn btn-success" onclick="loadingpage();"><i class="fa fa-cog"></i> {{ l('Solo Bajo Pedido') }}</a></li>
    
    <li><a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}?mrp_type=reorder" xclass="btn btn-success" onclick="loadingpage();"><i class="fa fa-cog"></i> {{ l('Solo Punto de Pedido') }}</a></li>
    
    <!-- li><a href="{{ URL::to('productionsheets/'.$sheet->id.'/calculate') }}?mrp_type=all" xclass="btn btn-success" onclick="loadingpage();"><i class="fa fa-cog"></i> {{ l('Todos') }}</a></li -->
    
    <!-- li><a href="#">Something else here</a></li>
    <li class="divider"></li -->
  </ul>
</div>


                <div class="btn-group">
                    <a href="#" class="btn xbtn-sm btn-info dropdown-toggle" data-toggle="dropdown" title="{{l('Go to', 'layouts')}}"><i class="fa fa-mail-forward"></i> &nbsp; <b>{{l('Go to', 'layouts')}}</b> &nbsp; <span class="caret"></span></a>
                    <ul class="dropdown-menu  xpull-right">
                      <li><a href="{{ route('productionsheet.stock', [$sheet->id]) }}"><i class="fa fa-th text-warning"></i> {{l('Stock Analysis')}}</a></li>

                      <li class="divider"></li>
                      <li><a href="{{ route('productionsheet.orders', [$sheet->id]) }}"><i class="fa fa-shopping-bag"></i> {{l('Customer Orders')}}</a></li>
                      <li><a href="{{ route('productionsheet.shippingslips', [$sheet->id]) }}"><i class="fa fa-truck"></i> {{l('Customer Shipping Slips')}}</a></li>
                      <li><a href="{{ route('productionsheet.invoices', [$sheet->id]) }}"><i class="fa fa-money"></i> {{ l('Customer Invoices') }}</a></li>

                      <li><a href="{{ route('productionsheet.vouchers', [$sheet->id]) }}"><i class="fa fa-credit-card text-info"></i> {{ l('Customer Vouchers') }}</a></li>

                      <li class="divider"></li>
                      <li><a href="{{ route('productionsheet.tourline', [$sheet->id]) }}"><img src="{{ \App\Helpers\TourlineExcel::getTourlineLogoUrl( ) }}" height="20" /> &nbsp;{{l('Hoja de Envío')}}</a></li>
                      <li><a href="{{ route('productionsheet.deliveryroute', [$sheet->id, 1]) }}"><i class="fa fa-map-o"></i> {{l('Hoja de Reparto')}}: Sevilla</a></li>

                      <li class="divider"></li>
                      <li><a href="{{ route('productionsheet.productionorders', [$sheet->id]) }}"><i class="fa fa-cubes"></i> {{l('Production Orders')}}</a></li>
                    </ul>
                </div>



        <!-- a href="{{ route('productionsheet.orders', [$sheet->id]) }}" class="btn btn-blue" stile="margin-left: 32px; margin-right: 32px; "><i class="fa fa-shopping-bag"></i> {{ l('Customer Orders') }}</a -->

        <a href="{{ URL::to('productionsheets') }}" class="btn xbtn-sm btn-default" title="{{ l('Back to Production Sheets') }}"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>


                <a id="btn1" href="#myHelpModal" class="btn btn-sm btn-behance" xdata-backdrop="false" data-toggle="modal" title="{{l('Help', [], 'layouts')}}"> <i class="fa fa-life-saver"></i>  {{l('Help', [], 'layouts')}}</a>

    </div>
    <h2>
        <a href="{{ URL::to('productionsheets') }}">{{ l('Production Sheets') }}</a> <span style="color: #cccccc;">::</span> <span title="{{ $sheet->name }}">{{ abi_date_form_short($sheet->due_date) }}</span>

         <button type="button" class="btn btn-xs btn-success" xdata-toggle="popover" xdata-placement="right" 
               title="{{ l('Type') }}: {{ $productionsheet_typeList[$sheet->type] }}" 
               xdata-content="
                                        BrIF: GB458794289000<br />" 
               xdata-original-title="Datos de Facturación">
@if( $sheet->type == 'onorder' )
                        <i class="fa fa-user"></i>
@elseif( $sheet->type == 'reorder' )
                        <i class="fa fa-cubes"></i>
@else
                        <i class="fa fa-warning alert-danger"></i>
@endif
         </button>
         
         <a class="btn btn-xs btn-warning" href="{{ URL::to('productionsheets/' . $sheet->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>


        @if ($sheet->is_dirty AND 0)
              <button type="button" class="btn btn-sm btn-danger" title="{{l('Need Update')}}">
                  <i class="fa fa-hand-stop-o"></i>
              </button>
        @endif
    </h2>        
</div>

    </div>
</div> 


<div class="container-fluid" id="content-body">

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-info" id="panel_customer_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-user"></i> &nbsp; {{ l('Customer Orders') }} (<strong>{!! l('independent demand') !!}</strong>)

                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('La Demanda Independiente está formada por Productos terminados, y se representa mediante Pedidos de Clientes.') }}">
                                  <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                           </a>

                    <a class="btn btn-xs alert-warning pull-right" href=""  title="{{l('Hide / Show', [], 'layouts')}}" onclick="$('#div_customer_orders').toggle('slow');return false;"><i class="fa fa-window-close-o"></i></a>

                  </h3>
               </div>
                    @include('production_sheets._panel_customer_orders')
            </div>
      </div>
   </div>


@if ( $sheet->type == 'reorder' )

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-info" id="panel_production_requirements">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-cubes"></i> &nbsp; {!! l('Production Requirements') !!} (<strong>{!! l('dependent demand') !!}</strong>) 

                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Estos Productos se incorporarán físicamente a la Demanda Independiente. Pueden considerarse como Semi-Elaborados que se fabrican antes de tener las Ordenes de Fabricación de los Productos terminados que forman la Demanda Independiente.') }}">
                                  <i class="fa fa-question-circle abi-help" style="color: #ff0084;"></i>
                           </a>

                    <a class="btn btn-xs alert-warning pull-right" href=""  title="{{l('Hide / Show', [], 'layouts')}}" onclick="$('#div_production_requirements').toggle('slow');return false;"><i class="fa fa-window-close-o"></i></a>

                  </h3>
               </div>
                    @include('production_sheets._panel_production_requirements')
            </div>
      </div>
   </div>
   
@endif

 
@if ( 0 && $sheet->productsNotScheduled()->count() )

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-danger" id="panel_not_scheduled_products">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-hand-stop-o"></i> &nbsp; {{ l('Finished Product Requirements') }}</h3>
               </div>
                    @include('production_sheets._panel_not_scheduled_products')
            </div>
      </div>
   </div>
   
@endif


                    @include('production_sheets._row_print_buttons')



   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-success" id="panel_production_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-cubes"></i> &nbsp; <strong>{!! l('Production Orders &#58&#58 Finished') !!}</strong></h3>
               </div>
                    @include('production_sheets._panel_production_orders', ['procurement_type' => '*', 'procurement_type_old' => 'manufacture'])
            </div>
      </div>
   </div>

@for ($i = 0; $i < AbiConfiguration::get('ASSEMBLY_GROUPS'); $i++)
   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-success" id="panel_production_orders_assemblies">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-cube"></i> &nbsp; {!! l('Production Orders &#58&#58 Assemblies') !!} - <strong>{{ AbiConfiguration::get('ASSEMBLY_GROUP_'.$i.'_TAG') }}</strong></h3>
               </div>
                    @include('production_sheets._panel_production_orders_assemblies', ['schedule_sort_order' => AbiConfiguration::get('ASSEMBLY_GROUP_'.$i)])
            </div>
      </div>
   </div>
@endfor

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-warning" id="panel_material_requirements">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-th-list"></i> &nbsp; {{ l('Material Requirements') }}</h3>
               </div>
                    @include('production_sheets._panel_material_requirements')
            </div>
      </div>
   </div>

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-warning" id="panel_packaging_requirements">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-gift"></i> &nbsp; {{ l('Packaging Requirements') }}</h3>
               </div>
                    @include('production_sheets._panel_packaging_requirements')
            </div>
      </div>
   </div>

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="list-group">
            <!-- a id="b_generales" href="" class="list-group-item active info" onClick="return false;">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Customer Orders') }}
            </a -->
         </div>
      </div>

      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-danger" id="panel_tool_requirements">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-wrench"></i> &nbsp; {{ l('Tool Requirements') }}</h3>
               </div>
                    @include('production_sheets._panel_tool_requirements')
            </div>
      </div>
   </div>

</div>



{{-- *************************************** --}}


{{--  --}}

    @include('production_sheets._modal_help')

{{--  --}}


@include('layouts/back_to_top_button')


@include('production_sheets._modal_production_order_form')


@include('production_sheets._modal_production_requirements_quick_form')

@include('production_sheets._modal_production_requirement_delete')



@endsection


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#cssload").hide();
});

function loadingpage()
{
    $("#content-header").hide('slow');
    $("#content-body").hide('slow');

   $("#cssload").hide();
   $("#cssload").removeClass('hide');
   $("#cssload").slideDown( "slow" );
}

</script>

@endsection



@section('styles') @parent

{{-- Loading... https://icons8.com/cssload/ -- } }

<style>
.cssload-dots {
  width: 0;
  height: 0;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  margin: auto;
  outline: 1px solid red;
  filter: url(#goo);
    -o-filter: url(#goo);
    -ms-filter: url(#goo);
    -webkit-filter: url(#goo);
    -moz-filter: url(#goo);
}

.cssload-dot {
  width: 0;
  height: 0;
  position: absolute;
  left: 0;
  top: 0;
}
.cssload-dot:before {
  content: "";
  width: 34px;
  height: 34px;
  border-radius: 49px;
  background: rgb(251,211,1);
  position: absolute;
  left: 50%;
  transform: translateY(0);
    -o-transform: translateY(0);
    -ms-transform: translateY(0);
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
  margin-left: -17.5px;
  margin-top: -17.5px;
}



.cssload-dot:nth-child(5):before {
  z-index: 100;
  width: 44.5px;
  height: 44.5px;
  margin-left: -21.75px;
  margin-top: -21.75px;
  animation: cssload-dot-colors 4.6s ease infinite;
    -o-animation: cssload-dot-colors 4.6s ease infinite;
    -ms-animation: cssload-dot-colors 4.6s ease infinite;
    -webkit-animation: cssload-dot-colors 4.6s ease infinite;
    -moz-animation: cssload-dot-colors 4.6s ease infinite;
}


.cssload-dot:nth-child(1) {
  animation: cssload-dot-rotate-1 4.6s 0s linear infinite;
    -o-animation: cssload-dot-rotate-1 4.6s 0s linear infinite;
    -ms-animation: cssload-dot-rotate-1 4.6s 0s linear infinite;
    -webkit-animation: cssload-dot-rotate-1 4.6s 0s linear infinite;
    -moz-animation: cssload-dot-rotate-1 4.6s 0s linear infinite;
}
.cssload-dot:nth-child(1):before {
  background-color: rgb(255,50,112);
  animation: cssload-dot-move 4.6s 0s ease infinite;
    -o-animation: cssload-dot-move 4.6s 0s ease infinite;
    -ms-animation: cssload-dot-move 4.6s 0s ease infinite;
    -webkit-animation: cssload-dot-move 4.6s 0s ease infinite;
    -moz-animation: cssload-dot-move 4.6s 0s ease infinite;
}

.cssload-dot:nth-child(2) {
  animation: cssload-dot-rotate-2 4.6s 1.15s linear infinite;
    -o-animation: cssload-dot-rotate-2 4.6s 1.15s linear infinite;
    -ms-animation: cssload-dot-rotate-2 4.6s 1.15s linear infinite;
    -webkit-animation: cssload-dot-rotate-2 4.6s 1.15s linear infinite;
    -moz-animation: cssload-dot-rotate-2 4.6s 1.15s linear infinite;
}
.cssload-dot:nth-child(2):before {
  background-color: rgb(32,139,241);
  animation: cssload-dot-move 4.6s 1.15s ease infinite;
    -o-animation: cssload-dot-move 4.6s 1.15s ease infinite;
    -ms-animation: cssload-dot-move 4.6s 1.15s ease infinite;
    -webkit-animation: cssload-dot-move 4.6s 1.15s ease infinite;
    -moz-animation: cssload-dot-move 4.6s 1.15s ease infinite;
}

.cssload-dot:nth-child(3) {
  animation: cssload-dot-rotate-3 4.6s 2.3s linear infinite;
    -o-animation: cssload-dot-rotate-3 4.6s 2.3s linear infinite;
    -ms-animation: cssload-dot-rotate-3 4.6s 2.3s linear infinite;
    -webkit-animation: cssload-dot-rotate-3 4.6s 2.3s linear infinite;
    -moz-animation: cssload-dot-rotate-3 4.6s 2.3s linear infinite;
}
.cssload-dot:nth-child(3):before {
  background-color: rgb(175,225,2);
  animation: cssload-dot-move 4.6s 2.3s ease infinite;
    -o-animation: cssload-dot-move 4.6s 2.3s ease infinite;
    -ms-animation: cssload-dot-move 4.6s 2.3s ease infinite;
    -webkit-animation: cssload-dot-move 4.6s 2.3s ease infinite;
    -moz-animation: cssload-dot-move 4.6s 2.3s ease infinite;
}

.cssload-dot:nth-child(4) {
  animation: cssload-dot-rotate-4 4.6s 3.45s linear infinite;
    -o-animation: cssload-dot-rotate-4 4.6s 3.45s linear infinite;
    -ms-animation: cssload-dot-rotate-4 4.6s 3.45s linear infinite;
    -webkit-animation: cssload-dot-rotate-4 4.6s 3.45s linear infinite;
    -moz-animation: cssload-dot-rotate-4 4.6s 3.45s linear infinite;
}
.cssload-dot:nth-child(4):before {
  background-color: rgb(251,211,1);
  animation: cssload-dot-move 4.6s 3.45s ease infinite;
    -o-animation: cssload-dot-move 4.6s 3.45s ease infinite;
    -ms-animation: cssload-dot-move 4.6s 3.45s ease infinite;
    -webkit-animation: cssload-dot-move 4.6s 3.45s ease infinite;
    -moz-animation: cssload-dot-move 4.6s 3.45s ease infinite;
}

@keyframes cssload-dot-move {
  0% {
    transform: translateY(0);
  }
  18%, 22% {
    transform: translateY(-68px);
  }
  40%, 100% {
    transform: translateY(0);
  }
}

@-o-keyframes cssload-dot-move {
  0% {
    -o-transform: translateY(0);
  }
  18%, 22% {
    -o-transform: translateY(-68px);
  }
  40%, 100% {
    -o-transform: translateY(0);
  }
}

@-ms-keyframes cssload-dot-move {
  0% {
    -ms-transform: translateY(0);
  }
  18%, 22% {
    -ms-transform: translateY(-68px);
  }
  40%, 100% {
    -ms-transform: translateY(0);
  }
}

@-webkit-keyframes cssload-dot-move {
  0% {
    -webkit-transform: translateY(0);
  }
  18%, 22% {
    -webkit-transform: translateY(-68px);
  }
  40%, 100% {
    -webkit-transform: translateY(0);
  }
}

@-moz-keyframes cssload-dot-move {
  0% {
    -moz-transform: translateY(0);
  }
  18%, 22% {
    -moz-transform: translateY(-68px);
  }
  40%, 100% {
    -moz-transform: translateY(0);
  }
}

@keyframes cssload-dot-colors {
  0% {
    background-color: rgb(251,211,1);
  }
  25% {
    background-color: rgb(255,50,112);
  }
  50% {
    background-color: rgb(32,139,241);
  }
  75% {
    background-color: rgb(175,225,2);
  }
  100% {
    background-color: rgb(251,211,1);
  }
}

@-o-keyframes cssload-dot-colors {
  0% {
    background-color: rgb(251,211,1);
  }
  25% {
    background-color: rgb(255,50,112);
  }
  50% {
    background-color: rgb(32,139,241);
  }
  75% {
    background-color: rgb(175,225,2);
  }
  100% {
    background-color: rgb(251,211,1);
  }
}

@-ms-keyframes cssload-dot-colors {
  0% {
    background-color: rgb(251,211,1);
  }
  25% {
    background-color: rgb(255,50,112);
  }
  50% {
    background-color: rgb(32,139,241);
  }
  75% {
    background-color: rgb(175,225,2);
  }
  100% {
    background-color: rgb(251,211,1);
  }
}

@-webkit-keyframes cssload-dot-colors {
  0% {
    background-color: rgb(251,211,1);
  }
  25% {
    background-color: rgb(255,50,112);
  }
  50% {
    background-color: rgb(32,139,241);
  }
  75% {
    background-color: rgb(175,225,2);
  }
  100% {
    background-color: rgb(251,211,1);
  }
}

@-moz-keyframes cssload-dot-colors {
  0% {
    background-color: rgb(251,211,1);
  }
  25% {
    background-color: rgb(255,50,112);
  }
  50% {
    background-color: rgb(32,139,241);
  }
  75% {
    background-color: rgb(175,225,2);
  }
  100% {
    background-color: rgb(251,211,1);
  }
}

@keyframes cssload-dot-rotate-1 {
  0% {
    transform: rotate(-105deg);
  }
  100% {
    transform: rotate(270deg);
  }
}

@-o-keyframes cssload-dot-rotate-1 {
  0% {
    -o-transform: rotate(-105deg);
  }
  100% {
    -o-transform: rotate(270deg);
  }
}

@-ms-keyframes cssload-dot-rotate-1 {
  0% {
    -ms-transform: rotate(-105deg);
  }
  100% {
    -ms-transform: rotate(270deg);
  }
}

@-webkit-keyframes cssload-dot-rotate-1 {
  0% {
    -webkit-transform: rotate(-105deg);
  }
  100% {
    -webkit-transform: rotate(270deg);
  }
}

@-moz-keyframes cssload-dot-rotate-1 {
  0% {
    -moz-transform: rotate(-105deg);
  }
  100% {
    -moz-transform: rotate(270deg);
  }
}

@keyframes cssload-dot-rotate-2 {
  0% {
    transform: rotate(165deg);
  }
  100% {
    transform: rotate(540deg);
  }
}

@-o-keyframes cssload-dot-rotate-2 {
  0% {
    -o-transform: rotate(165deg);
  }
  100% {
    -o-transform: rotate(540deg);
  }
}

@-ms-keyframes cssload-dot-rotate-2 {
  0% {
    -ms-transform: rotate(165deg);
  }
  100% {
    -ms-transform: rotate(540deg);
  }
}

@-webkit-keyframes cssload-dot-rotate-2 {
  0% {
    -webkit-transform: rotate(165deg);
  }
  100% {
    -webkit-transform: rotate(540deg);
  }
}

@-moz-keyframes cssload-dot-rotate-2 {
  0% {
    -moz-transform: rotate(165deg);
  }
  100% {
    -moz-transform: rotate(540deg);
  }
}

@keyframes cssload-dot-rotate-3 {
  0% {
    transform: rotate(435deg);
  }
  100% {
    transform: rotate(810deg);
  }
}

@-o-keyframes cssload-dot-rotate-3 {
  0% {
    -o-transform: rotate(435deg);
  }
  100% {
    -o-transform: rotate(810deg);
  }
}

@-ms-keyframes cssload-dot-rotate-3 {
  0% {
    -ms-transform: rotate(435deg);
  }
  100% {
    -ms-transform: rotate(810deg);
  }
}

@-webkit-keyframes cssload-dot-rotate-3 {
  0% {
    -webkit-transform: rotate(435deg);
  }
  100% {
    -webkit-transform: rotate(810deg);
  }
}

@-moz-keyframes cssload-dot-rotate-3 {
  0% {
    -moz-transform: rotate(435deg);
  }
  100% {
    -moz-transform: rotate(810deg);
  }
}

@keyframes cssload-dot-rotate-4 {
  0% {
    transform: rotate(705deg);
  }
  100% {
    transform: rotate(1080deg);
  }
}

@-o-keyframes cssload-dot-rotate-4 {
  0% {
    -o-transform: rotate(705deg);
  }
  100% {
    -o-transform: rotate(1080deg);
  }
}

@-ms-keyframes cssload-dot-rotate-4 {
  0% {
    -ms-transform: rotate(705deg);
  }
  100% {
    -ms-transform: rotate(1080deg);
  }
}

@-webkit-keyframes cssload-dot-rotate-4 {
  0% {
    -webkit-transform: rotate(705deg);
  }
  100% {
    -webkit-transform: rotate(1080deg);
  }
}

@-moz-keyframes cssload-dot-rotate-4 {
  0% {
    -moz-transform: rotate(705deg);
  }
  100% {
    -moz-transform: rotate(1080deg);
  }
}
</style>

--}}

@endsection
