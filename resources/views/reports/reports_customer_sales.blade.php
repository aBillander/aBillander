
<div class="container">
    <div class="row">

            <div xclass="col-lg-3 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading" style="color: #ffffff;
background-color: #772953;
border-color: #772953;">
                <h3 class="panel-title"><i class="fa fa-user"></i> &nbsp; <strong>Clientes</strong> :: Ventas de Productos / Año en curso y año(s) anterior(es)</h3>
              </div>


{!! Form::open(array('route' => 'reports.customers.sales', 'id' => 'customer_sales_report_form', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_sales_month_from', 'Mes desde') !!}
        {!! Form::select('customer_sales_month_from', $selectorMonthList, 1, array('id' => 'customer_sales_month_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_sales_month_to', 'Mes hasta') !!}
        {!! Form::select('customer_sales_month_to', $selectorMonthList, $current['month'], array('id' => 'customer_sales_month_to', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_sales_years_to_compare', 'Años para comparar') !!}
        {!! Form::select('customer_sales_years_to_compare', $selectorNumberYearsList, 1, array('id' => 'customer_sales_years_to_compare', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_sales_value', 'Valor de las Ventas') !!}
        {!! Form::select('customer_sales_value', ['total_tax_incl' => 'Con Impuestos incluidos', 'total_tax_excl' => 'Sin Impuestos'], 'total_tax_incl', array('id' => 'customer_sales_value', 'class' => 'form-control')) !!}
    </div>


    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_sales_autocustomer_name', 'Cliente') !!}
        {!! Form::text('customer_sales_autocustomer_name', '', array('class' => 'form-control', 'id' => 'customer_sales_autocustomer_name')) !!}

        {!! Form::hidden('customer_sales_customer_id', '', array('id' => 'customer_sales_customer_id')) !!}
    </div>


    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_sales_model', l('Document')) !!}
        {!! Form::select('customer_sales_model', $modelList, $default_model, array('id' => 'customer_sales_model', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2 hide" id="div-sales_grouped">
 {!! Form::label('sales_grouped', l('Agrupado?'), ['class' => 'control-label']) !!}
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('sales_grouped', '1', true, ['id' => 'sales_grouped_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('sales_grouped', '0', false, ['id' => 'sales_grouped_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>

                  </div>
{{--
                  <div class="row">

                     <div class="form-group col-lg-12 text-center" xstyle="padding-top: 22px">
                          {!! Form::submit('Ver Listado', array('class' => 'btn btn-success')) !!}
                    </div>

                  </div>
--}}

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; {!! l('Export', [], 'layouts') !!}
                  </button>
               </div>

{!! Form::close() !!}

            </div>

            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->





    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->





    </div><!-- div class="row" ENDS -->

</div>
