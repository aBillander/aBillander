@extends('abcc.layouts.master')

@section('title') {{ l('Customer Invoices - Show') }} @parent @endsection


@section('content')

<style>	

#invoice p  {
	margin: 0 0 10px;
}

#invoice {
	margin: auto;
	width: 100%;	
	width: 780px;	
}

.invoice_footer {
	position: fixed;
	bottom: 0px;
	left: 0px;
	right: 0px;
	height: 40px;
}
.invoice_footer p {
	margin-bottom: 0;
	font-size: 12px;
	font-family: 'Dosis', sans-serif;
}

.h1, .h2, .h3, .h4 {
	margin: 10px 0 10px 0;
	text-transform: uppercase;
	font-family: 'Dosis', sans-serif;
}
.h1 	{font-size: 36px; text-transform: uppercase;}
.h2 	{font-size: 30px}
.h3 	{font-size: 24px}
.h4 	{font-size: 18px}

.details {
	font-family: sans-serif;
	font-size: 12px;
    margin-bottom: 4px;
}

table {
	border: 0px;
	width: 100%;
	border-collapse: collapse;
    border-spacing: 0;
}
table th {
	background: none repeat scroll 0 0 #dbdbdb;
	font-family: 'Dosis', sans-serif;
}
table > thead > tr > th,
table > tbody > tr > th,
table > tfoot > tr > th,
table > thead > tr > td,
table > tbody > tr > td,
table > tfoot > tr > td {
	padding: 4px;	
	line-height: 1.42857;
	vertical-align: top;    
}
table tr > th {
    background-color: #EFEFEF;
    text-align: center !ximportant;
    text-transform: uppercase;
	font-size: 12px;
	vertical-align: center !important;
}
table td {
	font-family: sans-serif;
	font-size: 12px;
	padding: 4px;
	line-height: 1;
}
table.border th,
table.border td {
	border: 1px solid #999999;
	padding: 8px;	
}

.table-striped > tbody > tr:nth-child(2n+1) {
    background-color: #f9f9f9;
}

.crt {
	width: 20px;
}
.product {
	width: 300px;
}
.qty {
	width: 60px;
}
.small { 
	width: 80px;
}


.col-md-12 {
    width: 100%;
}
.col-md-11 {
    width: 91.6667%;
}
.col-md-10 {
    width: 83.3333%;
}
.col-md-9 {
    width: 75%;
}
.col-md-8 {
    width: 66.6667%;
}
.col-md-7 {
    width: 58.3333%;
}
.col-md-6 {
    width: 50%;
}
.col-md-5 {
    width: 41.6667%;
}
.col-md-4 {
    width: 33.3333%;
}
.col-md-3 {
    width: 25%;
}
.col-md-2 {
    width: 16.6667%;
}
.col-md-1 {
    width: 8.33333%;
}

.bg-white		{background: white !important;}
.top10 			{margin-top: 10px;}
.top20 			{margin-top: 20px;}
.text-left 		{text-align: left;}
.text-center 	{text-align: center;}
.text-right	 	{text-align: right;}

