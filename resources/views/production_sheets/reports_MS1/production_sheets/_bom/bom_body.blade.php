
<!-- body class="login-page" style="background: white" -->




<div class="panel panel-primary" id="panel_manufacturing_1">
   <div class="panel-heading">
      <h3 class="panel-title">BOM :: Lista de Materiales</h3>
   </div>
   <div class="panel-body">






          <div class="page-header" style="border-bottom: 0px solid #eeeeee;">
        <h3>
            <span style="color: #dd4814;">Lista de Materiales completa</span> <!-- span style="color: #cccccc;">/</span> [BOM]-ZZ -->
        </h3>        
    </div>

    <div id="div_bom_tree">
       <div class="">

    <table id="bom_tree" class="table table-hover tree" xclass="table table-hover tree tree-2 table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th class="text-left"></th>
                <th class="text-left"></th>
                <th class="text-left">Cantidad</th>
                <th class="text-left">Unidad de Medida</th>
                <th class="text-left">Merma (%)</th>
                <th class="text-left">Notas</th>
            </tr>
        </thead>
        <tbody>


    
            


            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
 
 
                        <tr class="treegrid-1 treegrid-expanded">
                <!-- td style="padding-left: 0px;">0 - 1 - 10</td -->
                <td><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span>[ 1_X ]</td>
                <td>XX</td>
                <td>2</td>
                <td>Unidad(es)</td>
                <td>0.00</td>
                <td class="text-center">
                </td>
            </tr>
 

            
      


                <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
 
 
                        <tr class="treegrid-2 treegrid-parent-1 treegrid-expanded">
                <!-- td style="padding-left: 36px;">1 - 2 - 10</td -->
                <td><span class="treegrid-indent"></span><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span>[&nbsp;1_Y&nbsp;]</td>
                <td>YY</td>
                <td>3</td>
                <td>Unidad(es)</td>
                <td>0.00</td>
                <td class="text-center">
                </td>
            </tr>
 

            
      


                <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
 
 
                        <tr class="treegrid-3 treegrid-parent-2">
                <!-- td style="padding-left: 72px;">2 - 3 - 10</td -->
                <td style="xwidth:1px; white-space: nowrap;"><span class="treegrid-indent"></span><span class="treegrid-indent"></span><span class="treegrid-expander"></span>[ SAL-C ]</td>
                <td>Sal</td>
                <td>3</td>
                <td>Cucharada rasa</td>
                <td>0.00</td>
                <td class="text-center">
                </td>
            </tr>
 

                        
                        <tr class="treegrid-4 treegrid-parent-2">
                <!-- td style="padding-left: 72px;">2 - 4 - 20</td -->
                <td style="xwidth:1px; white-space: nowrap;"><span class="treegrid-indent"></span><span class="treegrid-indent"></span><span class="treegrid-expander"></span>[SAL-C]</td>
                <td>Sal</td>
                <td>3</td>
                <td>Cucharada rasa</td>
                <td>0.00</td>
                <td class="text-center">
                </td>
            </tr>
 

                        
                        <tr class="treegrid-5 treegrid-parent-2">
                <!-- td style="padding-left: 72px;">2 - 5 - 30</td -->
                <td><span class="treegrid-indent"></span><span class="treegrid-indent"></span><span class="treegrid-expander"></span>[ 1007 ]</td>
                <td>Sal rara</td>
                <td>3</td>
                <td>Cucharada rasa</td>
                <td>0.00</td>
                <td class="text-center">
                </td>
            </tr>
 

                        
            
    

                        
            
    

                        
            
    


    

        </tbody>
    </table>

       </div>
    </div>








   </div>

   <div class="panel-footer text-right">
      <!-- button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; Guardar
      </button -->
   </div>

</div>





    <div>
        <div class="row">
            <div class="col-xs-7">
                <h3 style="margin-top: 0px;">Resumen de Producción</h3>
                <h4 style="color: #dd4814;">Obrador Pani</h4>
                <strong>Fecha:</strong> 12-04-2019 <br>

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
                  <h3 class="panel-title"><i class="fa fa-cubes"></i> &nbsp; <strong>Production Orders :: Finished</strong></h3>
               </div>
                    <div class="panel-body" id="div_production_orders">
   <div class="xtable-responsive">


<table id="sheets" class="table" style="border:0px #ffffff">
    <thead>
        <tr>
      <th>Referencia</th>
      <th>Nombre del Producto</th>
      <th>Cantidad</th>
      <th class="text-center">Unidad</th>
    </tr>
  </thead>
  <tbody>
      
    <tr>
      <td>1_Z</td>
      <td>ZZ</td>
      <td>3.00</td>
      <td class="text-center">ud</td>
    </tr>
      </tbody>
</table>


   </div>


</div><!-- div class="panel-body" -->

<!-- div class="panel-footer text-right">
</div -->



            </div>
      </div>




        </div>




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


<!-- /body -->
