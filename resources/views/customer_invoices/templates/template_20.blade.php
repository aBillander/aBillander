<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
    <head>
        <meta charset="utf-8">
        <title>{{ 'Invoice' }}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            h1,h2,h3,h4,p,span,div { font-family: DejaVu Sans; }
        </style>
    </head>
    <body>
        <div style="clear:both; position:relative;">
            <div style="position:absolute; left:0pt; width:250pt;">
                @if ($img = \App\Context::getContext()->company->company_logo)
                    <img class="img-rounded" height="{{ '60' }}" src="{{ URL::to( \App\Company::$company_path . $img ) }}">
                @endif
            </div>
            <div style="margin-left:300pt;">
                <!-- h2>{{ 'Invoice' }} {{-- $cinvoice->document_id > 0 ? '#' . $cinvoice->document_reference : 'BORRADOR' --}}</h2 -->
                <span style="font-size: 24px; margin-bottom: 10px;"><b>Invoice #: </b>  
                                    @if( $cinvoice->document_id > 0 )
                                        {{ $cinvoice->document_reference }}
                                    @else
                                        <span class="small">BORRADOR</span>
                                    @endif
                </span><br />
                <b>Date: </b> {{ $cinvoice->document_date }}<br />
                <b>Order Number: </b> {{ $cinvoice->reference }}<br />
                <b>Agent: </b> {{ $cinvoice->sales_rep_id }}<br />
                <b>Payment: </b> {{ $cinvoice->paymentmethod->name }}<br />
                            
            </div>
        </div>
        <br />
        <div style="clear:both; position:relative;">
            <div style="position:absolute; left:0pt; width:250pt;">
                <h4>Business Details:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        {{ $company->name_fiscal }}<br />
                        VAT ID: {{ $company->identification }}<br />
                        {{ $company->address->address1 }} {{ $company->address->address2 }}<br />
                        {{ $company->address->postcode }} {{ $company->address->city }}<br />
                        {{ $company->address->state->name }}, {{ $company->address->country->name }}<br />
                        {{ $company->address->phone }} / {{ $company->address->email }}<br />

                    </div>
                </div>
            </div>
            <div style="margin-left: 300pt;">
                <h4>Customer Details:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        {{ $cinvoice->customer->name_fiscal }}<br />
                        VAT ID: {{ $cinvoice->customer->identification }}<br />
                        {{ $cinvoice->invoicingAddress->address1 }} {{ $cinvoice->invoicingAddress->address2 }}<br />
                        {{ $cinvoice->invoicingAddress->postcode}} {{ $cinvoice->invoicingAddress->city }}<br />
                        {{ $cinvoice->invoicingAddress->state->name }}, {{ $cinvoice->invoicingAddress->country->name }}<br />
                        {{ $cinvoice->invoicingAddress->phone }} / {{ $cinvoice->invoicingAddress->mail }}<br />

                    </div>
                </div>
            </div>
        </div>
            
@if ($cinvoice->customerInvoiceLines->count()>0)  

        <!-- h4>Items:</h4 -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>SKU</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cinvoice->customerInvoiceLines as $line)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $line->reference }}</td>
                        <td>{{ $line->name }}</td>
                        <td>{{ $line->unit_final_price }}</td>
                        <td>{{ $line->discount_percent/100.0 }} {{ $line->discount_amount_tax_excl }}</td>
                        <td>{{ $line->quantity }}</td>
                        <td>{{ $line->total_tax_excl }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="clear:both; position:relative;">

            <div style="position:absolute; left:0pt; width:250pt;">
                <h4>Taxes:</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tax</th>
                            <th>Base</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cinvoice->customerinvoicetaxes() as $tax)
                        <tr>
                            <td>{{ $tax->percent }} %</td>
                            <td>{{ $tax->taxable_base }}</td>
                            <td>{{ $tax->total_line_tax }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-left: 300pt;">
                <h4>Total:</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><b>Subtotal</b></td>
                            <td>{{ $cinvoice->total_tax_excl }}</td>
                        </tr>
                        <tr>
                            <td>
                                <b>
                                    Taxes 
                                </b>
                            </td>
                            <td>{{ $cinvoice->total_tax_incl - $cinvoice->total_tax_excl }}</td>
                        </tr>
                        <tr>
                            <td><b>TOTAL</b></td>
                            <td><b>{{ $cinvoice->total_tax_incl }}</b></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        {{-- Gorrino style programming here :-( (but works!) --}}
                        @for ($i = 0; $i < (count( $cinvoice->customerinvoicetaxes() )-2); $i++)
                        <tr style="visibility:hidden">
                            <td> &nbsp; </td>
                            <td> &nbsp; </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

        </div>

@endif

        <div style="clear:both; position:relative;">
            
                <div style="position:absolute; left:0pt; width:100%;">
                    <h4>Notes:</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ $cinvoice->notes_to_customer }}
                        </div>
                    </div>
                </div>
            
            <div style="margin-left: 300pt;">
            </div>
        </div>

        @if (isset($cinvoice->text))
            <br /><br />
            <div class="well">
                {{ $cinvoice->text }}
            </div>
        @endif
    </body>
</html>
