<?php

?>
<!DOCTYPE html>
<html lang="es"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE">

        <title> Pedidos de Clientes - Modificar :: aBillander MFG   </title>

        <!-- Styles -->
        <link rel="shortcut icon" href="http://localhost/enatural/public/xtracon.png" type="image/x-icon">

        <link href="Pedidos%20de%20Clientes_files/bootstrap-united.css" rel="stylesheet" type="text/css">
        <link href="Pedidos%20de%20Clientes_files/extra-buttons.css" rel="stylesheet" type="text/css">
        <link href="Pedidos%20de%20Clientes_files/custom.css" rel="stylesheet" type="text/css">
        <link href="http://localhost/enatural/public/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
            

<style>
  .panel-heading h3:after {
      font-family:'FontAwesome';
      content:"\f077";
      float: right;
      xcolor: grey;
  }
  .panel-heading h3.collapsed:after {
      font-family:'FontAwesome';
      content:"\f078";
      float: right;
  }
</style>



  

  <link rel="stylesheet" href="Pedidos%20de%20Clientes_files/jquery-ui_002.css">

<style>

  .ui-autocomplete-loading{
    background: white url("http://localhost/enatural/public/assets/theme/images/ui-anim_basic_16x16.gif") right center no-repeat;
  }
  .loading{
    background: white url("http://localhost/enatural/public/assets/theme/images/ui-anim_basic_16x16.gif") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>




<link rel="stylesheet" href="Pedidos%20de%20Clientes_files/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

    </head>
    <body style="" class="">
        <div id="app">
            <nav class="navbar navbar-default" role="navigation" style="margin: 0px 0px 5px 0px;">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="http://localhost/enatural/public">
                <img class="navbar-brand img-rounded" src="Pedidos%20de%20Clientes_files/xtralogo.png" style="xposition: absolute; margin-top: -15px; padding: 7px; border-radius: 12px;" height="40">
            </a>
        </div>
        <div class="collapse navbar-collapse" role="navigation">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">

                
            


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Lara Customer <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                         <!-- li>
                            <a data-target="#contactForm" data-toggle="modal" onclick="return false;" href="">
                                 Soporte y feed-back
                            </a>
                        </li -->
                         <li>
                            <a data-target="#aboutLaraBillander" data-toggle="modal" onclick="return false;" href="">
                                 Acerca de ...
                            </a>
                        </li>
 
                        <li class="divider"></li>
                         <li>
                            <a href="http://bootswatch.com/3/united/" target="_blank">
                                 Plantilla BS3
                            </a>
                        </li>
                         <!-- li>
                            <a href="http://getbootstrap.com/components/" target="_blank">
                                 Glyphicons
                            </a>
                        </li -->
                         <li>
                            <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">
                                 Font-Awesome
                            </a>
                        </li>
                                                <li class="divider"></li>
                         <li>
                            <a href="http://localhost/enatural/public/configurations">
                                 Configuración
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/languages">
                                 Idiomas
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/currencies">
                                 Divisas
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/warehouses">
                                 Almacenes
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/salesreps">
                                 Agentes
                            </a>
                        </li>
                        
                         <li>
                            <a href="http://localhost/enatural/public/translations">
                                 Traducciones
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/sequences">
                                 Series de Documentos
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/templates">
                                 Plantillas de Documentos
                            </a>
                        </li>
                         <li>
                            <a href="http://localhost/enatural/public/users">
                                 Usuarios
                            </a>
                        </li>
                                                <li class="divider"></li>

                        <li>
                            <a href="javascript:void(0);" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> Salir
                            </a>

                            <form id="logout-form" action="http://localhost/enatural/public/logout" method="POST" style="display: none;">
                                <input name="_token" value="KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE" type="hidden">
                            </form>
                        </li>
                    </ul>
                </li>
                            </ul>
        </div>
    </div>
</nav>            <div class="container-fluid" style="margin: 10px 0px 10px 0px;"> 
                                <div class="row">
    <div class="col-md-12">
        <div class="page-header">
            
              <h2><xa href="http://localhost/enatural/public/customerorders">Nuevo Pedido</xa> <span style="color: #cccccc;"></span> 
             </h2>

        </div>
    </div>
</div> 

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-4 col-md-2 col-sm-3">

<div class="panel panel-primary" id="panel_update_order">

   <div class="panel-heading">
      <h3 class="panel-title" data-toggle="collapse" data-target="#header_data" aria-expanded="true">Buscar Producto</h3>
   </div>

   <div class="panel-body">

                  <div class="form-group col-lg-8 col-md-8 col-sm-8">
                     Producto                     <input class="form-control ui-autocomplete-input" id="line_autoproduct_name" onclick="this.select()" name="line_autoproduct_name" autocomplete="off" value="pan in" type="text">
                  </div>

                 <div class="form-group col-lg-4 col-md-2 col-sm-2">
                    Cantidad
                    <input class="form-control ui-autocomplete-input" xid="line_tax_percent" xname="line_tax_percent" value="1" type="text">
                 </div>


   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-plus"></i>
                     &nbsp; Añadir al Pedido
                  </button>
               </div>
</div>

<div class="panel panel-primary" id="panel_update_order">

   <div class="panel-heading">
      <h3 class="panel-title" data-toggle="collapse" data-target="#header_data" aria-expanded="true">Datos Generales</h3>
   </div>

   <div class="panel-body">
      <div class="row">

         <div class="form-group col-lg-6 col-md-2 col-sm-2 ">
            Dirección de Envío
            <select class="form-control" id="shipping_address_id" name="shipping_address_id"><option value="8" selected="selected">Dirección Principal</option></select>
            
         </div>

         <div class="form-group col-lg-6 col-md-2 col-sm-2 ">
            Forma de pago
                    <div  class="form-control">Domiciliado</div>
            
         </div>
      </div>

         <div class="form-group col-lg-12 col-md-6 col-sm-6 ">
            Notas
            <textarea class="form-control" id="shipping_conditions" rows="1" name="shipping_conditions" cols="50"></textarea>
            
         </div>
   </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; Confirmar Pedido
                  </button>
               </div>
</div>

      </div>


      
      <div class="col-lg-8 col-md-10 col-sm-9">

          <div id="panel_customer_order"> 

<div class="panel panel-primary" id="panel_update_order">

   <div class="panel-heading">
      <h3 class="panel-title" xdata-toggle="collapse" data-target="#header_data" aria-expanded="true">Líneas del pedido</h3>
   </div>
   <div id="header_data" class="panel-collapse collapse in" aria-expanded="true" style="">
    <form method="POST" action="http://localhost/enatural/public/customerorders/3" accept-charset="UTF-8" id="update_customer_order" name="update_customer_order" class="form"><input name="_method" value="PATCH" type="hidden"><input name="_token" value="KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE" type="hidden">


<!-- Order header -->

<input id="order_id" name="order_id" value="3" type="hidden">
<input id="customer_id" name="customer_id" value="3" type="hidden">
<input id="sales_equalization" name="sales_equalization" value="1" type="hidden">
<input id="invoicing_address_id" name="invoicing_address_id" value="8" type="hidden">
<input id="taxing_address_id" name="taxing_address_id" value="8" type="hidden">

               <div class="panel-body">       <div class="table-responsive">

    <table id="order_lines" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">Referencia</th>
               <th class="text-left">Descripción</th>
               <th class="text-right" xxwidth="90">Cantidad</th>

               <th class="text-right">Precio</th>

               <th class="text-right" xwidth="90">Total</th> 
               <th class="text-right" xwidth="90">Con IVA</th> 
               
               <!-- th class="text-right" xwidth="115">Impuesto</th -->

               <!-- th class="text-right">Line Total</th --> 
                <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody class="sortable ui-sortable">

                <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

                        <tr data-id="18" data-sort-order="10" class="ui-sortable-handle">
                <td>4003</td>
                <td>
                                Mollete artesano</td>
                <td class="text-right">2.00</td>
                <td class="text-right">100</td>
                <td class="text-right">200</td>
                <td class="text-right">220</td>
                <!-- td class="text-right">10</td -->
                <td class="text-center">
                </td>
                <td class="text-right">
                    <!-- a class="btn btn-sm btn-info" title="XXXXXS" onClick="loadcustomerorderlines();"><i class="fa fa-pencil"></i></a -->
                    
                    <a class="btn btn-sm btn-warning edit-order-line" data-id="18" title="Modificar" onclick="return false;"><i class="fa fa-pencil"></i></a>
                    
                    <a class="btn btn-sm btn-danger delete-order-line" data-id="18" title="Eliminar" data-content="Está a punto de borrar un registro ¿Está seguro?" data-title="[FAGESF] Fagodio Esforulante" onclick="return false;"><i class="fa fa-trash-o"></i></a>

                </td>
            </tr>
            
                        <tr data-id="23" data-sort-order="50" class="ui-sortable-handle">
                <td></td>
                <td>
                                  <i class="fa fa-truck abi-help" title="Coste de Envío"></i> 
                                Coste del Envío</td>
                <td class="text-right">1.00</td>
                <td class="text-right">10</td>
                <td class="text-right">10</td>
                <td class="text-right">12.1</td>
                <!-- td class="text-right">4</td -->
                <td class="text-center">
                </td>
                <td class="text-right">

                </td>
            </tr>
            
            
            
    
        </tbody>
    </table>

    <input name="next_line_sort_order" id="next_line_sort_order" value="70" type="hidden">

       </div>

               </div><!-- div class="panel-body" -->

               <!-- div class="panel-footer text-right">
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; Guardar
                  </button>
               </div -->

<!-- Order header ENDS -->


    </form>
   </div>

              <div class="panel-footer text-right">       </div>

</div>

<!-- Order Lines -->

<div id="msg-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>El registro se ha creado correctamente :: () </strong>
</div>

<div xid="panel_customer_order_lines" class="">





<div id="panel_customer_order_total" class="">
  
    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">Total</span> <!-- span style="color: #cccccc;">/</span>  -->
             
        </h3>        
    </div>

    <div xid="div_customer_order_total">
       <div class="table-responsive">

    <table id="order_total" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">Total Líneas</th> 

               <th class="text-left">Base Imponible</th>
               <th class="text-left">Impuestos</th>

               <th class="text-right">Total</th>
            </tr>
        </thead>


        <tbody>
            <tr>
                <td>210.00</td>
                <td>210.00</td>
                <td>22.10</td>
                <td class="text-right lead"><strong>239.10</strong></td>
            </tr>
        </tbody>
    </table>

       </div>
    </div>

</div>

</div>




<!-- Order Lines ENDS -->

</div>





      </div>
   </div>
</div>
                <div class="modal fade" id="aboutLaraBillander" tabindex="-1" role="dialog" aria-labelledby="myLaraBillander" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLaraBillander">Acerca de ...</h4>
            </div>
            <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <h4><a class="footer-logo" href="http://www.abillander.com/" target="new"><i class="fa fa-bolt"></i> Lar<span>aBillander</span></a> <span style="font-size: 14px;">by Lara Billander</span></h4>
                    <img src="Pedidos%20de%20Clientes_files/tumblr_m3z2ciodE01qh4utio1_500.jpg" title="Lara Billander :: The Girl with the Dragon Tattoo" class="center-block" alt="Lara Billander" width="150" height="176">
                <p>Versión 0.2.2</p>


              </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Continuar</button>

            </div>
        </div>
    </div>
</div>
                    

<div class="modal" id="modal_order_line" tabindex="-1" role="dialog" style="display: none;">
   <div class="modal-dialog modal-lg" xstyle="width: 99%; max-width: 1000px;">
      <div class="modal-content">

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="modal_order_line_Label">Add Line to Order</h4>
         </div>

         <div class="modal-body">

            <ul class="nav nav-tabs" id="nav_new_order_line">
               <li id="li_new_product" class="active"><a href="javascript:void(0);" id="tab_new_product">Producto codificado</a></li>
               <li id="li_new_service" class=""><a href="javascript:void(0);" id="tab_new_service">Servicio SIN codificar</a></li>
               <!-- li id="li_new_discount" ><a href="javascript:void(0);" id="tab_new_discount">Descuento</a></li>
               <li id="li_new_text_line" ><a href="javascript:void(0);" id="tab_new_text_line" >Línea de texto</a></li -->
            </ul>

                
                <input name="_token" value="KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE" type="hidden">
                <!-- input type="hidden" name="_token" value="{ { csrf_token() } }" -->
                <!-- input type="hidden" id="" -->
                <input id="line_id" name="line_id" value="" type="hidden">
                <input id="line_sort_order" name="line_sort_order" value="70" type="hidden">

                <input id="line_product_id" name="line_product_id" value="" type="hidden">
                <input id="line_combination_id" name="line_combination_id" value="" type="hidden">
                <input id="line_type" name="line_type" value="service" type="hidden">

                <input id="line_cost_price" name="line_cost_price" type="hidden">
                <input id="line_unit_price" name="line_unit_price" type="hidden">
                <input id="line_unit_customer_price" name="line_unit_customer_price" type="hidden">

                
                <input id="discount_amount_tax_incl" name="discount_amount_tax_incl" type="hidden">
                <input id="discount_amount_tax_excl" name="discount_amount_tax_excl" type="hidden">

         </div>

         <div id="new_product" style="">
         <div class="modal-body">
               


        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-8 col-md-8 col-sm-8">
                     Producto                     <input class="form-control ui-autocomplete-input" id="line_autoproduct_name" onclick="this.select()" name="line_autoproduct_name" autocomplete="off" value="pan in" type="text">
                  </div>

                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    Impuesto
                    <div id="line_tax_label" class="form-control"></div>
                    <input id="line_tax_percent" name="line_tax_percent" value="0" type="hidden">
                    <input id="line_tax_id" name="line_tax_id" value="0" type="hidden">
                 </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 ">
                     ID del Producto
                     <input class="form-control" id="pid" name="pid" type="text">
                  </div -->

                                    <div class="form-group col-lg-2 col-md-2 col-sm-2">
                          Aplica RE?
                     <div>
                       <div class="radio-inline">
                         <label>
                           <input id="line_is_sales_equalization_on" onclick='alert("peo")' checked="checked" name="line_is_sales_equalization" value="1" type="radio">
                           Sí
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           <input id="line_is_sales_equalization_off" name="line_is_sales_equalization" value="0" type="radio">
                           No
                         </label>
                       </div>
                     </div>
                   </div>
                                      
        </div>

        <!-- div class="row">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 ">
                    Measure Unit
                    <select class="form-control" id="line_measure_unit_id" onFocus="this.blur()" name="BOMline[measure_unit_id]"><option value="0">-- Seleccione --</option></select>
                    
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 ">
                     Scrap (%)
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="bottom" 
                                      data-container="body" 
                                      data-content="Percent. When the components are ready to be consumed in a released production order, this percentage will be added to the expected quantity in the Consumption Quantity field in a production journal.">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     <input class="form-control" id="line_scrap" name="BOMline[scrap]" type="text">
                     
                  </div>
        </div -->

         <div class="row">
                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 " xstyle="display: none;">
                     Position
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="Use multiples of 10. Use other values to interpolate.">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     { ! ! Form::text('line_sort_order', null, array('class' => 'form-control', 'id' => 'line_sort_order')) ! ! }
                     
                  </div -->
                 <!-- div class="form-group col-lg-3 col-md-3 col-sm-3">
                    Precio de Coste
                    <input class="form-control" id="cost_price" autocomplete="off" name="cost_price" type="text">
                    
                 </div -->

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 ">
                     Cantidad
                     <input class="form-control" id="line_quantity" onkeyup="calculate_line_product( )" onchange="calculate_line_product( )" onclick="this.select()" autocomplete="off" name="line_quantity" value="1" type="text">
                     
                  </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                                        Price with Tax
                                        <input class="form-control" id="line_price" onkeyup="calculate_line_product( )" onchange="calculate_line_product( )" onclick="this.select()" autocomplete="off" name="line_price" value="0" type="text">
                    
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    Descuento (%)
                    <input class="form-control" id="line_discount_percent" onkeyup="calculate_line_product( )" onchange="calculate_line_product( )" onclick="this.select()" autocomplete="off" name="line_discount_percent" value="0" type="text">
                    
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                                        Final Price with Tax
                                        <div id="line_final_price" class="form-control"></div>
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    Total
                    <div id="line_total_tax_exc" class="form-control"></div>
                 </div>
                 <div class="form-group col-lg-2 col-md-2 col-sm-2">
                    Total with Tax
                    <div id="line_total_tax_inc" class="form-control"></div>
                 </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                     Notas
                     <textarea class="form-control" id="line_notes" rows="3" name="line_notes" cols="50"></textarea>
                     
                  </div>
        </div>


<!--
                     <div class="col-lg-4 col-md-4 col-sm-4">
                        ¿Aplicar Recargo de Equivalencia? 

              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="El Recargo de Equivalencia sólo aplica en la compra de los bienes objeto del comercio minorista; no aplica a elementos de inmovilizado o en servicios contratados, etc.">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
                        <div>
                          <div class="radio-inline">
                            <label>
                              <input id="sales_equalization_on" checked="checked" name="sales_equalization" type="radio" value="1">
                              Sí
                            </label>
                          </div>
                          <div class="radio-inline">
                            <label>
                              <input id="sales_equalization_off" name="sales_equalization" type="radio" value="0">
                              No
                            </label>
                          </div>
                        </div>
                     </div>
-->

         <!-- div id="search_results" style="padding-top: 20px;"></div -->

         </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">Cancelar</button>
               <button type="submit" class="btn btn-success" name="modal_order_line_productSubmit" id="modal_order_line_productSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; Actualizar</button>

           </div>

         </div>   <!-- div id="new_product" class="modal-body"  ENDS  -->


         <div id="new_service" style="display: none;">
         <div class="modal-body">
            
               <div class="row">
                 <div class="form-group col-lg-8 col-md-8 col-sm-8">
                    Descripción
                    <input class="form-control" id="name" autocomplete="off" name="name" type="text">
                    
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                          ¿Es Coste de Envío?
                     <div>
                       <div class="radio-inline">
                         <label>
                           <input id="is_shipping_on" name="is_shipping" value="1" type="radio">
                           Sí
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           <input id="is_shipping_off" checked="checked" name="is_shipping" value="0" type="radio">
                           No
                         </label>
                       </div>
                     </div>
                   </div>

                                    <div class="form-group col-lg-2 col-md-2 col-sm-2">
                          Aplica RE?
                     <div>
                       <div class="radio-inline">
                         <label>
                           <input id="is_sales_equalization_on" name="is_sales_equalization" value="1" type="radio">
                           Sí
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           <input id="is_sales_equalization_off" checked="checked" name="is_sales_equalization" value="0" type="radio">
                           No
                         </label>
                       </div>
                     </div>
                   </div>
                                      
               </div>
               <div class="row">
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    Precio de Coste
                    <input class="form-control" id="cost_price" autocomplete="off" name="cost_price" value="0" type="text">
                    
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    Precio
                    <input class="form-control" id="price" onkeyup='calculate_service_price( "with_tax" )' onchange='calculate_service_price( "with_tax" )' onclick="this.select()" autocomplete="off" name="price" value="0" type="text">
                    
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    Impuesto
                    <select class="form-control" id="tax_id" onchange="calculate_service_price( )" name="tax_id"><option value="1" selected="selected">IVA Normal</option><option value="2">IVA Reducido</option><option value="3">IVA Super Reducido</option><option value="4">IVA Exento (0%)</option></select>
                 </div>
                 <div class="form-group col-lg-3 col-md-3 col-sm-3">
                    Con IVA
                    <input class="form-control" id="price_tax_inc" onkeyup="calculate_service_price( )" onchange="calculate_service_price( )" onclick="this.select()" autocomplete="off" name="price_tax_inc" value="0" type="text">
                    
                 </div>
               </div>

              <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                           Notas
                           <textarea class="form-control" id="service_notes" rows="3" name="service_notes" cols="50"></textarea>
                           
                        </div>
              </div>

         </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">Cancelar</button>
               <button type="submit" class="btn btn-success" name="modal_order_line_serviceSubmit" id="modal_order_line_serviceSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; Actualizar</button>

               <!-- div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_service_to_document();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; Guardar</a>
               </div -->

           </div>
            
         </div>   <!-- div id="new_service" style="display: none;"  ENDS  -->


         <div id="new_discount" class="modal-body" style="display: none;">
            
               <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                     Descripción
                     <input class="form-control" id="discount_name" autocomplete="off" name="discount_name" type="text">
                     
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     Precio
                     <input class="form-control" id="discount_price" onkeyup='calculate_discount_price( "with_tax" )' onchange='calculate_discount_price( "with_tax" )' onclick="this.select()" autocomplete="off" name="discount_price" type="text">
                     
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     Impuesto
                     <select class="form-control" id="discount_tax_id" onchange="calculate_discount_price( )" name="discount_tax_id"><option value="3">IVA Super Reducido</option><option value="2">IVA Reducido</option><option value="1" selected="selected">IVA Normal</option><option value="4">IVA Exento (0%)</option></select>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-sm-3">
                     Con IVA
                     <input class="form-control" id="discount_price_tax_inc" onkeyup="calculate_discount_price( )" onchange="calculate_discount_price( )" onclick="this.select()" autocomplete="off" name="discount_price_tax_inc" type="text">
                     
                  </div>
               </div>

               <div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_discount_to_document();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; Guardar</a>
               </div>
            
         </div>


         <div id="new_text_line" class="modal-body" style="display: none;">
            <form id="f_new_text_line" name="f_new_text_line" action="" method="post" class="form">
               <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                     Descripción
                     <input class="form-control" id="text_line_name" autocomplete="off" name="text_line_name" type="text">
                     
                  </div>
               </div>

               <div class="text-right">
                  <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="add_text_line_to_order();return false;">
                     <i class="fa fa-shopping-cart"></i>
                     &nbsp; Guardar</a>
               </div>
            </form>
         </div>


      </div>
   </div>
<ul id="ui-id-1" tabindex="0" class="ui-menu ui-widget ui-widget-content ui-autocomplete ui-front" style="top: 247.717px; left: 247.5px; width: 562px; display: none;"><li class="ui-menu-item"><div id="ui-id-194" tabindex="-1" class="ui-menu-item-wrapper">[7] [4022] Pan integral de sarraceno con hierbas provenzales ECO SG 500G</div></li><li class="ui-menu-item"><div id="ui-id-195" tabindex="-1" class="ui-menu-item-wrapper">[8] [4021] Pan integral de Sarraceno con semillas de sésamo ECO SG 500G</div></li><li class="ui-menu-item"><div id="ui-id-196" tabindex="-1" class="ui-menu-item-wrapper">[9] [1016] Pan integral de centeno 100% con copos de centeno ECO 900g</div></li><li class="ui-menu-item"><div id="ui-id-197" tabindex="-1" class="ui-menu-item-wrapper">[20] [1014] Pan integral de trigo con semillas de la tierra ECO 900g.</div></li></ul></div>

           </div>
            <footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 copyright">
                S.D.G. - Powered by  
                    <span style="font-size: 16px;" title=" Maranatha! ">&nbsp;&lt;º)♡&gt;&lt;&nbsp;</span> &amp; 
                    <span class="footer-logo" style="font-weight: bold;" title=" Laravel php framework "><span>&nbsp;Laravel 5.5</span></span>
            </div>
            <div class="col-lg-3 text-right">
                <a class="footer-logo" href="http://www.abillander.com/" target="new"><i class="fa fa-bolt"></i> <span>aBillander</span></a> <!-- &nbsp; { {l('Version', [], 'layouts')} } { { App\Configuration::get('SW_VERSION') } } 
                &nbsp; <a href="https://github.com/aBillander/aBillander" target="new"><i id="social" class="fa fa-github-square fa-2x social-tw"></i></a -->
            </div>
        </div>      
    </div>
