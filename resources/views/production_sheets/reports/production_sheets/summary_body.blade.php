
<!-- body class="login-page" style="background: white" -->

    <div>
        <div class="row">
            <div class="col-xs-7">
                <h3 style="margin-top: 0px;">Resumen de Producción</h3>
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

                        @include('production_sheets.reports.production_sheets._block_production_orders', ['procurement_type' => 'manufacture'])

                </div>
            </div>

        </div><!-- div class="row" -->

       <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-info" id="panel_tool_requirements">
                   <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-wrench"></i> &nbsp; {{ l('Tool Requirements') }}</h3>
                   </div>

                        @include('production_sheets.reports.production_sheets._block_tool_requirements')

                </div>
          </div>

       </div>

       <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-warning" id="panel_packaging_requirements">
                   <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-gift"></i> &nbsp; {{ l('Packaging Requirements') }}</h3>
                   </div>
                        
                        @include('production_sheets.reports.production_sheets._block_packaging_requirements')

                </div>
          </div>

       </div>


{{--



@for ($i = 0; $i < \App\Configuration::get('ASSEMBLY_GROUPS'); $i++)

       @continue
       <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-success" id="panel_production_orders_assemblies">
                   <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-cube"></i> &nbsp; {!! l('Production Orders &#58&#58 Assemblies') !!} - <strong>{{ \App\Configuration::get('ASSEMBLY_GROUP_'.$i.'_TAG') }}</strong></h3>
                   </div>

                        @include('production_sheets.reports.production_sheets._block_production_orders_assemblies', ['schedule_sort_order' => \App\Configuration::get('ASSEMBLY_GROUP_'.$i)])
                </div>
          </div>

       </div>

@endfor




        <div class="row">
            <div class="col-xs-6">
                <h4>To:</h4>
                <address>
                    <strong>Andre Madarang</strong><br>
                    <span>andre@andre.com</span> <br>
                    <span>123 Address St.</span>
                </address>
            </div>

            <div class="col-xs-5">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <th>Invoice Num:</th>
                            <td class="text-right">56</td>
                        </tr>
                        <tr>
                            <th> Invoice Date: </th>
                            <td class="text-right">Oct 1, 2018</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 0px">&nbsp;</div>

                <table style="width: 100%; margin-bottom: 20px">
                    <tbody>
                        <tr class="well" style="padding: 5px">
                            <th style="padding: 5px"><div> Balance Due (CAD) </div></th>
                            <td style="padding: 5px" class="text-right"><strong> $600 </strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <table class="table">
            <thead style="background: #F5F5F5;">
                <tr>
                    <th>Item List</th>
                    <th></th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div><strong>Service</strong></div>
                        <p>Description here. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Incidunt maiores placeat similique nisi. Nisi ratione, molestias exercitationem illo reiciendis cumque?</p></td>
                        <td></td>
                        <td class="text-right">$600</td>
                </tr>
                <tr>
                    <td><div><strong>Service</strong></div>
                        <p>Description here. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Incidunt maiores placeat similique nisi. Nisi ratione, molestias exercitationem illo reiciendis cumque?</p></td>
                        <td></td>
                        <td class="text-right">$600</td>
                </tr>
            </tbody>
        </table>

            <div class="row">
                <div class="col-xs-6"></div>
                <div class="col-xs-5">
                    <table style="width: 100%">
                        <tbody>
                            <tr class="well" style="padding: 5px">
                                <th style="padding: 5px"><div> Balance Due (CAD) </div></th>
                                <td style="padding: 5px" class="text-right"><strong> $600 </strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <div class="row">
                <div class="col-xs-8 invbody-terms">
                    Thank you for your business. <br>
                    <br>
                    <h4>Payment Terms</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad eius quia, aut doloremque, voluptatibus quam ipsa sit sed enim nam dicta. Soluta eaque rem necessitatibus commodi, autem facilis iusto impedit!</p>
                </div>
            </div>
        </div>


--}}



<!-- /body -->
