<?php 

namespace App\Traits;

use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceLine;
use App\Models\CustomerInvoiceLineTax;

use App\Models\Configuration;
use App\Helpers\DocumentAscription;

trait BillableInvoiceableTrait
{
    
    public function toInvoice()
    {
        // 

        // Header
        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
//            'user_id' => $this->,

            'sequence_id' => $this->customer->sequence_id ?? Configuration::getInt('DEF_CUSTOMER_INVOICE_SEQUENCE'),

            'created_via' => 'manual',

            'document_date' => \Carbon\Carbon::now(),

            'payment_date' => $this->payment_date,

            'delivery_date' => $this->delivery_date,
            'delivery_date_real' => $this->delivery_date_real,

            'document_discount_percent' => $this->document_discount_percent,
            'document_discount_amount_tax_incl' => $this->document_discount_amount_tax_incl,
            'document_discount_amount_tax_excl' => $this->document_discount_amount_tax_excl,

            'currency_conversion_rate' => $this->currency_conversion_rate,
            'down_payment' => $this->down_payment,

            'total_currency_tax_incl' => $this->total_currency_tax_incl,
            'total_currency_tax_excl' => $this->total_currency_tax_excl,
            'total_currency_paid' => $this->total_currency_paid,

            'total_tax_incl' => $this->total_tax_incl,
            'total_tax_excl' => $this->total_tax_excl,

            'commission_amount' => $this->commission_amount,

            // Skip notes

            'status' => 'draft',
            'locked' => 0,

            'invoicing_address_id' => $this->invoicing_address_id,
            'shipping_address_id' => $this->shipping_address_id,
            'warehouse_id' => $this->warehouse_id,
            'shipping_method_id' => $this->shipping_method_id ?? $this->customer->getShippingMethodId(),
//            'carrier_id' => $this->carrier_id,
            'sales_rep_id' => $this->sales_rep_id,
            'currency_id' => $this->currency_id,
            'payment_method_id' => $this->payment_method_id ?? $this->customer->payment_method_id ?? Configuration::getInt('DEF_CUSTOMER_PAYMENT_METHOD'),
            'template_id' => $this->customer->invoice_template_id ?? Configuration::getInt('DEF_CUSTOMER_INVOICE_TEMPLATE'),
        ];

        // Model specific data
        $extradata = [
            'type' => 'invoice',
            'payment_status' => 'pending',
        ];


        // Let's get dirty
        CustomerInvoice::unguard();
        $invoice = CustomerInvoice::create( $data + $extradata );
        CustomerInvoice::reguard();


        // Lines stuff
        if ( $this->lines()->count() )
        foreach ($this->lines as $line) {

            $invoice_line = $this->lineToInvoiceLine( $line );

            $invoice->lines()->save($invoice_line);

            if ( $line->taxes()->count() )
                foreach ($line->taxes as $linetax) {

                    $invoice_line_tax = $this->lineTaxToInvoiceLineTax( $linetax );

                    $invoice_line->taxes()->save($invoice_line_tax);

                }
        }

        // Good boy, so far

        // Not so fast, Sony Boy
        // Payment status?
        // 'payment_status'  'next_due_date' 'open_balance'     'parent_id'
        if ( $invoice->down_payment > 0 ) $invoice->payment_status = 'halfpaid';
        if ( $invoice->down_payment >= $invoice->total_currency_tax_incl ) $invoice->payment_status = 'paid';

        if ( $invoice->payment_status != 'pending' )
        {
            // Some payments are in place

            // Confirm invoice

            // Make payment
            
        }



        // Document traceability
        //     leftable  is this document
        //     rightable is Customer Invoice Document
        $link_data = [
            'leftable_id'    => $this->id,
            'leftable_type'  => $this->getClassName(),

            'rightable_id'   => $invoice->id,
            'rightable_type' => CustomerInvoice::class,

            'type' => 'traceability',
            ];

        $link = DocumentAscription::create( $link_data );


        return $invoice;
    }

    public function lineToInvoiceLine( $line )
    {
        // Common data
        $data = [
        ];

        $data = $line->toArray();
        // id
        unset( $data['id'] );
        // Parent document
        unset( $data[$this->getClassSnakeCase().'_id'] );

        // Model specific data
        $extradata = [
        ];


        // Let's get dirty
        CustomerInvoiceLine::unguard();
        $invoice_line = new CustomerInvoiceLine::create( $data + $extradata );
        CustomerInvoiceLine::reguard();

        return $invoice_line;
    }

    public function lineTaxToInvoiceLineTax( $linetax )
    {
        // Common data
        $data = [
        ];

        $data = $linetax->toArray();
        // id
        unset( $data['id'] );
        // Parent document
        unset( $data[$this->getClassSnakeCase().'_line_id'] );

        // Model specific data
        $extradata = [
        ];


        // Let's get dirty
        CustomerInvoiceLineTax::unguard();
        $invoice_line_tax = new CustomerInvoiceLineTax::create( $data + $extradata );
        CustomerInvoiceLineTax::reguard();

        return $invoice_line_tax;
    }

    
    public function toInvoiceAndConfirm()
    {
        // 
        $invoice = $this->toInvoice();

        // Todo: check if possible (payments, etc.)
        $invoice->confirm();

        return $invoice;
    }
}