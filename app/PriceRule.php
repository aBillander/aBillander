<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ViewFormatterTrait;

class PriceRule extends Model
{
    use ViewFormatterTrait;

    protected $dates = [
        'date_from',
        'date_to',
    ];

    protected $fillable = ['category_id', 'product_id', 'combination_id',
                           'customer_id', 'customer_group_id',
                           'currency_id', 'rule_type', 'discount_type',
                           'price', 'discount_percent', 'discount_amount', 'discount_amount_is_tax_incl',
                           'from_quantity',
                           'date_from', 'date_to',
    ];

    public static $rules = [
        'category_id'       => 'nullable|exists:categories,id',
        'product_id'        => 'nullable|exists:products,id',
        'combination_id'    => 'nullable|exists:combinations,id',
        'customer_id'       => 'nullable|exists:customers,id',
        'customer_group_id' => 'nullable|exists:customer_groups,id',
        'currency_id'       => 'nullable|exists:currencies,id',
        'date_from'         => 'nullable|date',
        'date_to'           => 'nullable|date',
    ];

    /**
     * Handy method
     *
     * @param Product $product
     */
    public function removeLine(Product $product)
    {
        $line = $this->pricelistlines()->where('product_id', '=', $product->id)->first();
        $line->delete();
    }

    /**
     * Return true or false if a rule applies to a product in cart right now
     *
     * @param $qty_in_cart
     * @return bool
     */
    public function applies($qty_in_cart)
    {
        $now = Carbon::now();
        if ($this->from_quantity <= $qty_in_cart &&
            (is_null($this->date_from) || $this->date_from <= $now) &&
            (is_null($this->date_to) || $this->date_to >= $now)) {

            return true;
        }
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function combination()
    {
        return $this->belongsTo('App\Combination');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function customergroup()
    {
        return $this->belongsTo('App\CustomerGroup', 'customer_group_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeFilter($query, $params)
    {

        return $query;

        if ($params['date_from']) // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('date', '>=', $params['date_from'] . ' 00:00:00');
        }

        if ($params['date_to']) {
            $query->where('date', '<=', $params['date_to'] . ' 23:59:59');
        }


        if (isset($params['reference']) && trim($params['reference']) !== '') {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
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

        if (isset($params['name']) && trim($params['name']) !== '') {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if (isset($params['warehouse_id']) && $params['warehouse_id'] > 0) {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }

        return $query;
    }

}