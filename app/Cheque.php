<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class Cheque extends Model
{
    use ViewFormatterTrait;

    public static $statuses = array(
            'pending',		// Pendiente de depositar
            'deposited',	// Depositado
            'paid',			// or cleared: pagaddo (ingresado en el banco)
            'voided',		// Anulado
            'rejected',		// or dishonored, or returned, or bounced
        );

    protected $dates = [
            'date_of_issue', 'due_date', 'payment_date', 'posted_at', 'date_of_entry',
    ];

    protected $fillable = [
    		'document_number', 'place_of_issue', 'amount', 
            'date_of_issue', 'due_date', 'payment_date', 'posted_at', 'date_of_entry',
            'memo', 'notes', 'status', 
            'currency_id', 'customer_id', 'drawee_bank_id',
    ];

    public static $rules = [
        'document_number'         => 'required|min:2|max:32',

    	'amount'   => 'numeric|min:0',

        'currency_id'   => 'required|exists:currencies,id',
        'customer_id'   => 'required|exists:customers,id',
        'drawee_bank_id'   => 'nullable|exists:banks,id',
    	];
    


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

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
    
    public function chequedetails()
    {
        return $this->hasMany( 'App\ChequeDetail' )
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function details()
    {
        return $this->chequedetails();
    }
    
    public function currency()
    {
    	return $this->belongsTo( 'App\Currency' );
    }
    
    public function customer()
    {
    	return $this->belongsTo( 'App\Customer' );
    }
    
    public function drawee_bank()
    {
    	return $this->belongsTo( 'App\Bank', 'drawee_bank_id' );
    }
    
    // Alias
    public function bank()
    {
    	return $this->drawee_bank();
    }



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        if (array_key_exists('date_of_issue_from', $params) && $params['date_of_issue_from'])
        {
            $query->where('date_of_issue', '>=', $params['date_of_issue_from']);
        }

        if (array_key_exists('date_of_issue_to', $params) && $params['date_of_issue_to'])
        {
            $query->where('date_of_issue', '<=', $params['date_of_issue_to']);
        }


        if (array_key_exists('due_date_from', $params) && $params['due_date_from'])
        {
            $query->where('due_date', '>=', $params['due_date_from']);
        }

        if (array_key_exists('due_date_to', $params) && $params['due_date_to'])
        {
            $query->where('due_date', '<=', $params['due_date_to']);
        }



        if (array_key_exists('status', $params) && $params['status'] && self::isStatus($params['status']))
        {
            $query->where('status', $params['status']);
        }

        if (array_key_exists('customer_id', $params) && $params['customer_id'])
        {
            $query->where('customer_id', $params['customer_id']);
        }

        if (array_key_exists('price_amount', $params) && is_numeric($params['price_amount']))
        {
            $query->where('amount', $params['price_amount']);
        }


/*
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            $query->orWhere('ean13', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));

            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));

            if ( \Auth::user()->language->iso_code == 'en' )
            {
                $query->orWhere('name_en', 'LIKE', '%' . trim($params['name'] . '%'));
            }
        }

        if ( isset($params['stock']) )
        {
            if ( $params['stock'] == 0 )
                $query->where('quantity_onhand', '<=', 0);
            if ( $params['stock'] == 1 )
                $query->where('quantity_onhand', '>', 0);
        }

        if ( isset($params['category_id']) && $params['category_id'] > 0 )
        {
            $query->where('category_id', '=', $params['category_id']);
        }

        if ( isset($params['manufacturer_id']) && $params['manufacturer_id'] > 0 && 0)
        {
            $query->where('manufacturer_id', '=', $params['manufacturer_id']);
        }

        if ( isset($params['procurement_type']) && $params['procurement_type'] != '' )
        {
            $query->where('procurement_type', '=', $params['procurement_type']);
        }

        if ( isset($params['active']) )
        {
            if ( $params['active'] == 0 )
                $query->where('active', '=', 0);
            if ( $params['active'] == 1 )
                $query->where('active', '>', 0);
        }
*/

        return $query;
    }

}
