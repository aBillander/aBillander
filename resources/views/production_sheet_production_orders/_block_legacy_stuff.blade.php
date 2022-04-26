


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
            <div class="panel panel-success" id="panel_production_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-cubes"></i> &nbsp; <strong>{!! l('Production Orders &#58&#58 Finished') !!}</strong> ({{ $sheet->productionorders->where('procurement_type', 'manufacture')->count() }})</h3>
               </div>
                    @include('production_sheet_production_orders._panel_production_orders', ['procurement_type' => 'manufacture'])
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
                  <h3 class="panel-title"><i class="fa fa-cube"></i> &nbsp; {!! l('Production Orders &#58&#58 Assemblies') !!} - <strong>{{ AbiConfiguration::get('ASSEMBLY_GROUP_'.$i.'_TAG') }}</strong> [{{ AbiConfiguration::get('ASSEMBLY_GROUP_'.$i) }}] - ({{ $sheet->productionorders->where('schedule_sort_order', AbiConfiguration::get('ASSEMBLY_GROUP_'.$i))->count() }})</h3>
               </div>
                    @include('production_sheet_production_orders._panel_production_orders_assemblies', ['schedule_sort_order' => AbiConfiguration::get('ASSEMBLY_GROUP_'.$i)])
            </div>
      </div>
   </div>
@endfor

</div>


@include('production_sheets._modal_production_order_form')

