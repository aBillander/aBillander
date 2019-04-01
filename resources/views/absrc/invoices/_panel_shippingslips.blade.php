

	 <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h4 class="modal-title">{{ l('Show Shipping Slips') }} :: <label class="label label-default">{{ $document->document_reference }}</label></h4>
	 </div>

	 <div class="modal-body">

       <div class="table-responsive">

<table id="payments" name="payments" class="table table-hover">
	<thead>
        <tr>
            <th class="text-left">{{ l('ID #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Delivery Date') }}</th>
            <th class="text-left">{{ l('Deliver to') }}</th>
            <th class="text-right">{{ l('Items') }}</th>
            <th class="text-right">{{ l('Total') }}</th>
            <th class="text-center">{{ l('Notes') }}</th>
        </tr>
	</thead>
	<tbody>
@if ($document->shippingslips->count())


	@foreach ($document->shippingslips as $document)
        <tr>
            <td>
                <a href="{{ route('abcc.shippingslip.pdf', [$document->id]) }}" title="{{l('Show', [], 'layouts')}}" target="_blank">
                @if ($document->document_id>0)
                {{ $document->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif
                </a>
                </td>
            <td>{{ abi_date_short($document->document_date) }}</td>
            <td>{{ abi_date_short($document->delivery_date_real ?: $document->delivery_date) }}</td>
            <td>
                @if ( $document->hasShippingAddress() || 1)



                {{ $document->shippingaddress->alias }} 
                 <a href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" data-content="{{ $document->shippingaddress->firstname }} {{ $document->shippingaddress->lastname }}<br />{{ $document->shippingaddress->address1 }}<br />{{ $document->shippingaddress->city }} - {{ $document->shippingaddress->state->name }} <a href=&quot;javascript:void(0)&quot; class=&quot;btn btn-grey btn-xs disabled&quot;>{{ $document->shippingaddress->phone }}</a>" data-original-title="" title="">
                        <i class="fa fa-address-card-o"></i>
                    </button>
                 </a>
      

                @endif
            </td>
            <td class="text-right">{{ $document->lines->count() }}</td>
            <td class="text-right">{{ $document->as_money_amount('total_tax_excl') }}</td>
            <td class="text-center">@if ($document->notes_from_customer)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{!! nl2br($document->notes_from_customer) !!}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
            </td>
        </tr>
	@endforeach

@else
    <tr><td colspan="10">
	<div class="alert alert-warning alert-block">
	    <i class="fa fa-warning"></i>
	    {{l('No records found', [], 'layouts')}}
	</div>
    </td>
    <td></td></tr>
@endif

	</tbody>
</table>



		</div>	 



	 </div><!-- div class="modal-body" ENDS -->

	<div class="modal-footer">

	   <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Back', [], 'layouts')}}</button>

	   <!-- button type="submit" class="btn btn-success" name="modal_edit_document_line_productSubmit" id="modal_edit_document_line_productSubmit">
	    <i class="fa fa-thumbs-up"></i>
	    &nbsp; {{l('Update', [], 'layouts')}}</button -->

	</div>