</footer>
        </div>

        <!-- Scripts -->
        <script src="Pedidos%20de%20Clientes_files/jquery.js" type="text/javascript"></script>
        <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
        <script src="Pedidos%20de%20Clientes_files/bootstrap.js" type="text/javascript"></script>

        <script src="Pedidos%20de%20Clientes_files/common.js" type="text/javascript"></script>

        <script type="text/javascript">

            // https://stackoverflow.com/questions/11832914/round-to-at-most-2-decimal-places-only-if-necessary
            // usage:var n = 1.7777;    n.round(2); // 1.78

            Number.prototype.round = function(places) {
              return +(Math.round(this + "e+" + places)  + "e-" + places);
            }

            // Numbers as string rounding. Groovy!!!
            String.prototype.round = function(places) {
              return +(Math.round(parseFloat(this) + "e+" + places)  + "e-" + places);
            }

            // Passing data from Eloquent to Javascript
            // See: https://github.com/laracasts/PHP-Vars-To-Js-Transformer

            var PRICES_ENTERED_WITH_TAX = 1;

        </script>

                

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="Pedidos%20de%20Clientes_files/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->
    

    <script type="text/javascript">

        var PRICE_DECIMAL_PLACES;

        $(document).ready(function() {

//          loadBOMlines();

  //            alert('id');

          $(document).on('click', '.create-order-line', function(evnt) {
//              var url = "{ { route('productbom.storeline', [$order->id]) } }";
              var next = $('#next_line_sort_order').val();
              var label = '';

                    label = "Add Line to Order";
                    $('#modal_order_line_Label').text(label);
                    modal_search_tab_hide_all();
                    $("#li_new_product").addClass('active');

                    $('#line_id').val('');
//                    $('#line_type').val('');
                    $('#line_sort_order').val(next);
                    $('#line_quantity').val(1);
                    $('#line_price').val(0.0);
                    $('#line_discount_percent').val(0.0);

                    calculate_line_product();

                    $('#line_notes').val('');

                $("#line_autoproduct_name").val('');
                $('#line_product_id').val('');
                $('#line_combination_id').val('');

              $("#new_product").show();

              $('#modal_order_line').modal({show: true});
              $("#line_autoproduct_name").focus();
              return false;
          });

          $(document).on('click', '.edit-bom-line', function(evnt) {
              var id = $(this).attr('data-id');
              var url = "{ { route('productbom.getline', [$bom->id, '']) } }/"+id;
              var label = '';

               $.get(url, function(result){
                    label = '['+result.product.reference+'] '+result.product.name;
                    $('#modal_order_line_Label').text(label);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_quantity').val(result.quantity);
                    $('#line_measure_unit_id').val(result.measure_unit_id);
                    $('#line_scrap').val(result.scrap);
                    $('#line_notes').val(result.notes);

//                    console.log(result);
               });

              $('#product-search-autocomplete').hide();
              $("#line_autoproduct_name").val('');
              $('#modal_order_line').modal({show: true});
              return false;
          });

          loadCustomerOrderlines();


          $(document).on('click', '.update-order-total', function(evnt) {

              updateCustomerOrderTotal();
              return false;

          });

          

        });

        function loadCustomerOrderlines() {
           
           var panel = $("#panel_customer_order_lines");
           var url = "http://localhost/enatural/public/customerorders/3/getlines";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');
                 $("[data-toggle=popover]").popover();
                 sortableOrderlines();
           }, 'html');

        }

        function updateCustomerOrderTotal() {
           
           var panel = $("#panel_customer_order_total");
           var url = "http://localhost/enatural/public/customerorders/3/updatetotal";
           var token = "KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE";

           panel.addClass('loading');

            $.ajax({
                url: url,
                headers : {'X-CSRF-TOKEN' : token},
                method: 'POST',
                dataType: 'html',
                data: {
                    document_discount_percent: $("#document_discount_percent").val()
                },
                success: function (response) {

                   panel.html(response);
                   panel.removeClass('loading');
                   $("[data-toggle=popover]").popover();
                }
            });

        }

        function sortableOrderlines() {

          // Sortable :: http://codingpassiveincome.com/jquery-ui-sortable-tutorial-save-positions-with-ajax-php-mysql
          // See: https://stackoverflow.com/questions/24858549/jquery-sortable-not-functioning-when-ajax-loaded
          $('.sortable').sortable({
              cursor: "move",
              update:function( event, ui )
              {
                  $(this).children().each(function(index) {
                      if ( $(this).attr('data-sort-order') != ((index+1)*10) ) {
                          $(this).attr('data-sort-order', (index+1)*10).addClass('updated');
                      }
                  });

                  saveNewPositions();
              }
          });

        }

        function saveNewPositions() {
            var positions = [];
            var token = "KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE";

            $('.updated').each(function() {
                positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: "http://localhost/enatural/public/customerorders/sortlines",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'POST',
                dataType: 'json',
                data: {
                    positions: positions
                },
                success: function (response) {
                    console.log(response);
                }
            });
        }

        $("#modal_order_line_productSubmit").click(function() {

 //         alert('etgwer');

            var id = $('#line_id').val();
            var url = "http://localhost/enatural/public/customerorders/updateline/"+id;
            var token = "KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE";

            if ( id == '' )
                url = "http://localhost/enatural/public/customerorders/3/storeline";
            else
                url = "http://localhost/enatural/public/customerorders/updateline/"+id;

  //        alert(url);

            var payload = { 
                              order_id : 3,
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              product_id : $('#line_product_id').val(),
                              combination_id : $('#line_combination_id').val(),
                              quantity : $('#line_quantity').val(),
                              cost_price : $('#line_cost_price').val(),
                              unit_price : $('#line_unit_price').val(),
                              unit_customer_price : $('#line_unit_customer_price').val(),
                              unit_customer_final_price : $('#line_price').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_percent : $('#line_tax_percent').val(),
                              sales_equalization : $("input[name='line_is_sales_equalization']:checked").val(),
                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
                              discount_percent : $('#line_discount_percent').val(),
                              discount_amount_tax_incl : $('#line_discount_amount_tax_incl').val(),
                              discount_amount_tax_excl : $('#line_discount_amount_tax_excl').val(),
                              sales_rep_id : $('#line_sales_rep_id').val(),
                              notes : $('#line_notes').val()
                          };



//    pload = pload + "&customer_id="+$("#customer_id").val();
//    pload = pload + "&currency_id="+$("#currency_id").val()+"&conversion_rate="+$("#currency_conversion_rate").val();
//    pload = pload + "&_token="+$('[name="_token"]').val();

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    loadCustomerOrderlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_order_line').modal('toggle');
                    $("#msg-success").fadeIn();
                }
            });

