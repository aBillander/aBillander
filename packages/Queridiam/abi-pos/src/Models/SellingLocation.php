<?php

namespace Queridiam\POS\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Store, outlet, etc., where a POS is located, with address and maybe local settings
class SellingLocation extends Model
{
    use SoftDeletes;    

    protected $dates = ['deleted_at'];
    // 

    protected $appends = ['full_name'];
    
    protected $fillable = [ 'company_id', 'alias', 'name', 
                            'cash_denominations', 'allowed_payment_methods', 
                            'active', 
                            'address_id', 'currency_id', 
                            'counter_sale_sequence_id', 'counter_sale_template_id', 
                            'invoice_sequence_id', 'invoice_template_id', 
                            'price_list_id', 'warehouse_id', 
                          ];

    public static $rules = [
//                            'alias'   => 'required|min:2|max:32',
//                            'name'    => 'required|min:2|max:128',
                           ];

    
        
    /*
    |--------------------------------------------------------------------------
    | Methods & Accessors
    |--------------------------------------------------------------------------
    */

    public function getAliasNameAttribute()
    {
        $value = '[' . $this->alias . '] ' . $this->name;

        return $value;
    }

    public static function selectorList()
    {
            return SellingLocation::select('id', \DB::raw("concat('[', alias, '] ', name) as selector_full_name"))->pluck('selector_full_name', 'id')->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id')
                   ->where('addressable_type', SellingLocation::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function pricelist()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }


    public function cashregisters()
    {
        return $this->hasMany(CashRegister::class);
    }


    public function countersalesequence() 
    {
        return $this->belongsTo(Sequence::class, 'counter_sale_sequence_id');
    }

    public function countersaletemplate() 
    {
        return $this->belongsTo(Template::class, 'counter_sale_template_id');
    }


    public function invoicesequence() 
    {
        return $this->belongsTo(Sequence::class, 'invoice_sequence_id');
    }

    public function invoicetemplate() 
    {
        return $this->belongsTo(Template::class, 'invoice_template_id');
    }
}
