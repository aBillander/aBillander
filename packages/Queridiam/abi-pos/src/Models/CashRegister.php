<?php

namespace Queridiam\POS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegister extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static $statuses = array(
            'open',
            'closed',
        );

    protected $dates = ['deleted_at'];
    //

    protected $appends = ['full_name'];
    
    protected $fillable = [ 'alias', 'name', 'reference', 'barcode', 
                            'description', 'location',      //  location    varchar(64)
                            'active', 'status', 'cashier_user_id', 
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
        return $this->hasOne(CashierUser::class);
    }
}
