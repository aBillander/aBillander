<?php

namespace Queridiam\POS\Models;

use App\Models\Currency;
use App\Models\PriceList;
use App\Models\Sequence;
use App\Models\Template;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegister extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static $statuses = array(
            'regular', 
            'decommissioned',
        );

    protected $dates = ['deleted_at'];
    //

    protected $appends = ['full_name'];
    
    protected $fillable = [ 'alias', 'name', 'reference', 'barcode', 'description', 
                            'cash_denominations', 'allowed_payment_methods', 
                            'active', 'status', 'location', 
                            'active', 'status', 
                            'currency_id', // 'cash_denomination_set_id', 
                            'counter_sale_sequence_id', 'counter_sale_template_id', 'invoice_sequence_id', 'invoice_template_id', 
                            'price_list_id', 'warehouse_id', 'selling_location_id', 
                            'cash_register_session_id', 
                          ];

    public static $rules = [
                            'alias'   => 'required|min:2|max:32',
                            'name'    => 'required|min:2|max:128',
                           ];

    
        
    /*
    |--------------------------------------------------------------------------
    | Methods & Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        $value = '[' . $this->reference . '] ' . $this->name;

        return $value;
    }

    public static function selectorList()
    {
            return CashRegister::select('id', \DB::raw("concat('[', alias, '] ', name) as selector_full_name"))->pluck('selector_full_name', 'id')->toArray();
    }

    public function getAliasNameAttribute()
    {
            return '['. $this->alias. '] '. $this->name;
    }

    

    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, [], 'appmultilang');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l(get_called_class().'.'.$status, [], 'appmultilang');
    }

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->status, 'appmultilang');
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cashieruser()
    {
        return $this->hasOne(CashierUser::class, 'cash_register_id');
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

    public function sellinglocation()
    {
        return $this->belongsTo(SellingLocation::class, 'selling_location_id');
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


    public function cashregistersessions() 
    {
        return $this->hasMany(CashRegisterSession::class, 'cash_register_id');
    }
}