/*            $(function () {  $('[data-toggle="tooltip"]').tooltip()});
            $("[data-toggle=popover]").popover();
            $(function () {
  $('[data-toggle="popover"]').popover()
})
*/
        });

        $("#line_autoproduct_name").autocomplete({
            source : "http://localhost/enatural/public/customerorders/line/searchproduct",
            minLength : 1,
            appendTo : "#modal_order_line",

            select : function(key, value) {
                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#line_autoproduct_name").val(str);
                $('#line_product_id').val(value.item.id);
                $('#line_combination_id').val(0)

                getProductData( $('#line_product_id').val(), $('#line_combination_id').val() );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
                .appendTo( ul );
            };


        function getProductData( product_id, combination_id ) {
            var price;
            var token = "KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE";
            // https://stackoverflow.com/questions/28417781/jquery-add-csrf-token-to-all-post-requests-data/28418032#28418032

            $.ajax({
                url: "http://localhost/enatural/public/customerorders/line/getproduct",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    combination_id: combination_id,
                    customer_id: $("#customer_id").val(),
                    currency_id: $("#currency_id").val(),
                    conversion_rate: $("#currency_conversion_rate").val(),
                    taxing_address_id: $("#taxing_address_id").val()
                },
                success: function (response) {
                    PRICE_DECIMAL_PLACES = response.currency.decimalPlaces;
                    $('#line_cost_price').val(response.cost_price);
                    $('#line_unit_price').val(response.unit_price.display);
                    $('#line_tax_label').html(response.tax_label);
                    $('#line_tax_id').val(response.tax_id);
                    $('#line_tax_percent').val(response.tax_percent);

                    if( $("#sales_equalization").val() )
                        $('input:radio[name=line_is_sales_equalization][value=1]').prop('checked', true);
                    else
                        $('input:radio[name=line_is_sales_equalization][value=0]').prop('checked', true);
                    
                    $('#line_discount_percent').val(0);
//                    price = parseFloat(response.unit_customer_price.display);
                    price = response.unit_customer_price.display;
                    $("#line_unit_customer_price").val( price );
                    $("#line_price").val( price.round( PRICE_DECIMAL_PLACES ) );

                    calculate_line_product();

                    console.log(response);
                }
            });
        }


        $("#modal_order_line_serviceSubmit").click(function() {

 //         alert('etgwer');

            var id = $('#line_id').val();
            var url = "http://localhost/enatural/public/customerorders/updateline/"+id;
            var token = "KIiWm1AIE2R3TEWREDtuwn1TLUJC8XoRJlkCdWTE";

            if ( id == '' )
                url = "http://localhost/enatural/public/customerorders/3/storeline";
            else
                url = "http://localhost/enatural/public/customerorders/updateline/"+id;

  //        alert(url);

            var payload = { 
                              order_id : 3,
                              line_sort_order : $('#line_sort_order').val(),
                              line_type : $('#line_type').val(),
                              name : $('#name').val(),
                              quantity : 1,
                              is_shipping : $("input[name='is_shipping']:checked").val(),
                              cost_price : $('#cost_price').val(),
                              price : $('#price').val(),
                              price_tax_inc : $('#price_tax_inc').val(),
                              prices_entered_with_tax : PRICES_ENTERED_WITH_TAX,
                              tax_id : $('#tax_id').val(),
                              tax_percent : $('#line_tax_percent').val(),
                              currency_id : $("#currency_id").val(),
                              conversion_rate: $("#currency_conversion_rate").val(),
//                              sales_rep_id : $('#line_sales_rep_id').val(),
                              notes : $('#service_notes').val()
                          };



//    pload = pload + "&customer_id="+$("#customer_id").val();
//    pload = pload + "&currency_id="+$("#currency_id").val()+"&conversion_rate="+$("#currency_conversion_rate").val();
//    pload = pload + "&_token="+$('[name="_token"]').val();

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    loadCustomerOrderlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_order_line').modal('toggle');
                    $("#msg-success").fadeIn();
                }
            });

