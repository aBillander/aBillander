

<div id="div_downpayment_details">

   <div class="table-responsive">

@if ($downpaymentdetails->count())
            <table id="downpaymentdetails" class="table table-hover">
                <thead>
                <tr>
                    <th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
                    <th style="text-transform: none;">{{l('Subject')}}</th>
                    <th style="text-transform: none;">{{l('Invoice')}}</th>
                    <th style="text-transform: none;">{{l('Payment Date')}}</th>
                    <th style="text-transform: none;">{{l('Amount')}}</th>
                    <th style="text-transform: none;">{{l('Payment Type')}}</th>
                    <th style="text-transform: none;" class="text-center">{{l('Status', [], 'layouts')}}</th>
                    <th class="text-right">

@if( 0 && $open_balance > 0.0)
                        <a href="{{ URL::to('downpayments/'.$downpayment->id.'/downpaymentdetails/create') }}" class="btn btn-sm btn-success create-downpaymentdetail pull-right"
                            title="{{l('Add New Detail')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
@endif
                    </th>
                </tr>
                </thead>
                <tbody xclass="sortable">
                @foreach ($downpaymentdetails as $detail)
@php
    $payment = $detail->supplierpayment;
@endphp
                    <tr data-id="{{ $detail->id }}" data-sort-order="{{ $detail->line_sort_order }}">
                        <td title="{{ $detail->id }}"> {{ $payment->id }}</td>
                        <td>{{ $payment->name }}</td>
                        <td>
@if( $payment->supplierinvoice )
          <a href="{{ URL::to('supplierinvoices/' . optional($payment->supplierInvoice)->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $payment->supplierinvoice->document_reference }}</a>
@else
    -
@endif
                        </td>
                        <td>{{ abi_date_short($payment->payment_date) }}</td>
                        <td>{{ abi_money_amount($payment->amount, $payment->currency) }}</td>

                        <td>{{ optional($payment->paymenttype)->name }}</td>

                        <td class="text-center">
                            @if     ( $payment->status == 'pending' )
                                <span class="label label-info">
                            @elseif ( $payment->status == 'bounced' )
                                <span class="label label-danger">
                            @elseif ( $payment->status == 'paid' )
                                <span class="label label-success">
                            @elseif ( $payment->status == 'uncollectible' )
                                <span class="label alert-danger">
                            @else
                                <span class="label">
                            @endif
                            {{l( $payment->status, [], 'appmultilang' )}}</span>

                          @if ( 0 && $payment->status == 'paid' )

                                <a href="{{ route('suppliervoucher.unpay', [$payment->id]) }}" class="btn btn-xs btn-danger" 
                                title="{{l('Undo', 'layouts')}}" xstyle="margin-left: 22px;"><i class="fa fa-undo"></i></a>
                           
                          @endif

                        </td>


                        <td class="text-right button-pad">

                            <a class="btn btn-sm btn-warning" href="{{ URL::to('suppliervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('supplierdownpayments/' . $downpayment->id . '/edit#details') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

@if (0 && $detail->deletable)
                            <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                                    href="{{ URL::to('downpayments/' . $downpayment->id.'/downpaymentdetails/' . $detail->id ) }}" 
                                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                                    data-title="{{ l('Down Payment to Supplier Details') }} :: ({{$detail->id}}) {{ $detail->name }} " 
                                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
@endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
@else
            <div class=" hide  modal-footer">
                <a href="javascript:void(0);" class="btn xbtn-sm btn-success create-downpaymentdetail pull-right" 
                title="{{l('Add New Detail')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

            </div>

            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{l('No records found', [], 'layouts')}}

            </div>
@endif

    <input type="hidden" name="next_detail_line_sort_order" id="next_detail_line_sort_order" value="{{ ($downpaymentdetails->max('line_sort_order') ?? 0) + 10 }}">

</div>


{{-- *************************************** --}}

