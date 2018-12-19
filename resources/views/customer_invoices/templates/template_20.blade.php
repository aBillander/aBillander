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
                <!-- h2>{{ 'Invoice' }} {{-- $document->document_id > 0 ? '#' . $document->document_reference : 'BORRADOR' --}}</h2 -->
                <span style="font-size: 24px; margin-bottom: 10px;"><b>Invoice #: </b>  
                                    @if( $document->document_id > 0 )
                                        {{ $document->document_reference }}
                                    @else
                                        <span class="small">BORRADOR</span>
                                    @endif
                </span><br />
                <b>Date: </b> {{ $document->document_date }}<br />
                <b>Order Number: </b> {{ $document->reference }}<br />
                <b>Agent: </b> {{ $document->sales_rep_id }}<br />
                <b>Payment: </b> {{ $document->paymentmethod->name }}<br />
                            
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
                        
                        {{ $document->customer->name_fiscal }}<br />
                        VAT ID: {{ $document->customer->identification }}<br />
                        {{ $document->invoicingAddress->address1 }} {{ $document->invoicingAddress->address2 }}<br />
                        {{ $document->invoicingAddress->postcode}} {{ $document->invoicingAddress->city }}<br />
                        {{ $document->invoicingAddress->state->name }}, {{ $document->invoicingAddress->country->name }}<br />
                        {{ $document->invoicingAddress->phone }} / {{ $document->invoicingAddress->mail }}<br />

                    </div>
                </div>
            </div>
        </div>
            
@if ($document->documentlines->count()>0)  

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
                @foreach ($document->documentlines as $line)
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
                        @foreach ($document->documenttaxes() as $tax)
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
                            <td>{{ $document->total_tax_excl }}</td>
                        </tr>
                        <tr>
                            <td>
                                <b>
                                    Taxes 
                                </b>
                            </td>
                            <td>{{ $document->total_tax_incl - $document->total_tax_excl }}</td>
                        </tr>
                        <tr>
                            <td><b>TOTAL</b></td>
                            <td><b>{{ $document->total_tax_incl }}</b></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        {{-- Gorrino style programming here :-( (but works!) --}}
                        @for ($i = 0; $i < (count( $document->documenttaxes() )-2); $i++)
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
                            {{ $document->notes_to_customer }}
                        </div>
                    </div>
                </div>
            
            <div style="margin-left: 300pt;">
            </div>
        </div>

        @if (isset($document->text))
            <br /><br />
            <div class="well">
                {{ $document->text }}
            </div>
        @endif
    </body>
</html>
