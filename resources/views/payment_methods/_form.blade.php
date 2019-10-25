
<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('name', l('Payment Method name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('payment_type_id', l('Payment Type')) !!}
    {!! Form::select('payment_type_id', ['' => l('-- None --', [], 'layouts')] + $payment_typeList, null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-4 col-md-4 col-sm-4">
    {{-- !! Form::label('deadlines_by', l('Deadlines by')) !! }
    { !! Form::select('deadlines_by', array( 
                      '0' => l('-- Please, select --', [], 'layouts'),
                      'days'  => l('Days',  [], 'appmultilang'),
                      'months' => l('Months', [], 'appmultilang')
                  ), null, array('class' => 'form-control')) !! --}}
</div>
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('', l('Installments')) !!}
</div>
</div>

<div class="row">
<div class="form-group col-lg-1 col-md-1 col-sm-1">
</div>
<div class="form-group col-lg-10 col-md-10 col-sm-10">
  <input type="hidden" id="nbrlines" name="nbrlines" value="{{ count($paymentmethod->deadlines) }}"/>
      <table class="table table-condensed">
         <thead>
            <tr>
               <th class="text-left">{{ l('Days') }}</th>
               <th class="text-left">{{ l('Percent') }}</th>
               <th class="text-left"> </th>
            </tr>
         </thead>
         <tbody id="method_lines">
            @if ( count($paymentmethod->deadlines) > 0 )
               @foreach ( $paymentmethod->deadlines as $i => $line )
                <tr id="line_{{ $i }}">
                  <td><input type="hidden" id="lineid_{{ $i }}" name="lineid_{{ $i }}" value="{{ $i }}"/>
                    <input type="text" name="slot_{{ $i }}" id="slot_{{ $i }}" value="{{ $line['slot'] }}" 
                    onclick="this.select()" class="form-control" autocomplete="off"/></td>
           
                  <td><input type="text" name="percentage_{{ $i }}" id="percentage_{{ $i }}" value="{{ $line['percentage'] }}" 
                    onclick="this.select()" onkeyup="checkFields()" onchange="checkFields()" class="form-control text-right" autocomplete="off"/></td>
              @if ($i==0)
                  <td><button id="i_new_line" class="btn btn-md btn-success" type="button" title="{{l('Add New Item', [], 'layouts')}}">
                   <i class="fa fa-plus"></i></button></td>
              @else
                  <td><button class="btn btn-md btn-danger" type="button" onclick="$('#line_{{ $i }}').remove();checkFields();" title="{{l('Delete', [], 'layouts')}}">
                   <i class="fa fa-trash"></i></button></td>
              @endif
                </tr>
               @endforeach
            @endif
         </tbody>
      </table>

<div class="alert alert-danger alert-block" name="percentages_check_sum" id="percentages_check_sum" style="display: none;">
  <strong>{!! l('Error', [], 'layouts') !!}: </strong>
    {!! l('Percentages do not add up to 100') !!}
</div>

<div class="alert alert-danger alert-block" name="percentages_check" id="percentages_check" style="display: none;">
  <strong>{!! l('Error', [], 'layouts') !!}: </strong>
    {!! l('Some percentages are incorrect') !!}
</div>

</div>
</div>

<div class="row">
<div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-payment_is_cash">
 {!! Form::label('payment_is_cash', l('Payment is Cash'), ['class' => 'control-label']) !!}
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('payment_is_cash', '1', true, ['id' => 'payment_is_cash_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('payment_is_cash', '0', false, ['id' => 'payment_is_cash_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>
<div class="form-group col-lg-5 col-md-5 col-sm-5" id="div-auto_direct_debit">
 {!! Form::label('auto_direct_debit', l('Auto Direct Debit'), ['class' => 'control-label']) !!}
               <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Include invoices (with this method) in automatic payment remittances') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('auto_direct_debit', '1', true, ['id' => 'auto_direct_debit_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('auto_direct_debit', '0', false, ['id' => 'auto_direct_debit_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>
<div class="form-group col-lg-3 col-md-3 col-sm-3" id="div-active">
 {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
 <div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
       {!! l('Yes', [], 'layouts') !!}
     </label>
   </div>
   <div class="radio-inline">
     <label>
       {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
       {!! l('No', [], 'layouts') !!}
     </label>
   </div>
 </div>
</div>
</div>

{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('paymentmethods.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
