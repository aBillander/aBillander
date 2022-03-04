<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Traits\ModelAttachmentableTrait;

use App\Traits\ViewFormatterTrait;

class DownPayment extends Model
{
    use ViewFormatterTrait;
    
    use ModelAttachmentableTrait;

    public static $statuses = array(
            'pending',		// Pendiente de depositar
            'applied',		// Aplicado
        );

    protected $dates = [
            'date_of_issue', 'due_date', 'payment_date', 'posted_at',
    ];

    protected $fillable = [
    		'reference', 'name', 'amount', 
            'date_of_issue', 'due_date', 'payment_date', 'posted_at',
            'payment_type_id', 'bank_id', 'notes', 'status', 
            'currency_id', 'currency_conversion_rate', 'customer_id', 'supplier_id',
            'customer_order_id', 'supplier_order_id',
    ];

    public static $rules = [
//        'reference'         => 'required|min:2|max:32',
        'name'         => 'required',
        'due_date'         => 'required',

    	'amount'   => 'numeric|min:0',

        'currency_id'   => 'required|exists:currencies,id',
        'payment_type_id'   => 'required|exists:payment_types,id',
        // Controller should add proper Customer or Supplier validation rule
//        'customer_id'   => 'required|exists:customers,id',
        'bank_id'   => 'nullable|exists:banks,id',
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
    
    public function getDeletableAttribute()
    {
        // Safety check: Can not delete if ANY payment is related to an Invoice        

        foreach ($this->downpaymentdetails as $detail) {
            # code...
            if ( $detail->supplierpayment && $detail->supplierpayment->supplierinvoice )
                return false;
        }

        return true;

        // return !( $this->status == 'applied' );
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function downpaymentdetails()
    {
        return $this->hasMany( DownPaymentDetail::class )
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function details()
    {
        return $this->downpaymentdetails();
    }

    public function vouchers()
    {
        return $this->belongsToMany(Payment::class, 'down_payment_details');
    }
    
    public function currency()
    {
    	return $this->belongsTo( Currency::class );
    }
    
    public function customer()
    {
        return $this->belongsTo( Customer::class );
    }
    
    public function supplier()
    {
        return $this->belongsTo( Supplier::class );
    }
    
    public function customerorder()
    {
        return $this->belongsTo( CustomerOrder::class, 'customer_order_id' );
    }
    
    public function supplierorder()
    {
        return $this->belongsTo( SupplierOrder::class, 'supplier_order_id' );
    }
    
    public function getSupplierinvoiceAttribute()
    {
            // Supplier order
            $so = $this->supplierorder;
            if ( ! $so ) return null;

            // Supplier Shipping Slip
            $sss = $so->shippingslip;
            if ( ! $sss ) return null;

            // Supplier invoice
            $si = $sss->invoice;
            if ( ! $si ) return null;

            return $si;

    }
    
    public function drawee_bank()
    {
    	return $this->belongsTo( Bank::class, 'drawee_bank_id' );
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

        if (array_key_exists('supplier_id', $params) && $params['supplier_id'])
        {
            $query->where('supplier_id', $params['supplier_id']);
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