/*            $(function () {  $('[data-toggle="tooltip"]').tooltip()});
            $("[data-toggle=popover]").popover();
            $(function () {
  $('[data-toggle="popover"]').popover()
})
*/
        });

    </script><div role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"><div style="display: none;">14 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">14 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">11 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">8 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">14 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">11 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">8 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">2 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">6 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">14 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">11 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">8 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">4 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">8 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">7 results are available, use up and down arrow keys to navigate.</div><div style="display: none;">4 results are available, use up and down arrow keys to navigate.</div><div>4 results are available, use up and down arrow keys to navigate.</div></div>





<script src="Pedidos%20de%20Clientes_files/jquery-ui.js"></script>
<script src="Pedidos%20de%20Clientes_files/datepicker-es.js"></script>


<script>

  $(function() {
    $( "#document_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "d/m/yy"
    });
  });

  $(function() {
    $( "#delivery_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "d/m/yy"
    });
  });
  
</script>





<script type="text/javascript">

$(document).ready(function() {
   
   $("#tab_new_product").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all();
      $("#li_new_product").addClass('active');

      // Reset values
      $('#line_type').val('product');
      $("#line_autoproduct_name").val('');
      $('#line_product_id').val();
      $('#line_combination_id').val();
      $('#line_quantity').val(1.0);
      $('#line_cost_price').val();
      $('#line_unit_price').val();
      $('#line_unit_customer_price').val();
      $('#line_price').val();
      $('#line_tax_id').val(0);
      $('#line_tax_percent').val(0.0);
      $('#line_tax_label').html('');
      $('#line_discount_percent').val(0.0);
      $('#line_discount_amount_tax_incl').val(0.0);
      $('#line_discount_amount_tax_excl').val(0.0);
      $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
      $('#line_total_tax_exc').html('');
      $('#line_total_tax_inc').html('');
      $('#line_notes').val('');

      $("#new_product").show();
      $("#line_autoproduct_name").focus();
   });
   
   $("#tab_new_service").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_service").addClass('active');

      // Reset values
      $('#line_type').val('service');
      $('#name').val('');
