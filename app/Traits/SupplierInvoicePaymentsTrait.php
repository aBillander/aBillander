<?php 

namespace App\Traits;

use App\Models\Configuration;
use App\Models\Payment;

trait SupplierInvoicePaymentsTrait
{
    
    public function makePaymentDeadlines()
    {
        // abi_r(( $this->status != 'confirmed' && $this->payment_status != 'pending' ), true);

        if ( ($this->status != 'closed') && ($this->payment_status != 'pending') ) {

            // Not allowed

            return false;
            
        }

        // Down Payments
        foreach ($this->payments->where('is_down_payment', '>', 0) as $dpayment) {
            # Unlink Invoice
            $dpayment->paymentable_id = 0;
            $dpayment->paymentable_type = '';
            $dpayment->document_reference = null;
            
            $dpayment->save();

            $dpayment->downpayment->update(['status' => 'pending']);
        }

        // Regular Vouchers
        $this->payments()->where('is_down_payment', 0)->delete();


        // Clean record so far.
        // Lets start it over

        // Apply Down Payments
        $total_down_payment = 0.0;
        foreach ($this->downpayments->where('currency_id', $this->currency_id) as $downpayment) {
            # code...

            foreach ($downpayment->vouchers as $payment) {
                # code...
                $payment->document_reference = $this->document_reference;
                $this->payments()->save($payment);

                $total_down_payment += $payment->amount;
            }            

            $payment->downpayment->update(['status' => 'applied']);

        }


        $ototal = $this->as_priceable( $this->total_tax_incl - $total_down_payment );
        
        if ($ototal == 0.0)
        {
            // Same as: $document->checkPaymentStatus();
            $this->payment_status = 'paid';
            $this->open_balance = $ototal;
            $this->save();


            return true;
        }


        $ptotal = 0;
        $pmethod = $this->paymentmethod;
        $dlines = $pmethod->deadlines;
        // $pdays = $this->customer->paymentDays();     <= Not used in this method
        // $base_date = \Carbon\Carbon::createFromFormat( Context::getContext()->language->date_format_lite, $this->document_date );
        $base_date = $this->document_date;

        for($i = 0; $i < count($pmethod->deadlines); $i++)
        {
            $next_date = $base_date->copy()->addDays($dlines[$i]['slot']);

            // Calculate installment due date
            // $due_date = $this->customer->paymentDate( $next_date );
            // ^-- Should use Company Payment Days!
            $due_date = $next_date;

            if ( $i != (count($pmethod->deadlines)-1) ) {
                $installment = $this->as_priceable( $ototal * $dlines[$i]['percentage'] / 100.0, $this->currency, true );
                $ptotal += $installment;
            } else {
                // Last Installment
                $installment = $ototal - $ptotal;
            }

            // Create Voucher
            $data = [   'payment_type' => 'payable', 
                        'reference' => $this->document_reference . ' :: ' . ($i+1) . ' / ' . count($pmethod->deadlines), 
                        'name' => ($i+1) . ' / ' . count($pmethod->deadlines), 
//                          'due_date' => abi_date_short( \Carbon\Carbon::parse( $due_date ), Context::getContext()->language->date_format_lite ), 
                        'due_date' => $due_date, 
                        'payment_date' => null, 
                        'amount' => $installment, 
                        'currency_id' => $this->currency_id,
                        'currency_conversion_rate' => $this->currency_conversion_rate, 
                        'status' => 'pending', 
                        'notes' => null,
                        'document_reference' => $this->document_reference,

                        'payment_document_id' => $pmethod->payment_document_id,
                        'payment_method_id' => $pmethod->id,
                        'auto_direct_debit' => $pmethod->auto_direct_debit,
                        'is_down_payment' => 0,

                        'payment_type_id' => $pmethod->payment_type_id,
                    ];

            // abi_r( $data );die();

            $payment = Payment::create( $data );
            $this->payments()->save($payment);
            $this->supplier->payments()->save($payment);
/*
            $payment->invoice_id = $this->id;
            $payment->model_name = 'SupplierInvoice';
            $payment->owner_id = $document->supplier->id;
            $payment->owner_model_name = 'Supplier';

            $payment->save();
*/
            // ToDo: update Invoice next due date
        }

        // Update Document
        $this->checkPaymentStatus();
        // $this->save();    <= Not needed, since checkPaymentStatus() saves the model

        // Update Supplier Risk
        // $this->supplier->calculateRisk();    <= Not needed, since no new payment is recorded


        return true;
    }

    
    public function destroyPaymentDeadlines()
    {
        // abi_r(( $this->status != 'confirmed' && $this->payment_status != 'pending' ), true);

        if ( ($this->status == 'closed') ) {  // && ($this->payment_status != 'pending') ) {

            // Not allowed

            return false;
            
        }

        // Down Payments
        foreach ($this->payments->where('is_down_payment', '>', 0) as $dpayment) {
            # Unlink Invoice
            $dpayment->paymentable_id = 0;
            $dpayment->paymentable_type = '';
            $dpayment->document_reference = null;
            
            $dpayment->save();

            $dpayment->downpayment->update(['status' => 'pending']);
        }

        // Regular Vouchers
        $this->payments()->where('is_down_payment', 0)->delete();


        // Clean record so far.


        // Update Document
        $this->checkPaymentStatus();


        return true;
    }
  
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function nextPayment()
    {
        //
        return $this->payments()->where('status', 'pending')->orderBy('due_date', 'asc')->first();
    }

    public function getNextDueDateAttribute()
    {
        // 
        return optional($this->nextPayment())->due_date;
    }

}