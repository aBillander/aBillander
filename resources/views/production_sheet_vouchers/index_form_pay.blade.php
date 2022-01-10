

              <div class="panel-body">

<div class="alert alert-dismissible alert-warning">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <p>{{ l('There are :nbr Vouchers Pending.', ['nbr' => $payments->where('status', 'pending')->count()]) }}</p>
</div>

<div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('bulk_payment_date') ? 'has-error' : '' }}">
        {!! Form::label('bulk_payment_date_form', l('Payment Date')) !!}
        {!! Form::text('bulk_payment_date_form', null, array('id' => 'bulk_payment_date_form', 'class' => 'form-control')) !!}
        {!! $errors->first('bulk_payment_date', '<span class="help-block">:message</span>') !!}
    </div>

<div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('bulk_payment_type_id') ? 'has-error' : '' }}">
    {!! Form::label('bulk_payment_type_id', l('Payment Type')) !!}
                    <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                              data-container="#panel_forms"
                              data-content="{{ l('If you do not select anything, every Voucher will go with its Payment Type.') }}">
                          <i class="fa fa-question-circle abi-help"></i>
                    </a>
    {!! Form::select('bulk_payment_type_id', array('' => l('-- Please, select --', [], 'layouts')) + $payment_typeList, null, array('class' => 'form-control')) !!}
    {!! $errors->first('bulk_payment_type_id', '<span class="help-block">:message</span>') !!}
</div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('group_balance', l('Amount')) !!}
        <div id="group_balance" class="form-control alert-warning">0.0</div>
    </div>

</div>

                  </div>

                  <div class="panel-footer">

                        <a class="btn btn-blue" href="javascript:void(0);" title="{{l('Pay multiple')}}" onclick = "this.disabled=true;$('#form-select-payments').attr('action', '{{ route( 'customervouchers.bulk.pay' )}}');$('#form-select-payments').submit();return false;"><i class="fa fa-money"></i> &nbsp; {{l('Pay multiple')}}</a>
                  
                  </div>
