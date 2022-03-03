<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Traits\ModelAttachmentableTrait;

use App\Traits\ViewFormatterTrait;

class Cheque extends Model
{
    use ViewFormatterTrait;
    
    use ModelAttachmentableTrait;

    public static $statuses = array(
            'pending',		// Pendiente de depositar
            'deposited',	// Depositado
            'paid',			// or cleared: pagaddo (ingresado en el banco)
            'voided',		// Anulado
            'bounced',		// or dishonored, or returned, or rejected
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
        'place_of_issue'         => 'required',

    	'amount'   => 'numeric|min:0',

        'currency_id'   => 'required|exists:currencies,id',
        'customer_id'   => 'required|exists:customers,id',
        'drawee_bank_id'   => 'nullable|exists:banks,id',
    	];
    



    public static function boot()
    {
        parent::boot();

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($document)
        {
            // before delete() method call this
            foreach($document->details as $detail) {
                $detail->delete();
            }

        });

    }



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


    public function checkStatus()
    {
        $this->load('vouchers');

        $open_balance = $this->amount - $this->vouchers->sum('amount'); // abi_r($open_balance);

        $count = $this->vouchers()->count(); // abi_r($count);

        // abi_r((int) ( $this->vouchers()->where('status', 'paid')->count() <= $count && ($open_balance != 0.0) ));

        // abi_r((int) (( $count > 0 ) && ( $this->vouchers()->where('status', 'paid')->count() == $count ) && ($open_balance == 0.0)));

        if ( ( $count > 0 ) && ( $this->vouchers()->where('status', 'paid')->count() == $count ) && ($open_balance == 0.0) )
        {
            if ( $this->payment_date && ($this->status == 'paid') ) return true;

            $this->status = 'paid';
            $this->payment_date = \Carbon\Carbon::now();

            $this->save();

            // abi_r($this); // die();

            return true;
        }

        if ( $this->vouchers()->where('status', 'paid')->count() <= $count && ($open_balance != 0.0) )    // This include case $count = 0
        {
            $this->status = 'deposited';
            $this->payment_date = null;

            $this->save();

            // abi_r($this); // die();

            return true;
        }

        return true;
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

    public function vouchers()
    {
        return $this->belongsToMany('App\Payment', 'cheque_details');
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


    public function scopeOfSalesRep($query)
    {
//        return $query->where('customer_id', Auth::user()->customer_id);

        if ( isset(Auth::user()->sales_rep_id) && ( Auth::user()->sales_rep_id != NULL ) )
        {
                $sales_rep_id = Auth::user()->sales_rep_id;

                return $query->whereHas('customer', function ($query) use ($sales_rep_id) {
                                    $query->where('sales_rep_id', $sales_rep_id);
                                } );
        }

        // Not allow to see resource
        return $query->where('sales_rep_id', 0);
    }

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
