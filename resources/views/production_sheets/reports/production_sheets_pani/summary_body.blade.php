
<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name"><h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: Resumen de Producción</h1></div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;">Centro de Trabajo: <strong>{{ $work_center->name }}</strong> :: <strong>PANI</strong></div>

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

        <!-- div style="margin-bottom: 0px">&nbsp;</div -->
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'cubes', 'section_name' =>  l('Production Orders &#58&#58 Finished')])

        @include('production_sheets.reports.production_sheets_pani._block_production_orders', ['procurement_type' => 'manufacture'])


<br /><br />
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'wrench', 'section_name' =>  l('Tool Requirements')])

        @include('production_sheets.reports.production_sheets_pani._block_tool_requirements')


<br /><br />
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'bars', 'section_name' =>  l('Tray Requirements')])

        @include('production_sheets.reports.production_sheets_pani._block_tray_requirements')


<br /><br />

@php

// Masas Madre

$i = 0;

@endphp
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'cube', 'section_name' =>  l('Semi-Elaborados').' - '. \App\Configuration::get('ASSEMBLY_GROUP_'.$i.'_TAG')])

        @include('production_sheets.reports.production_sheets_pani._block_assemblies_requirements', ['schedule_sort_order' => \App\Configuration::get('ASSEMBLY_GROUP_'.$i)])

{{--
<br /><br />
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'gift', 'section_name' =>  l('Packaging Requirements')])

        @include('production_sheets.reports.production_sheets._block_packaging_requirements')

--}}