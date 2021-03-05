

<div id="div_cheque_details">

   <div class="table-responsive">

@if ($chequedetails->count())
            <table id="chequedetails" class="table table-hover">
                <thead>
                <tr>
                    <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                    <th>{{l('Position', 'layouts')}}</th>
                    <!-- th class="text-left">{{ l('Name', 'chequedetails') }}</th -->
                    <th class="text-left">{{ l('Amount') }}<br />
                        <span class="

@if( $open_balance == 0.0)
                        alert-success
@else
                        alert-danger
@endif
                        ">{{ $cheque->as_money_amountable( $chequedetails->sum('amount'), $cheque->currency ) }}</span>
                    </th>
                    <th class="text-left">{{ l('Customer Invoice', 'chequedetails') }}</th>
                    <th class="text-left">{{ l('Customer Voucher', 'customervouchers') }}</th>
                    <th class="text-center">{{l('Status', [], 'layouts')}}</th>
                    <th>{{l('Due Date')}}</th>
                    <th>{{l('Payment Date')}}</th>
                    <th class="text-right">

@if( $open_balance > 0.0)
                        <a href="{{ URL::to('cheques/'.$cheque->id.'/chequedetails/create') }}" class="btn btn-sm btn-success create-chequedetail pull-right"
                            title="{{l('Add New Detail')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
@endif
                    </th>
                </tr>
                </thead>
                <tbody class="sortable">
                @foreach ($chequedetails as $detail)
                    <tr data-id="{{ $detail->id }}" data-sort-order="{{ $detail->line_sort_order }}">
                        <td>{{ $detail->id }} {{-- $detail->customerpayment->cheque->id --}}</td>
                        <td>{{ $detail->line_sort_order }}</td>
                        <!-- td>{{ $detail->name }}</td -->

                        <td>{{ $detail->amount > 0.0 ? $detail->as_money_amount('amount') : '-' }}</td>

                        <td>
                            @if( $detail->customer_invoice_id > 0 )
                                <a href="{{ route('customerinvoices.edit', $detail->customer_invoice_id) }}" title="{{l('Go to', 'layouts')}}" target="_blank"> {{ $detail->customer_invoice_reference }} </a>
                            @else
                                {{ $detail->customer_invoice_reference }}
                            @endif

                            {{-- $detail->customerinvoice->document_reference --}}

                        </td>

                        <td>
@if($detail->customerpayment)
                            [{{ optional($detail->customerpayment)->id }}] {{ optional($detail->customerpayment)->name }} 
                          ({{ optional($detail->customerpayment)->as_money_amount_with_sign('amount') }})
                          <a class="btn btn-xs btn-warning" href="{{ URL::to('customervouchers/' . $detail->payment_id . '/edit' ) }}" title="{{l('Go to', [], 'layouts')}}" target="_blank"><i class="fa fa-external-link"></i></a>
@else
                        -
@endif
                        </td>

                            <td class="text-center button-pad">
                                @if     ( optional($detail->customerpayment)->status == 'pending' )
                                    <span class="label label-info">
                                @elseif ( optional($detail->customerpayment)->status == 'bounced' )
                                    <span class="label label-danger">
                              @elseif ( optional($detail->customerpayment)->status == 'paid' )
                                <span class="label label-success">
                              @elseif ( optional($detail->customerpayment)->status == 'uncollectible' )
                                <span class="label alert-danger">
                                @else
                                    <span class="label">
                                @endif
                                {{\App\Payment::getStatusName(optional($detail->customerpayment)->status)}}</span>

                        </td>

                        <td>{{ abi_date_short(optional($detail->customerpayment)->due_date) }}</td>

                        <td>{{ abi_date_short(optional($detail->customerpayment)->payment_date) }}</td>


                        <td class="text-right button-pad">
{{-- --}}
                            <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('cheques/' . $cheque->id.'/chequedetails/' . $detail->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->
@if ($detail->deletable)
                            <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                                    href="{{ URL::to('cheques/' . $cheque->id.'/chequedetails/' . $detail->id ) }}" 
                                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                                    data-title="{{ l('Customer Cheque Details') }} :: ({{$detail->id}}) {{{ $detail->name }}} " 
                                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
@endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
@else
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn xbtn-sm btn-success create-chequedetail pull-right" 
                title="{{l('Add New Detail')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

            </div>

            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{l('No records found', [], 'layouts')}}

            </div>
@endif

    <input type="hidden" name="next_detail_line_sort_order" id="next_detail_line_sort_order" value="{{ ($chequedetails->max('line_sort_order') ?? 0) + 10 }}">

</div>


{{-- *************************************** --}}

