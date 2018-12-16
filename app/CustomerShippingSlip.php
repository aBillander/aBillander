<?php

namespace App;

use Auth;

use \App\CustomerShippingSlipLine;

class CustomerShippingSlip extends Billable
{

    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     */
    protected $document_fillable = [
    ];


    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'customer_id' => 'exists:customers,id',
                            'invoicing_address_id' => '',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
               ];


    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    // For testing & reminder only
    public function getVars()
    {
        abi_r($this->getClass());
        abi_r($this->getClassName());
        abi_r($this->getClassSnakeCase());
        abi_r($this->getClassLastSegment());
        abi_r($this->getParentClass());
        abi_r($this->getParentClassName());
        abi_r($this->getParentClassSnakeCase());
        abi_r($this->getParentClassLowerCase());
        abi_r($this->getParentModelSnakeCase());
        abi_r($this->getParentModelLowerCase(), true);

            return ;
    }

    public function getNumberAttribute()
    {
        // WTF???  (ノಠ益ಠ)ノ彡┻━┻ 
        return    $this->document_id > 0
                ? $this->document_reference
                : l('draft', [], 'appmultilang') ;
    }

    public function getAllNotesAttribute()
    {
        $notes = '';

        if ($this->notes_from_customer && (strlen($this->notes_from_customer) > 4)) $notes .= $this->notes_from_customer."\n\n";        // Prevent accidental whitespaces
        if ($this->notes               ) $notes .= $this->notes."\n\n";
        if ($this->notes_to_customer   ) $notes .= $this->notes_to_customer."\n\n";


        return $notes;
    }
    
    
    public function customerCard()
    {
        $address = $this->customer->address;

        $card = $customer->name .'<br />'.
                $address->address_1 .'<br />'.
                $address->city . ' - ' . $address->state->name.' <a href="javascript:void(0)" class="btn btn-grey btn-xs disabled">'. $$address->phone .'</a>';

        return $card;
    }
    
    public function customerCardFull()
    {
        $address = $this->customer->address;

        $card = ($address->name_commercial ? $address->name_commercial .'<br />' : '').
                ($address->firstname  ? $address->firstname . ' '.$address->lastname .'<br />' : '').
                $address->address1 . ($address->address2 ? ' - ' : '') . $address->address2 .'<br />'.
                $address->city . ' - ' . $address->state->name.' <a href="javascript:void(0)" class="btn btn-grey btn-xs disabled">'. $address->phone .'</a>';

        return $card;
    }
    
    public function customerCardMini()
    {
        $customer = unserialize( $this->customer );

        $card = $customer["city"].' - '.($customer["state_name"] ?? '').' <a href="#" class="btn btn-grey btn-xs disabled">'. $customer["phone"] .'</a>';

        return $card;
    }
    
    public function customerInfo()
    {
        $customer = $this->customer;

        $name = $customer->name_fiscal ?: $customer->name_commercial;

        if ( !$name ) 
            $name = $customer->name;

        return $name;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    
    public function makeTotals( $document_discount_percent = null, $document_rounding_method = null )
    {
        if ( ($document_discount_percent !== null) && ($document_discount_percent >= 0.0) )
            $this->document_discount_percent = $document_discount_percent;
        
        if ( $document_rounding_method === null )
            $document_rounding_method = Configuration::get('DOCUMENT_ROUNDING_METHOD');

        $currency = $this->document_currency;

        // Just to make sure...
        // https://blog.jgrossi.com/2018/querying-and-eager-loading-complex-relations-in-laravel/
        $this->load(['lines', 'taxes']);

        $lines      = $this->lines;
        $line_taxes = $this->taxes;

        // Calculate these: 
        //      $this->total_lines_tax_excl 
        //      $this->total_lines_tax_incl

        // Calculate base
        $this->total_lines_tax_excl = 0.0;

        switch ( $document_rounding_method ) {
            case 'line':
                # Round off lines and summarize
                $this->total_lines_tax_excl = $lines->sum( function ($line) use ($currency) {
                            return $line->as_price('total_tax_excl', $currency);
                        } );
                break;
            
            case 'total':
                # Summarize (by tax), round off and summarize

                $tax_classes = \App\Tax::with('taxrules')->get();

                foreach ($tax_classes as $tx) 
                {
                    $lns = $lines->where('tax_id', $tx->id);

                    if ($lns->count()) 
                    {
                        $amount = $lns->sum('total_tax_excl');

                        $this->total_lines_tax_excl += $this->as_priceable( $amount, $currency );
                    } 
                }

                break;
            
            case 'custom':
                # code...
                // break;
            
            case 'none':
            
            default:
                # Just summarize
                $this->total_lines_tax_excl = $lines->sum('total_tax_excl');
                break;
        }

        // Calculate taxes
        $this->total_lines_tax_incl = 0.0;

        switch ( $document_rounding_method ) {
            case 'line':
                # Round off lines and summarize

                // abi_r($line_taxes);die();

                $this->total_lines_tax_incl = $line_taxes->sum( function ($line) use ($currency) {
                            return $line->as_price('total_line_tax', $currency);
                        } );
                // abi_r($this->total_lines_tax_incl, true);

                break;
            
            case 'total':
                # Summarize (by tax), round off and summarize
                $tax_classes = \App\Tax::with('taxrules')->get();

                foreach ($tax_classes as $tx) 
                {
                    $lns = $line_taxes->where('tax_id', $tx->id);

                    if ($lns->count()) 
                    {
                        foreach ($tx->taxrules as $rule) {

                            $line_rules = $lns->where('tax_rule_id', $rule->id);

                            if ($line_rules->count()) 
                            {
                                $amount = $line_rules->sum('total_line_tax');

                                $this->total_lines_tax_incl += $this->as_priceable($amount, $currency);
                            }
                        }
                    } 
                }

                break;
            
            case 'custom':
                # code...
                // break;
            
            case 'none':
            
            default:
                # Just summarize
                $this->total_lines_tax_incl = $lines->sum('total_tax_incl') - $this->total_lines_tax_excl;
                break;
        }

        $this->total_lines_tax_incl += $this->total_lines_tax_excl;

        // abi_r($this->total_lines_tax_incl, true);

        // These are NOT rounded!
//        $this->total_lines_tax_excl = $lines->sum('total_tax_excl');
//        $this->total_lines_tax_incl = $lines->sum('total_tax_incl');


// $document_rounding_method = 'line' :: Document lines are rounded, then added to totals






// $document_rounding_method = 'total' :: Document lines are NOT rounded. Totals are rounded
// abi_r($this->total_lines_tax_excl.' - '.$this->total_lines_tax_incl);die();



        if ($this->document_discount_percent>0) 
        {
            $total_tax_incl = $this->total_lines_tax_incl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_incl;
            $total_tax_excl = $this->total_lines_tax_excl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_excl;

            // Make a Price object for rounding
            $p = \App\Price::create([$total_tax_excl, $total_tax_incl], $currency);

            // Improve this: Sum subtotals by tax type must match Order Totals
            // $p->applyRoundingWithoutTax( );

            $this->total_currency_tax_incl = $p->getPriceWithTax();
            $this->total_currency_tax_excl = $p->getPrice();

        } else {

            $this->total_currency_tax_incl = $this->total_lines_tax_incl;
            $this->total_currency_tax_excl = $this->total_lines_tax_excl;
            
        }


        // Not so fast, Sony Boy
        if ( $this->currency_conversion_rate != 1.0 ) 
        {

            // Make a Price object 
            $p = \App\Price::create([$this->total_currency_tax_excl, $this->total_currency_tax_incl], $this->currency, $this->currency_conversion_rate);

            // abi_r($p);

            $p = $p->convertToBaseCurrency();

            // abi_r($p, true);

            // Improve this: Sum subtotals by tax type must match Order Totals
            // $p->applyRoundingWithoutTax( );

            $this->total_tax_incl = $p->getPriceWithTax();
            $this->total_tax_excl = $p->getPrice();

        } else {

            $this->total_tax_incl = $this->total_currency_tax_incl;
            $this->total_tax_excl = $this->total_currency_tax_excl;

        }


        // So far, so good
        $this->save();

        return true;
    }

}
