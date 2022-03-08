
<div class="container">
    <div class="row">

            <div xclass="col-lg-3 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading" style="color: #ffffff;
background-color: #772953;
border-color: #772953;">
                <h3 class="panel-title"><i class="fa fa-money"></i> Ventas</h3>
              </div>


{!! Form::open(array('route' => 'helferin.reports.sales', 'id' => 'sales_report_form', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('sales_date_from_form', 'Fecha desde') !!}
        {!! Form::text('sales_date_from_form', null, array('id' => 'sales_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('sales_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('sales_date_to_form', null, array('id' => 'sales_date_to_form', 'class' => 'form-control')) !!}
    </div>

     <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('sales_autocustomer_name', 'Cliente') !!}
        {!! Form::text('sales_autocustomer_name', null, array('class' => 'form-control', 'id' => 'sales_autocustomer_name')) !!}

        {!! Form::hidden('sales_customer_id', null, array('id' => 'sales_customer_id')) !!}
     </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('sales_document_from', 'ID desde') !!}
        {!! Form::text('sales_document_from', null, array('id' => 'sales_document_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-1 col-md-1 col-sm-1">
        {!! Form::label('sales_document_to', 'ID hasta') !!}
        {!! Form::text('sales_document_to', null, array('id' => 'sales_document_to', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('sales_model', l('Document')) !!}
        {!! Form::select('sales_model', $modelList, null, array('id' => 'sales_model', 'class' => 'form-control')) !!}
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