//      $('#is_shipping').val(0);
      $('input[name=is_shipping][value=0]').prop('checked', 'checked');
      $('#cost_price').val(0.0);
      $('#price').val(0.0);
      $('#tax_id').val(1);
      $('#line_tax_percent').val(0.0);
      $('#price_tax_inc').val(0.0);
      $('#service_notes').val('');

      $("#new_service").show();
      $("#name").focus();
   });
   
   $("#tab_new_discount").click(function(event) {
      event.preventDefault();
      modal_search_tab_hide_all()
      $("#li_new_discount").addClass('active');
      $('#line_type').val('discount');
      $("#new_discount").show();
      document.f_new_discount.discount_name.select();
   });

      // To get focus properly:
      $("#tab_new_product").trigger("click");

});

function modal_search_tab_hide_all() {

  $("#nav_new_order_line li").each(function() {
     $(this).removeClass("active");
  });

     $("#new_product").hide();
     $("#new_service").hide();
     $("#new_discount").hide();
}

function calculate_line_product() {

   var tax_percent;

    // parseFloat

    var total;
    var total_tax_exc;
    var total_tax_inc;
//    var tax_percent = $('#line_tax_percent').val();

    if ($("#line_tax_id").val()>0) { 
      tax_percent = parseFloat( 
        get_tax_percent_by_id( $("#line_tax_id").val(), $("input[name='line_is_sales_equalization']:checked").val() ) 
      ); 
    }
    else { return false; }

    total = $("#line_quantity").val() * $("#line_price").val() * ( 1.0 - $("#line_discount_percent").val() / 100.0 );

    if ( PRICES_ENTERED_WITH_TAX ) {
        total_tax_inc = total;
        total_tax_exc = total / ( 1.0 + tax_percent / 100.0 );
    } else {
        total_tax_inc = total * ( 1.0 + tax_percent / 100.0 );
        total_tax_exc = total;
    }

    $("#line_total_tax_exc").html( total_tax_exc.round( PRICE_DECIMAL_PLACES ) );
    $("#line_total_tax_inc").html( total_tax_inc.round( PRICE_DECIMAL_PLACES ) );
}


