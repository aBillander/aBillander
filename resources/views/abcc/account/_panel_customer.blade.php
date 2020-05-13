
            <div class="panel panel-primary" id="panel_main">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('My Company') }}</h3>
               </div>


        {!! Form::model($customer, array('xmethod' => 'PATCH', 'url' => route('abcc.customer.update') )) !!}


               <div class="panel-body">

<!-- Datos generales -->

        <div class="row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6 {!! $errors->has('name_fiscal') ? 'has-error' : '' !!}">
              {{ l('Fiscal Name') }}
              {{-- {!! Form::text('name_fiscal', null, array('class' => 'form-control', 'id' => 'name_fiscal')) !!}
              {!! $errors->first('name_fiscal', '<span class="help-block">:message</span>') !!} --}}
              <div class="form-control">{{ $customer->name_fiscal }}</div>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('identification') ? 'has-error' : '' !!}">
              {{ l('Identification') }}
              {{-- {!! Form::text('identification', null, array('class' => 'form-control', 'id' => 'identification')) !!}
              {!! $errors->first('identification', '<span class="help-block">:message</span>') !!} --}}
              <div class="form-control">{{ $customer->identification }}</div>
            </div>
            
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {!! $errors->has('website') ? 'has-error' : '' !!}">
                    {{ l('Website') }}
                    {!! Form::text('website', null, array('class' => 'form-control', 'id' => 'website')) !!}
                    {!! $errors->first('website', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

@include('abcc.account._form_fields_model_customer')

        <div class="row">
        </div>

<!-- Datos generales ENDS -->

               </div>

@if( $customer_user->is_principal )
               <div class="panel-footer text-right">
                  <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{ l('Save', [], 'layouts') }}
                  </button>
               </div>
@endif

        {!! Form::close() !!}

            </div>


@section('scripts')  @parent 

    <script type="text/javascript">

        // Hide Alias field
        $('#alias_field').hide();

        // Hide Notes field
        $('#notes_field').hide();

        $('#state_selector').hide();
        $('#country_selector').hide();

        // Disable Main Address edition
        $("#address1").attr( {"disabled" : "disabled"} );
        $("#address2").attr( {"disabled" : "disabled"} );
        $("#postcode").attr( {"disabled" : "disabled"} );

        $("#city").attr( {"disabled" : "disabled"} );
        $("#state").attr( {"disabled" : "disabled"} );
        $("#country").attr( {"disabled" : "disabled"} );
        $("#state_selector").attr( {"disabled" : "disabled"} );

    </script>

@endsection
