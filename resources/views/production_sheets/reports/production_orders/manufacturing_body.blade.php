
@php

// $company = \App\Company::find(2);

// espelta
$qty_10601 = [];
$key_1000_1010 = '1000+1010';
$key_1001_1011 = '1001+1011';
$key_1002_1012 = '1002+1012';

$qty_20001 = [];
// $key_1001_1011 = '1001+1011';

$qty_20601 = [];
// $key_1002_1012 = '1002+1012';



// trigo
$qty_10709 = [];
$key_1003_1013 = '1003+1013';
$key_1004_1014 = '1004+1014';

$qty_20601 = [];
// $key_1004_1014 = '1004+1014';



// centeno

$key_1006_1016 = '1006+1016';

$qty_20101 = [];
// $key_1006_1016 = '1006+1016';




// combi

$key_1005_1015 = '1005+1015';

$qty_20003 = [];
$qty_20100 = [];
// $key_1005_1015 = '1005+1015';


// maiz

$qty_40102 = [];
$key_4002 = '4002';
$key_4012 = '4012';
$key_4006 = '4006';

$qty_20001 = [];
// $key_4012 = '4012';

$qty_20001 = [];
// $key_4006 = '4006';



@endphp

<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name"><h1 style="margin-top: 0mm; margin-bottom: 1mm;">Orden de Fabricación :: {{ $family['label'] }}</h1></div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;">Centro de Trabajo: <strong>{{ $work_center->name }}</strong></div>

            <div class="shop-address" style="margin-top: 2mm;">
                        <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>
                
            </div>

        </td>

        <td class="header">


        @if ($img = \App\Context::getContext()->company->company_logo)

            <img class="ximg-rounded" height="45" style="float:right" src="{{ URL::to( \App\Company::imagesPath() . $img ) }}" >

        @endif


        <div class="banner">
        </div>

        </td>

    </tr>

</table>


        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'cubes', 'section_name' => l('Production Orders &#58&#58 Finished')])


        @include('production_sheets.reports.production_orders._block_finished_short', ['procurement_type' => 'manufacture'])


        <div style="margin-bottom: 0px">&nbsp;</div>


@for ($i = 2; $i < ( \App\Configuration::get('ASSEMBLY_GROUPS') ); $i++)


        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'cube', 'section_name' => l('Production Orders &#58&#58 Assemblies').' - '. \App\Configuration::get('ASSEMBLY_GROUP_'.$i.'_TAG')])

        @include('production_sheets.reports.production_orders._block_assemblies_short', ['schedule_sort_order' => \App\Configuration::get('ASSEMBLY_GROUP_'.$i)])

@endfor


        <div style="margin-bottom: 0px">&nbsp;</div>


{{--



<!-- body class="login-page" style="background: white" -->

    <div>
        <div class="row">
            <div class="col-xs-7">
                <h3 style="margin-top: 0px;">Orden de Fabricación :: Espelta</h3>
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
        

        <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-success" id="panel_production_orders">
                   <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-cubes"></i> &nbsp; <strong>{!! l('Production Orders &#58&#58 Finished') !!}</strong></h3>
                   </div>

                        @include('production_sheets.reports.production_orders._block_finished', ['procurement_type' => 'manufacture'])

                </div>
            </div>

        </div><!-- div class="row" -->

        <div style="margin-bottom: 0px">&nbsp;</div>


@for ($i = 2; $i < ( \App\Configuration::get('ASSEMBLY_GROUPS') ); $i++)        

        <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-success" id="panel_production_orders_assemblies_{{ $i }}">
                   <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-cube"></i> &nbsp; {!! l('Production Orders &#58&#58 Assemblies') !!} - <strong>{{ \App\Configuration::get('ASSEMBLY_GROUP_'.$i.'_TAG') }}</strong></h3>
                   </div>

                        @include('production_sheets.reports.production_orders._block_assemblies', ['schedule_sort_order' => \App\Configuration::get('ASSEMBLY_GROUP_'.$i)])

                </div>
            </div>

        </div><!-- div class="row" -->

@endfor

<!-- /body -->

--}}

{{-- Custom Block --}}


        @includeIf('production_sheets.reports.production_orders._block_custom_short_'.$family['key'], ['procurement_type' => 'manufacture'])

@if ( \App\Configuration::isTrue('MANUFACTURING_PRINT_DEBUG') )

        <hr />
        Productos: {{ implode(', ', $family['references']) }}<br />
        Semielaborados Directos: {{ implode(', ', $family['assemblies']) }}

@endif