function calculate_service_price( with_tax )
{
   var tax_percent;

  if ($("#tax_id").val()>0) { 
    tax_percent = parseFloat( 
      get_tax_percent_by_id( $("#tax_id").val(), $("input[name='is_sales_equalization']:checked").val() ) 
    ); 
  }
  else { return ; }

   $('#line_tax_percent').val( tax_percent );

   if (with_tax=='with_tax')
   {
      p = $("#price").val();
      p_t = p*(1.0 + tax_percent/100.0)
      $("#price_tax_inc").val(p_t);
   } else {
      p_t = $("#price_tax_inc").val();
      p = p_t/(1.0 + tax_percent/100.0)
      $("#price").val(p);
   }
}



function get_tax_percent_by_id(tax_id, se = 0) 
{
   // http://stackoverflow.com/questions/18910939/how-to-get-json-key-and-value-in-javascript
   // var taxes = $.parseJSON( '{&quot;1&quot;:&quot;21.000&quot;,&quot;2&quot;:&quot;10.000&quot;,&quot;3&quot;:&quot;4.000&quot;,&quot;4&quot;:&quot;0.000&quot;}' );

/*   var taxes = { ! ! json_encode( $customer->sales_equalization
                                  ? $taxeqpercentList 
                                  : $taxpercentList 
                              ) ! ! } ;
*/
   var se;
   var taxes   = {"1":"21.000","2":"10.000","3":"4.000","4":"0.000"} ;
   var retaxes = {"1":"26.200","2":"11.400","3":"4.500","4":"0.000"} ;

   if (typeof taxes[tax_id] == "undefined")   // or if (taxes[tax_id] === undefined) {
   {
        // variable is undefined
        alert('Tax code ['+tax_id+'] not found!');

        return false;
   }

   if (se>0)
        return parseFloat(retaxes[tax_id]);
   else
        return parseFloat(taxes[tax_id]);
}

