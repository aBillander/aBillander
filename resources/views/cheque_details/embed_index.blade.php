
<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{ { URL::to('taxes/'.$tax->id.'/taxrules/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <a href="{{ URL::to('taxes') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Taxes') }}</a --> 
    </div>
    <h2>
        <a href="#">{{ l('Customer Cheque Details') }}</a>
    </h2>        
</div>

<div id="div_chequedetails">

    <div class="table-responsive">

        @if ($chequedetails->count())
            <table id="chequedetails" class="table table-hover">
                <thead>
                <tr>
                    <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                    <th>{{l('Position')}}</th>
                    <th class="text-left">{{ l('Name', 'chequedetails') }}</th>
                    <th class="text-left">{{ l('Amount') }}</th>
                    <th class="text-left">{{ l('Customer Invoice', 'chequedetails') }}</th>
                    <th class="text-right">
                        <a href="{{ URL::to('cheques/'.$cheque->id.'/chequedetails/create') }}" class="btn btn-sm btn-success xxcreate-customerdetail pull-right"
                            title="{{l('Add New Detail')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
                    </th>
                </tr>
                </thead>
                <tbody class="sortable">
                @foreach ($chequedetails as $detail)
                    <tr data-id="{{ $detail->id }}" data-sort-order="{{ $detail->line_sort_order }}">
                        <td>{{ $detail->id }}</td>
                        <td>{{ $detail->line_sort_order }}</td>
                        <td>{{ $detail->name }}</td>

                        <td>{{ $detail->amount > 0.0 ? $detail->as_money_amount('amount') : '-' }}</td>

                        <td>
                            @if( $detail->customer_invoice_id > 0 )
                                <a href="{{ route('customerinvoices.edit', $detail->customer_invoice_id) }}" title="{{l('Go to', 'layouts')}}" target="_blank"> {{ $detail->customer_invoice_reference }} </a>
                            @else
                                {{ $detail->customer_invoice_reference }}
                            @endif

                            {{-- $detail->customerinvoice->document_reference --}}

                        </td>


                        <td class="text-right button-pad">
{{-- --}}
                            <a class="btn btn-sm btn-warning" href="{{ URL::to('cheques/' . $cheque->id.'/chequedetails/' . $detail->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                            <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                                    href="{{ URL::to('cheques/' . $cheque->id.'/chequedetails/' . $detail->id ) }}" 
                                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                                    data-title="{{ l('Customer Cheque Details') }} :: ({{$detail->id}}) {{ $detail->name }} " 
                                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
{{-- --}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="modal-footer">

                <div class="alert alert-warning alert-block" style="text-align: left;">
                    <i class="fa fa-warning"></i>
                    {{l('No records found', [], 'layouts')}}
                </div>

                <a href="{{ URL::to('cheques/'.$cheque->id.'/chequedetails/create') }}" class="btn xbtn-sm btn-success xxcreate-customerdetail pull-right"
                   title="{{l('Add New Detail')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
            </div>
        @endif

    </div>

    {{--
          @include('layouts/modal_mail')
          @include('layouts/modal_delete')
    --}}

</div>
<!-- Customer Users ENDS -->
