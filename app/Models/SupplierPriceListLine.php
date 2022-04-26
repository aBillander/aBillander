<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class SupplierPriceListLine extends Model {

    use ViewFormatterTrait;
//    use SoftDeletes;

//    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'supplier_id', 'product_id','supplier_reference', 
                            'price', 'currency_id', 'discount_percent', 'discount_amount', 'from_quantity', 
                        ];

    public static $rules = array(
        'supplier_id'  => 'required|exists:suppliers,id',
        'product_id'   => 'required|exists:products,id',
        'currency_id'  => 'required|exists:currencies,id',
        'price'        => 'required|numeric|min:0', 
    	);


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function getPriceLocalCurrencyAttribute() 
    {
        if ( $this->currency_id == Context::getContext()->currency->id )
            return $this->price;

        return Currency::convertAmount($this->price, $this->currency, Context::getContext()->currency);
    }


    public function scopeFilter($query, $params)
    {
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('products.reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));

            /*
            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
            */
        }

        if ( isset($params['supplier_reference']) && trim($params['supplier_reference']) !== '' )
        {
            $reference = trim($params['supplier_reference']);

            $query->where( function ($query1) use ($reference) {
                $query1->where('supplier_price_list_lines.supplier_reference', 'LIKE', '%' . $reference . '%');
 //                      ->OrWhere('supplier_reference', '');
 //                      ->OrWhere('supplier_reference', null);
            } );
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('products.name', 'LIKE', '%' . trim($params['name'] . '%'));
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

        return $query;
    }
    

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}