<?php 

namespace App\Traits;

use App\Configuration;

trait CustomerInvoicePaymentsTrait
{
    
    public function makePaymentDeadlines()
    {
        // abi_r(( $this->status != 'confirmed' && $this->payment_status != 'pending' ), true);

        if ( ($this->status != 'closed') && ($this->payment_status != 'pending') ) {

            // Not allowed

            return false;
            
        }

        $this->payments()->delete();

        $ototal = $this->as_priceable( $this->total_tax_incl - $this->down_payment );
        $ptotal = 0;
        $pmethod = $this->paymentmethod;
        $dlines = $pmethod->deadlines;
        $pdays = $this->customer->paymentDays();
        // $base_date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $this->document_date );
        $base_date = $this->document_date;

        for($i = 0; $i < count($pmethod->deadlines); $i++)
        {
            $next_date = $base_date->copy()->addDays($dlines[$i]['slot']);

            // Calculate installment due date
            $due_date = $this->customer->paymentDate( $next_date );

            if ( $i != (count($pmethod->deadlines)-1) ) {
                $installment = $this->as_priceable( $ototal * $dlines[$i]['percentage'] / 100.0, $this->currency, true );
                $ptotal += $installment;
            } else {
                // Last Installment
                $installment = $ototal - $ptotal;
            }

            // Create Voucher
            $data = [   'payment_type' => 'receivable', 
                        'reference' => $this->document_reference . ' :: ' . ($i+1) . ' / ' . count($pmethod->deadlines), 
                        'name' => ($i+1) . ' / ' . count($pmethod->deadlines), 
//                          'due_date' => \App\FP::date_short( \Carbon\Carbon::parse( $due_date ), \App\Context::getContext()->language->date_format_lite ), 
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
                    ];

            // abi_r( $data );die();

            $payment = \App\Payment::create( $data );
            $this->payments()->save($payment);
            $this->customer->payments()->save($payment);
/*
            $payment->invoice_id = $this->id;
            $payment->model_name = 'CustomerInvoice';
            $payment->owner_id = $document->customer->id;
            $payment->owner_model_name = 'Customer';

            $payment->save();
*/
            // ToDo: update Invoice next due date
        }

        $this->payment_status = 'pending';
        $this->open_balance = $ototal;
        $this->save();


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