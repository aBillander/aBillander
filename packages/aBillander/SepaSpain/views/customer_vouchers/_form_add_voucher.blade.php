

         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ l('Add Voucher to SEPA Direct Debit') }} :: {{ l('Due Date') }}: {{ abi_date_short($voucher->due_date) }} :: {{ l('Amount') }}: {{ $voucher->as_price('amount') }} {{ $voucher->currency->name }}</h4>
         </div>

         <div class="modal-body">


            {{-- csrf_field() --}}
            {!! Form::token() !!}
            <!-- input type="hidden" name="_token" value="{ { csrf_token() } }" -->
            <!-- input type="hidden" id="" -->
            {{ Form::hidden('voucher_id'     , $voucher->id, array('id' => 'voucher_id'     )) }}
               


 <div id="div_sdds">
   <div class="table-responsive">

@if ($sdds->count())
<table id="sdds" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}</th>
      <th>{{ l('Date') }}</th>
      <!-- th>{{ l('Validation Date') }}</th>
      <th>{{ l('Payment Date') }}</th -->
      <th>{{ l('Amount') }} / {{ l('Vouchers') }}</th>
      <!-- th class="text-center">{{l('Scheme')}}</th -->
      <th>{{ l('Bank') }}</th>
      <th class="text-center">{{l('Status', [], 'layouts')}}</th>
      <!-- th class="text-center">{{l('Notes', [], 'layouts')}}</th -->
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($sdds as $sdd)
    <tr>
      <td>{{ $sdd->id }} / {{ $sdd->document_reference }}</td>
      <td>{{ abi_date_short($sdd->document_date) }}</td>
      <!-- td>{{ abi_date_short($sdd->validation_date) }}</td>
      <td>{{ abi_date_short($sdd->payment_date) }}</td-->
      <td>{{ $sdd->as_money_amount('total') }} / {{ $sdd->nbrItems() }}</td>
      <!-- td class="text-center">{{ $sdd->scheme }}</td -->
      <td>{{ optional($sdd->bankaccount)->bank_name }}</td>

            <td class="text-center">
              @if     ( $sdd->status == 'pending' )
                <span class="label label-danger">
              @elseif ( $sdd->status == 'confirmed' )
                <span class="label label-warning">
              @elseif ( $sdd->status == 'closed' )
                <span class="label label-success">
              @else
                <span>
              @endif
              {{ $sdd->status_name }}</span>
            </td>

      <!-- td class="text-center">
          @if ($sdd->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $sdd->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif</td -->

           <td class="text-right">

                <a class="btn btn-sm btn-navy add-this-voucher-to-sdd" data-sdd-id="{{$sdd->id}}" data-type=""  title="{{l('Add Voucher to SEPA Direct Debit')}}" onClick="return false;"><i class="fa fa-plus"></i> {{l('Add', [], 'layouts')}}</a>

            </td>
    </tr>
  @endforeach
    </tbody>
</table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

         </div><!-- div class="modal-body" ENDS -->

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>

           </div>
