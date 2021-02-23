
    <div xclass="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('Down Payments') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $document->name }} -->
             {{-- include getSubtotals functions in billable trait --}}
        </h3>        
    </div>

    <div id="div_document_downpayments">

@if ($downpayments->count())

   <div class="table-responsive">

<table id="downpayments" class="table table-hover">
  <thead>
    <tr>
      <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{ l('Due Date') }}</th>
            <th>{{l('Supplier Order')}}</th>
            <th>{{l('Amount')}}</th>
            <th>{{l('Currency')}}</th>
            <th class="text-center">{{l('Status', [], 'layouts')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
      <th> </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($downpayments as $downpayment)
    <tr>
            <td>{{ $downpayment->id }}</td>
            <td>{{ abi_date_short($downpayment->due_date) ?: '-' }}</td>
            <td>
  @if($downpayment->supplierorder)
              <a class="" href="{{ URL::to('supplierorders/' . $downpayment->supplier_order_id . '/edit') }}" 
                title="{{ l('Go to', 'layouts') }}" target="_new">
                  {{ $downpayment->supplierorder->document_reference ?: l('Draft', 'layouts') }}
              </a>
  @else
              -
  @endif
            </td>

            <td>{{ $downpayment->amount > 0.0 ? $downpayment->as_money_amount('amount') : '-' }}</td>
            <td xclass="text-center">{{ $downpayment->currency->name }}</td>
            <td class="text-center button-pad">
              @if     ( $downpayment->status == 'pending' )
                <span class="label label-info">
              @elseif ( $downpayment->status == 'deposited' )
                <span class="label label-danger">
              @elseif ( $downpayment->status == 'paid' )
                <span class="label label-success">
              @elseif ( $downpayment->status == 'bounced' )
                <span class="label alert-danger">
              @else
                <span class="label">
              @endif
              {{ $downpayment->status_name }}</span>

              @if ( $downpayment->status == 'paid' )

                    <a href="{{ route('supplier.downpayment.bounce', [$downpayment->id]) }}" class="btn btn-xs btn-danger" 
                    title="{{l('Bounce Cheque')}}" xstyle="margin-left: 22px;"><i class="fa fa-mail-reply-all"></i></a>

              @endif

            </td>

              <td class="text-center">
                  @if ($downpayment->notes)
                   <a href="javascript:void(0);">
                      <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                              data-content="{{ $downpayment->notes }}">
                          <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                      </button>
                   </a>
                  @endif
              </td>

      <td class="text-right button-pad">

                <a class="btn btn-sm btn-warning" href="{{ URL::to('supplierdownpayments/' . $downpayment->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

      </td>
    </tr>
  @endforeach
  </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_downpayments" ENDS -->

