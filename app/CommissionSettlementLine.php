<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class CommissionSettlementLine extends Model
{

    use ViewFormatterTrait;
    
    protected $dates = ['document_date'];
    
    protected $fillable = [ 'commissionable_id', 'commissionable_type',
    						'document_reference', 'document_date', 
    						'document_commissionable_amount', 'commission_percent', 'commission', 
    	];

    public static $rules = [
                            'document_reference' => 'required'
    	];


    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function commissionsettlement()
    {
        return $this->belongsTo('App\CommissionSettlement', 'commission_settlement_id');
    }

    // Alias
    public function document()
    {
        return $this->commissionsettlement();
    }


    public function commissionable()
    {
        return $this->morphTo();
    }

}
