
<!-- body class="login-page" style="background: white" -->

    <div>
        <div class="row">
            <div class="col-xs-7">
                <h3 style="margin-top: 0px;">Semielaborados Previos</h3>
                <h4 style="color: #dd4814;">Centro de Trabajo: <strong>{{ $work_center->name }}</strong></h4>
                <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>

                <br>
            </div>

            <div class="col-xs-4">
                <img class="ximg-rounded" height="45" style="float:right" src="{{ asset('assets/theme/images/company_logo.png') }}" alt="logo">

                <!-- img class="navbar-brand img-rounded" src="http://localhost/enatural/public/assets/theme/images/company_logo.png" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;" height="40" -->
            </div>
        </div>

        <div style="margin-bottom: 0px">&nbsp;</div>


@for ($i = 0; $i < ( \App\Configuration::get('ASSEMBLY_GROUPS') - 1 ); $i++)        

        <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-success" id="panel_production_orders_assemblies_{{ $i }}">
                   <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-cube"></i> &nbsp; {!! l('Production Orders &#58&#58 Assemblies') !!} - <strong>{{ \App\Configuration::get('ASSEMBLY_GROUP_'.$i.'_TAG') }}</strong></h3>
                   </div>

                        @include('production_sheets.reports.preassemblies._block_production_orders_assemblies', ['schedule_sort_order' => \App\Configuration::get('ASSEMBLY_GROUP_'.$i)])

                </div>
            </div>

        </div><!-- div class="row" -->

@endfor

<!-- /body -->