</style>

	<div class="container top20">
	<div class="row thumbnail">
		<div id="invoice">
			<div class="col-md-6">
				<h1 class="uppercase">
				<!-- 
				@ if ($img = AbiContext::getContext()->company->company_logo)
					<img src="{ { URL::to( AbiCompany::imagesPath() . $img ) } }" class="img-responsive thumbnail">
				@ endif 
				-->
				<img src="http://localhost/aBillander55/public/uploads/company/1510135936.png" class="img-responsive thumbnail">
				</h1>
			</div>
			
			<div class="col-md-2">
				<span class="label label-info ">{{ \App\CustomerInvoice::getStatusList()[ $cinvoice->status ] }}</span>
			</div>
			
			<div class="col-md-4">
				<h1 class="uppercase">
					Factura
				</h1>
				
				<table class="table">
					<tr>
						<th class="col-md-6 text-center">Núm.:</th>
						<th class="col-md-6 text-center">Fecha:</th>
					</tr>

					<tr>						
						<td class="text-center">@if( $cinvoice->document_id > 0 )
							{{ $cinvoice->document_reference }}
							@else
								<span class="small">BORRADOR</span>
							@endif
						</td>
						<td class="text-center">{{ abi_date_short($cinvoice->document_date) }}</td>
					</tr>
				</table>				
			</div>

			<div class="col-md-6 top20">	
				<h2>{{ $company->name_fiscal }}</h2>
				<p class="details">NIF/CIF: {{ $company->identification }}</p>
				<p class="details">{{ $company->address->address1 }} {{ $company->address->address2 }}</p>
				<p class="details">{{ $company->address->city }}, {{ $company->address->postcode }} {{ $company->address->state->name }}, {{ $company->address->country->name }}</p>
				<p class="details"></p>
				<p class="details">{{ $company->address->phone }} / {{ $company->address->email }}</p>
				<!-- p class="details">{ { $owner->bank }}</p>
				<p class="details">{ { $owner->bank_account }}</p -->
			</div>
			
			<div class="col-md-6 top20">
				<h2>Cliente: <span class="h4">{{ $cinvoice->customer->name_fiscal }}</span></h2>
				<p class="details">NIF/CIF: {{ $cinvoice->customer->identification }}</p>
				<p class="details">{{ $cinvoice->invoicingAddress->address1 }} {{ $cinvoice->invoicingAddress->address2 }}</p>
				<p class="details">{{ $cinvoice->invoicingAddress->city }}, {{ $cinvoice->invoicingAddress->postcode}} {{ $cinvoice->invoicingAddress->state->name }}, {{ $cinvoice->invoicingAddress->country->name }}</p>
				<p class="details">{{-- $cinvoice->invoicingAddress->firstname } } { { $cinvoice->invoicingAddress->lastname --}}</p>
				<p class="details">{{ $cinvoice->invoicingAddress->phone }} &nbsp; {{ $cinvoice->invoicingAddress->mail }}</p>
				<!-- p class="details">{ { $invoice->bank }}</p>
				<p class="details">{ { $invoice->bank_account }}</p -->				
			</div>
			
			<div class="col-md-12 top20">
			
				@if ($cinvoice->customerInvoiceLines->count()>0)  
			
					<div class="table-responsive">
					<table class="table table-striped">
					<thead>
						<tr>
							<th>Ref.</th>
							<th>Item</th>
							<th class="small">Cantidad</th>
							<th class="small">Precio</th>
							<th class="small">Descuento</th>
							<th class="small">Impuesto</th>
							<th class="small">Total</th>
						</tr>
					</thead>
					
					<tbody>
						<?php $subTotalItems 	= 0;?>
						<?php $taxItems 		= 0;?>
						<?php $discountItems	= 0;?>
						<?php $invoiceDiscount	= 0;?>
						
						@foreach ($cinvoice->customerInvoiceLines as $line)
						
							<tr>
								<td>
									{{ $line->reference }}
								</td>
								
								<td>
									{{ $line->name }}
								</td>
								
								<td class="small">
									{{ $line->quantity }}
								</td>
								
								<td class="small">
									{{ $line->unit_final_price }}
								</td>
								
								<td class="small">
									{{ $line->discount_percent }} %
								</td>
								
								<td class="small">
									{{ \App\Tax::find($line->tax_id)->percent }} %
								</td>
								
								<td class="small">
									{{ $line->total_tax_incl }}
								</td>							
							</tr>
							
							@if ($line->notes)
							<tr>
								<td colspan="7">
									{{ $line->notes }}
								</td>
							</tr>
							@endif
					{{--  	
							< ?p hp $subTotalItems 	+= $v->quantity * $v->price;?>
							< ?p hp $taxItems 		+= ($v->quantity * $v->price) * ($v->tax / 100);?>							
							< ?p hp $discountItems 	+= $v->discount_value;?>		
					--}}			
						@endforeach
					{{--	
						< ?p hp if ($invoice->type == 2) { ?>
							< ?p hp $invoiceDiscount		= $invoice->discount;?>
						< ?p hp } ?>
						
						< ?p hp if ($invoice->type == 2) { ?>
							< ?p hp $invoiceDiscount		= ($subTotalItems + $taxItems - $discountItems) * ( $invoice->discount / 100); ?>
						< ?p hp } ?>	
					--}}	
					</tbody>	
					
					<tfoot>
						<tr class="bg-white">
							<td colspan="4" class="vcenter text-center">
								Gracias por su compra!
							</td>
							
							<td colspan="3" class="total">
								<div class="form-group top10">Sub-Total: 
									{{ $cinvoice->total_tax_excl }} 
								</div>
								
								<div class="form-group">Impuestos: 
									{{ $cinvoice->total_tax_incl - $cinvoice->total_tax_excl }}
								</div>
						{{--
								@if ( $discountItems != 0 )
									<div class="form-group">{{ trans('invoice.discount') }}: 
										- {{ $invoice->position == 1 ? $invoice->currency : '' }} {{ number_format($discountItems, 2, '.', '') }} {{ $invoice->position == 2 ? $invoice->currency : '' }}
									</div>
								@endif
						--}}		
								@if ( $cinvoice->total_discounts_tax_incl != 0 )
									<div class="form-group">Descuento ({{ $cinvoice->document_discount }}%): 
										{{ $cinvoice->total_discounts_tax_incl }}
									</div>
								@endif								
								
								<h4 class="form-group">TOTAL: 
									{{ $cinvoice->total_tax_incl }}
								</h4>
							</td>
						</tr>	
					</tfoot>	
					</table>
					</div>
					
				@else
				

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
					
				@endif				
				
			</div>
		</div>
		
