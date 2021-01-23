
<div class="container">
    <div class="row">

            <div xclass="col-lg-3 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading panel-info" xstyle="color: #ffffff;
background-color: #772953;
border-color: #772953;">
                <h3 class="panel-title"><i class="fa fa-users"></i> &nbsp; <strong>ABC Clientes</strong> :: Ventas de Productos / Año en curso</h3>
              </div>


{!! Form::open(array('route' => 'reports.abc.customers.sales', 'id' => 'abc_customer_sales_report_form', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('abc_customer_sales_month_from', 'Mes desde') !!}
        {!! Form::select('abc_customer_sales_month_from', $selectorMonthList, 1, array('id' => 'abc_customer_sales_month_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('abc_customer_sales_month_to', 'Mes hasta') !!}
        {!! Form::select('abc_customer_sales_month_to', $selectorMonthList, $current['month'], array('id' => 'abc_customer_sales_month_to', 'class' => 'form-control')) !!}
    </div>
{{--
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('abc_customer_sales_years_to_compare', 'Años para comparar') !!}
        {!! Form::select('abc_customer_sales_years_to_compare', $selectorNumberYearsList, 0, array('id' => 'abc_customer_sales_years_to_compare', 'class' => 'form-control')) !!}
    </div>
--}}
        {{ Form::hidden( 'abc_customer_sales_years_to_compare', 0, ['id' => 'abc_customer_sales_years_to_compare'] ) }}
    
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('abc_customer_sales_value', 'Valor de las Ventas') !!}
        {!! Form::select('abc_customer_sales_value', ['total_tax_incl' => 'Con Impuestos incluidos', 'total_tax_excl' => 'Sin Impuestos'], 'total_tax_incl', array('id' => 'abc_customer_sales_value', 'class' => 'form-control')) !!}
    </div>


    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {{-- Poor man offset --}}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {{-- Poor man offset --}}
    </div>


    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('abc_customer_sales_model', l('Document')) !!}
        {!! Form::select('abc_customer_sales_model', $modelList, $default_model, array('id' => 'abc_customer_sales_model', 'class' => 'form-control')) !!}
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

    <div class="alert alert-dismissible alert-warning">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4>ABC analysis (from the term “The Pareto Principle”, also called 80/20 rule)</h4>
          <p>When it comes to customer management, ABC analysis typically segregates customers into three categories based on its revenue and control measures required: <strong>A is 20% of customers with 80% of total revenue and hence asks for tight control; B is 30% customers with 15% revenue; whereas ‘C’ is 50% of the customers with least 5% revenue and hence treated as most liberal</strong>.</p>
    </div>


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
