
<div id="panel_customeruser">     

<div class="panel panel-info">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Customer Center Access') }}

@if ( 0 && \App\Configuration::isTrue('DEVELOPER_MODE') && $customer->user )
      <a href="{{ route('customer.impersonate', [$customer->user->id]) }}" class="btn-success btn-link pull-right" target="_blank"><p class="text-success"><i class="fa fa-clock-o"></i> {{ l('Impersonate') }}</p></a>

@endif

      </h3>
   </div>

@if( optional($customer->user)->active )


        {!! Form::model($customer->user, array('method' => 'PATCH', 'url' => route('customerusers.update', [$customer->user->id]).'#customeruser' )) !!}
        <input type="hidden" value="{{$customer->id}}" name="customer_id" id="customer_id">

          @include('customers._form_customer_user')

        {!! Form::close() !!}
        

@else

    @if ( !$customer->address->email )
          <div class="row">
            <div class="col-md-10 col-md-offset-1" style="margin-top: 10px;margin-bottom: 10px">
                <div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert">&times;</a>
                  {{l('Can not create a User for this Customer:')}}
                  <ul><li class="error">{{l('This Customer has not a valid email address.')}}</li></ul>
                </div>
            </div>
          </div>
    @else

{!! Form::open(array('url' => route('customerusers.store').'#customeruser', 'id' => 'create_customeruser', 'name' => 'create_customeruser', 'class' => 'form')) !!}
<input type="hidden" value="{{$customer->id}}" name="customer_id" id="customer_id">
<input type="hidden" value="customeruser" name="tab_name" id="tab_name">

   <div class="panel-body">

        <div class="row">

                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-allow_abcc_access">
                     {!! Form::label('allow_abcc_access', l('Allow Customer Center access?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('allow_abcc_access', '1', false, ['id' => 'allow_abcc_access_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('allow_abcc_access', '0', true, ['id' => 'allow_abcc_access_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>

                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-notify_customer">
                     {!! Form::label('notify_customer', l('Notify Customer? (by email)'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('notify_customer', '1', true, ['id' => 'notify_customerb_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('notify_customer', '0', false, ['id' => 'notify_customer_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>
{{--
        <hr />

        <div class="row">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('description_short') ? 'has-error' : '' }}">
                     {{ l('Short Description') }}
                     {!! Form::textarea('description_short', null, array('class' => 'form-control', 'id' => 'description_short', 'rows' => '3')) !!}
                     {!! $errors->first('description_short', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>
--}}
   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

{!! Form::close() !!}

    @endif

@endif

</div><!-- div class="panel panel-info" -->

    @include('customers._panel_customer_users_0')


@if( $customer->user )

    @include('customers._panel_cart_lines', ['cart' => $customer->cart])

@endif


</div>