<!-- -->
		
	</div>
	</div>
	
	<div class="container">
	<div class="row">
		<div class="col-md-12">
			
	        <a href="{{ route('abcc.invoices.index') }}" class="btn btn-sm btn-default pull-right"><i class="fa fa-mail-reply"></i> {{l('Back to Customer Invoices')}}</a>

	        <a class="btn btn-primary" href="{{ route('abcc.invoice.pdf',  ['invoiceKey' => $cinvoice->secure_key]) }}" title="{{l('Download', [], 'layouts')}}" target="_blank"><i class="fa fa-file-pdf-o"></i> {{l('PDF Export', [], 'layouts')}}</a>

			<!-- button class="btn btn-info" data-toggle="modal" data-target="#sendEmailCustomerInvoice">
				<i class="fa fa-envelope"></i> {{l('Send', [], 'layouts')}}
			</button -->
			
		</div>
	</div>	
	</div>


<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="sendEmailCustomerInvoice" class="modal fade">
	<div class="modal-dialog">
	    <div class="modal-content">

	        <div class="modal-header">
	            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
	            <h4 class="modal-title">{{l('Send eMail', [], 'layouts')}}</h4>
	        </div>

            <div class="modal-body">

               <div id="modal-status-placeholder"></div>
				
{!! Form::open(array('url' => 'customerinvoices/sendemail', 'id' => 'form_sendEmailCustomerInvoice', 'name' => 'form_sendEmailCustomerInvoice', 'class' => 'form')) !!}
               
               <fieldset>
               	  <input type="hidden" id="invoice_id" name="invoice_id" value="{{$cinvoice->id}}"/>
               <div class="form-group">
                  <label class="control-label">{{l('Subject', [], 'layouts')}}:</label>
                  <?php $num = ($cinvoice->document_id > 0 ?
							$cinvoice->document_reference :
							l('DRAFT', [], 'layouts') ); ?>
                  {!! Form::text('email_subject', l('Su factura :num de :name', ['num' => $num, 'name' => $company->name_fiscal]), array('id' => 'email_subject', 'class' => 'form-control', 'autocomplete' => 'off')) !!}
               </div>

               <div class="form-group">
                  <label class="control-label">{{l('Message', [], 'layouts')}}:</label>
                  <textarea id="email_body" class="form-control" name="email_body" rows="5"></textarea>
               </div>

            </fieldset>
            </div>

	        <div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button"> {{l('Cancel', [], 'layouts')}} </button>
				<button class="btn btn-info pull-right" type="submit" onclick="this.disabled=true;this.form.submit();">
					<span class="fa fa-envelope"></span> &nbsp; {{l('Send', [], 'layouts')}}
				</button>							
	        </div>
						
{!! Form::close() !!}	

	    </div>
	</div>
</div>

