
      <table class="table table-condensed">
         <thead>
            <tr>
               <th class="text-left" style="width: 60px"></th>
               <th class="text-left">{{l('Reference')}}</th>
               <th class="text-left">{{l('Description')}}</th>
               <th class="text-right" width="90">{{l('Quantity')}}</th>
               <th></th>
               <th class="text-right">{{l('Price')}}</th>
               <th class="text-right">{{l('With Tax')}}</th>
               <th class="text-right" width="90">{{l('Disc. %')}}</th>
               <th class="text-center">{{l('Net')}}</th>
               <th class="text-right" width="90">{{l('Disc.')}}</th>
               <th class="text-right" width="115">{{l('Tax')}}</th>
               <th class="text-right">{{l('Total')}}</th>
               <th class="text-right">{{l('R.E.')}}</th>
            </tr>
         </thead>
         <tbody id="order_lines">
            @if ( count($invoice->customerInvoiceLines) > 0 )
               @foreach ( $invoice->customerInvoiceLines as $i => $line )

                  @if ( $line->locked )

                      @include('customer_invoices._invoice_line')

                  @endif

               @endforeach

               @foreach ( $invoice->customerInvoiceLines as $i => $line )

                  @if ( !$line->locked )

                      @include('customer_invoices._invoice_line')

                  @endif

               @endforeach
            @endif
         </tbody>
         <tbody>
            <tr class="bg-info">
               <td>
               </td>
               <td>
                     <button id="i_new_line" class="btn btn-sm btn-primary" type="button">
                        <i class="fa fa-plus"></i>
                        &nbsp; {{l('New line...')}}
                     </button>

               </td>
               <td colspan="5" class="text-right" style="vertical-align: middle;">{{l('Order Discount (%)')}}: </td>
               <td class="{{ $errors->has('document_discount') ? 'has-error' : '' }}" style="background-color: #fff;">
                    
                    {!! Form::text('document_discount', null, array('class' => 'form-control', 'id' => 'document_discount', 'onchange' => 'calculate_document()', 'onkeyup' => 'calculate_document()', 'onclick' => 'this.select()')) !!}

                    {{ $errors->first('document_discount',  '<span class="help-block">:message</span>') }}


                    {!! Form::hidden('order_gross_tax_excl', null, array('id' => 'order_gross_tax_excl')) !!}
                    {!! Form::hidden('order_gross_taxes',    null, array('id' => 'order_gross_taxes'))    !!}
                    {!! Form::hidden('order_gross_tax_incl', null, array('id' => 'order_gross_tax_incl')) !!}
               </td>
               <td>
                  {!! Form::text('order_total_tax_excl', null, array('class' => 'form-control text-right', 'id' => 'order_total_tax_excl', 'style' => 'font-weight: bold;', 'onfocus' => 'this.blur();')) !!}
               </td>
               <td>

               </td>
               <td>
                  {!! Form::text('order_total_taxes', null, array('class' => 'form-control text-right', 'id' => 'order_total_taxes', 'style' => 'font-weight: bold;', 'onfocus' => 'this.blur();')) !!}
               </td>
               <td>
                  {!! Form::text('order_total_tax_incl', null, array('class' => 'form-control text-right', 'id' => 'order_total_tax_incl', 'style' => 'font-weight: bold;', 'onfocus' => 'this.blur();')) !!}
               </td>
               <td>
               </td>
            </tr>
         </tbody>
      </table>

@section('scripts') 

@parent

<script type="text/javascript">

    $(document).ready(function() {
        $( "tr.locked > td > input" ).on( "click", function( event ) {
     
              event.preventDefault();

              $(this).blur();
        });
    }); 

    $(document).ready(function() {
        $( "tr.locked > td > textarea" ).on( "click", function( event ) {
     
              event.preventDefault();

              $(this).blur();
        });
    }); 

</script>

@stop