</script>

    
<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" style="position: absolute; top: 91.317px; left: 266.5px; z-index: 1; display: none;"><div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all"><a class="ui-datepicker-prev ui-corner-all" data-handler="prev" data-event="click" title="&lt;Ant"><span class="ui-icon ui-icon-circle-triangle-w">&lt;Ant</span></a><a class="ui-datepicker-next ui-corner-all" data-handler="next" data-event="click" title="Sig&gt;"><span class="ui-icon ui-icon-circle-triangle-e">Sig&gt;</span></a><div class="ui-datepicker-title"><span class="ui-datepicker-month">Mayo</span>&nbsp;<span class="ui-datepicker-year">2018</span></div></div><table class="ui-datepicker-calendar"><thead><tr><th scope="col"><span title="Lunes">Lu</span></th><th scope="col"><span title="Martes">Ma</span></th><th scope="col"><span title="Miércoles">Mi</span></th><th scope="col"><span title="Jueves">Ju</span></th><th scope="col"><span title="Viernes">Vi</span></th><th scope="col" class="ui-datepicker-week-end"><span title="Sábado">Sá</span></th><th scope="col" class="ui-datepicker-week-end"><span title="Domingo">Do</span></th></tr></thead><tbody><tr><td class=" ui-datepicker-other-month " data-handler="selectDay" data-event="click" data-month="3" data-year="2018"><a class="ui-state-default ui-priority-secondary" href="#">30</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">1</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">2</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">3</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">4</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">5</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">6</a></td></tr><tr><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">7</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">8</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">9</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">10</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">11</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">12</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">13</a></td></tr><tr><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">14</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">15</a></td><td class="  ui-datepicker-current-day" data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default ui-state-active" href="#">16</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">17</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">18</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">19</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">20</a></td></tr><tr><td class="  ui-datepicker-today" data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default ui-state-highlight" href="#">21</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">22</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">23</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">24</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">25</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">26</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">27</a></td></tr><tr><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">28</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">29</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">30</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="4" data-year="2018"><a class="ui-state-default" href="#">31</a></td><td class=" ui-datepicker-other-month " data-handler="selectDay" data-event="click" data-month="5" data-year="2018"><a class="ui-state-default ui-priority-secondary" href="#">1</a></td><td class=" ui-datepicker-week-end ui-datepicker-other-month " data-handler="selectDay" data-event="click" data-month="5" data-year="2018"><a class="ui-state-default ui-priority-secondary" href="#">2</a></td><td class=" ui-datepicker-week-end ui-datepicker-other-month " data-handler="selectDay" data-event="click" data-month="5" data-year="2018"><a class="ui-state-default ui-priority-secondary" href="#">3</a></td></tr></tbody></table></div></body></html>