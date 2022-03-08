
<table class="head container">

    <tr>

        <td class="shop-info">

            <div class="shop-name"><h1 style="margin-top: 0mm; margin-bottom: 1mm;">Hoja de Producción :: Resumen de Producción</h1></div>

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
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'cubes', 'section_name' =>  l('Production Orders &#58&#58 Finished')])

        @include('production_sheets.reports.production_sheets._block_production_orders', ['procurement_type' => 'manufacture'])


<br /><br />
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'wrench', 'section_name' =>  l('Tool Requirements')])

        @include('production_sheets.reports.production_sheets._block_tool_requirements')


<br /><br />
        

        @include('production_sheets.reports.production_orders._section', ['section_icon' => 'gift', 'section_name' =>  l('Packaging Requirements')])

        @include('production_sheets.reports.production_sheets._block_packaging_requirements')

