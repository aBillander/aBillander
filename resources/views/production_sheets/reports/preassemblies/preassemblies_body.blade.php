
<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name"><h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: Semielaborados Previos</h1></div>

            <div class="shop-name"><h3 style="font-weight: normal;color: #dd4814;">Centro de Trabajo: <strong>{{ $work_center->name }}</strong></div>

            <div class="shop-address" style="margin-top: 2mm;">
                        <strong>Fecha:</strong> {{ abi_date_form_short($sheet->due_date) }} <br>
                
            </div>

        </td>

        <td class="header">


        @if ($img = AbiContext::getContext()->company->company_logo)

            <img class="ximg-rounded" height="45" style="float:right" src="{{ URL::to( AbiCompany::imagesPath() . $img ) }}" >

        @endif


        <div class="banner">
        </div>

        </td>

    </tr>

</table>

        <!-- div style="margin-bottom: 0px">&nbsp;</div -->


@for ($i = 0; $i < ( AbiConfiguration::get('ASSEMBLY_GROUPS') - 1 ); $i++)        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'cube', 'section_name' =>  l('Production Orders &#58&#58 Assemblies').' - '. AbiConfiguration::get('ASSEMBLY_GROUP_'.$i.'_TAG')])


        @include('production_sheets.reports.preassemblies._block_production_orders_assemblies', ['schedule_sort_order' => AbiConfiguration::get('ASSEMBLY_GROUP_'.$i)])


@endfor

<!-- /body -->