{{-- Payments --}}

	<div class="col-md-10 col-md-offset-1" style="margin-top: 40px; margin-bottom: 40px;">
		<div class="panel panel-primary" id="panel_invoice_profit">
            <div class="panel-heading">
               <h3 class="panel-title">
    	           {{l('Payment Schedule')}}
               </h3>
            </div>

               <div class="well well-sm" style="background-color: #d9edf7; border-color: #bce8f1; color: #3a87ad;">
	               <b>{{l('Invoice')}}</b>: {{$cinvoice->document_reference}} <br>
	               <b>{{l('Customer')}}</b>: <a href="{$fsc->factura->cliente_url()}">{{ $cinvoice->customer->name_fiscal }}</a><br>
               </div>

            <div class="panel-body">

@if ($cinvoice->payments->count())
<table id="payments" name="payments" class="table table-hover">
	<thead>
		<tr>
			<th style="text-transform: none;" class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th style="text-transform: none;">{{l('Subject')}}</th>
			<th style="text-transform: none;">{{l('Due Date')}}</th>
			<th style="text-transform: none;">{{l('Payment Date')}}</th>
			<th style="text-transform: none;">{{l('Amount')}}</th>
            <th style="text-transform: none;" class="text-center">{{l('Status', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($cinvoice->payments as $payment)
		<tr>
			<td>{{ $payment->id }}</td>
			<td>{{ $payment->name }}</td>
			<td @if( !$payment->payment_date AND ( $payment->due_date < \Carbon\Carbon::now() ) ) class="danger" @endif>
				{{ $payment->due_date }}</td>
			<td>{{ $payment->payment_date }}</td>
			<td>{{ $payment->amount }}</td>
            <td class="text-center">
            	@if     ( $payment->status == 'pending' )
            		<span class="label label-info">
            	@elseif ( $payment->status == 'bounced' )
            		<span class="label label-danger">
            	@elseif ( $payment->status == 'paid' )
            		<span class="label label-success">
            	@else
            		<span>
            	@endif
            	{{l( $payment->status, [], 'appmultilang' )}}</span></td>

			<td class="text-right">
                <!--
                @if ( $payment->status == 'paid' )
                	<a class="btn btn-sm btn-danger" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('customerinvoices/' . $cinvoice->id . '#payments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
            	@else
                	<a class="btn btn-sm btn-warning" href="{{ URL::to('customervouchers/' . $payment->id  . '/edit?back_route=' . urlencode('customerinvoices/' . $cinvoice->id . '#payments') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
            	@endif
            	-->
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
	</div>

@endsection

{{--

		<div class="col-md-12">
			<h3>{{ trans('invoice.list_of_payments') }}</h3>
			
			<div class="table-responsive">
			<table class="table table-striped">			
			<thead>
				<tr>
					<th>{{ trans('invoice.crt') }}.</th>
					<th class="col-md-4">{{ trans('invoice.amount_paid') }}</th>
					<th class="col-md-4">{{ trans('invoice.date') }}</th>
					<th class="col-md-4">{{ trans('invoice.payment_method') }}</th>
				</tr>				
			</thead>

			<tbody>	
			
				@if ($invoicePayments)
					@foreach($invoicePayments as $crt => $p)
					
						<tr>
							<td>
								{{ $crt + 1 }}
							</td>				
					
							<td>
								{{ $p->payment_amount }} {{ $invoice->currency }}
							</td>

							<td>
								{{ $p->payment_date }}
							</td>						
					
							<td>
								{{ $p->name }}
							</td>				
						</tr>
						
					@endforeach
				@else
				
					<tr>
						<td colspan="4">
							{{ trans('invoice.no_data') }}
						</td>				
					</tr>
					
				@endif
				
			</tbody>	
			</table>
			</div>
		</div>
		
		@if (!Request::segment(3))
		
			<div class="col-md-12">
				<h3>{{ trans('invoice.invoice_extra_information') }}</h3>
				<p class="top10">{{ $invoice->description }}</p>
			</div>		
		
			@if (isset($invoiceSettings->text))
			
				<div class="col-md-12">
					<h4>{{ trans('invoice.invoice_personal_description') }}</h4>
					{{ $invoiceSettings->text }}
				</div>
				
			@endif
		@endif

--}}