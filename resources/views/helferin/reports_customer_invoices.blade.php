
<div class="container">
    <div class="row">

            <div xclass="col-lg-3 col-md-6">
            <div class="panel panel-danger">
              <div class="panel-heading" xstyle="color: #468847; background-color: #dff0d8; border-color: #d6e9c6;">
                <h3 class="panel-title"><i class="fa fa-money"></i> Facturas de Clientes</h3>
              </div>


{!! Form::open(array('route' => 'helferin.reports.customer.invoices', 'id' => 'customer_invoices_report_form', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_invoices_date_from_form', 'Fecha desde') !!}
        {!! Form::text('customer_invoices_date_from_form', null, array('id' => 'customer_invoices_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('customer_invoices_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('customer_invoices_date_to_form', null, array('id' => 'customer_invoices_date_to_form', 'class' => 'form-control')) !!}
    </div>

{{--
    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('ecotaxes_model', l('Document')) !!}
        {!! Form::select('ecotaxes_model', ["CustomerInvoice" => 'Facturas'], null, array('id' => 'ecotaxes_model', 'class' => 'form-control')) !!}
    </div>
--}}

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


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

          $('#customer_invoices_date_from_form').val( '' );
          $('#customer_invoices_date_to_form'  ).val( '' );

          // $('#ecotaxes_model').val( '{{ $default_model }}' );


        });


    </script> 



{{-- Date Picker --}}

<script>

  $(function() {
    $( "#customer_invoices_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#customer_invoices_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

</script>

@endsection
