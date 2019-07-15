@extends('layouts.master')

@section('title') {{ l('Welcome') }} @parent @stop


@section('content')

<div class="page-header">
    <h2>
         
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            {{ Auth::user()->getFullName() }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

         <!-- a href="{{ URL::to('auth/logout') }}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Reports', [], 'layouts') }} 
         <span style="color: #cccccc;">/</span> {{ l('Accounting', [], 'layouts') }}
    </h2>
</div>


<div class="container">
    <div class="row">

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i> Facturas</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.invoices', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('invoices_date_from_form', 'Fecha desde') !!}
        {!! Form::text('invoices_date_from_form', null, array('id' => 'invoices_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('invoices_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('invoices_date_to_form', null, array('id' => 'invoices_date_to_form', 'class' => 'form-control')) !!}
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
                     &nbsp; Ver Listado
                  </button>
               </div>

{!! Form::close() !!}

            </div>

            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->

            <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bank"></i> Remesas</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.bankorders', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_from', 'Remesa desde') !!}
        {!! Form::text('bank_order_from', null, array('id' => 'bank_order_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_to', 'Remesa hasta') !!}
        {!! Form::text('bank_order_to', null, array('id' => 'bank_order_to', 'class' => 'form-control')) !!}
    </div>

                  </div>
                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_date_from_form', 'Fecha desde') !!}
        {!! Form::text('bank_order_date_from_form', null, array('id' => 'bank_order_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('bank_order_date_to_form', null, array('id' => 'bank_order_date_to_form', 'class' => 'form-control')) !!}
    </div>

                  </div>

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; Ver Listado
                  </button>

            </div>

{!! Form::close() !!}

            </div>
            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-th"></i> Inventario</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.inventory', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('inventory_date_to_form', 'Inventario a Fecha') !!}
        {!! Form::text('inventory_date_to_form', null, array('id' => 'inventory_date_to_form', 'class' => 'form-control')) !!}
    </div>

                  </div>

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; Ver Listado
                  </button>

            </div>

{!! Form::close() !!}

            </div>
            </div>



    </div><!-- div class="row" ENDS -->

</div>






{{-- ********************************************************** --}}




{{-- ***************************************************** --}}


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-2">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-9 col-md-9 col-sm-10">
      <div class="jumbotron" style="background: no-repeat url('{{URL::to('/assets/theme/images/Dashboard.jpg')}}'); background-size: 100% auto;min-height: 200px; margin-top: 40px;">


      </div>
      </div>

   </div>
</div>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

          $('#invoices_date_from_form').val( '' );
          $('#invoices_date_to_form'  ).val( '' );

          $('#bank_order_date_from_form').val( '' );
          $('#bank_order_date_to_form'  ).val( '' );
          $('#bank_order_from').val( '' );
          $('#bank_order_to'  ).val( '' );

          $('#inventory_date_to_form').val( '' );

        });

    </script> 



{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#invoices_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#invoices_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#bank_order_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#bank_order_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#inventory_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection
