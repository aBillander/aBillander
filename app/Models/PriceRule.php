<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ViewFormatterTrait;


class PriceRule extends Model
{
    use ViewFormatterTrait;

    public static $types = [
                'price', 
                'discount', 
                'promo',       // Extra units free of charge
                'pack',        // Price for different measure unit than default / stock measure unit
            ];

    protected $discount_types = [
        'percentage',
        'amount',               // Not used, so far...
    ];

    protected $dates = [
        'date_from',
        'date_to',
    ];

    protected $fillable = ['name', 'category_id', 'product_id', 'combination_id',
                           'customer_id', 'customer_group_id',
                           'currency_id', 'rule_type', 'discount_type',
                           'price', 'discount_percent', 'discount_amount', 'discount_amount_is_tax_incl',
                           'from_quantity', 'extra_quantity',
                           'date_from', 'date_to',
                           'measure_unit_id', 'conversion_rate',
    ];

    public static $rules = [
        'category_id'       => 'nullable|exists:categories,id',
        'product_id'        => 'exists:products,id',
        'combination_id'    => 'nullable|exists:combinations,id',
        'customer_id'       => 'nullable|exists:customers,id',
        'customer_group_id' => 'nullable|exists:customer_groups,id',
        'currency_id'       => 'nullable|exists:currencies,id',
//        'measure_unit_id'   => 'nullable|exists:measureunits,id',
        'date_from'         => 'nullable|date',
        'date_to'           => 'nullable|date',
        'from_quantity'     => 'numeric|min:0',
        'extra_quantity'    => 'numeric|min:0',

        'price'    => 'numeric|min:0',
    ];


    public static function getRuleTypeList()
    {
            $list = [];
            foreach (static::$types as $type) {
                // $list[$type] = l(PriceRule::class.'.'.$type, [], 'appmultilang');
                $list[$type] = $type;
            }

            return $list;
    }

    public static function getRuleTypeName( $type )
    {
            return $type;
            //return l(PriceRule::class.'.'.$type, [], 'appmultilang');
    }


    /**
     * Handy method
     *
     * @param Product $product
     */
    public function getUnitPrice( Currency $currency = null )
    {
/*
        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;

*/
        // Welcome to the show:
        if($this->rule_type=='price')
        {
            $price = $this->price;
        }
        elseif($this->rule_type=='discount')
        {
            $price = optional($this->product)->price * (1.0 - $this->discount_percent/100.0);
        }
        elseif($this->rule_type=='pack')
        {
            $price = $this->price / $this->conversion_rate;
        }
        elseif($this->rule_type=='promo')
        {
            $price = $this->price;
        }

        // Add Ecotax
        if ( Configuration::isTrue('ENABLE_ECOTAXES') )
        {
            $price += optional($this->product)->getEcotax(); 
        }

        return $price;
    }


    /**
     * Return true or false if a rule applies to a product right now
     *
     * @param $qty
     * @return bool
     */
    public function applies($qty)
    {
        $now = Carbon::now();
        if ($this->from_quantity <= $qty &&
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
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function combination()
    {
        return $this->belongsTo(Combination::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customergroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeApplyToCustomer($query, $customer_id)
    {
        // Be careful:
        // If $customer_id = $customer->id, then: $customer_group_id = $customer->customer_group_id
        return $query->when($customer_id, function($query) use ($customer_id) {

                        $customer_group_id = Customer::find($customer_id)->customer_group_id;


                        $query->where(function ($query) use ($customer_id) {

                                $query->whereHas('customer', function ($query) use ($customer_id) {

                                        $query->where('id', $customer_id);
                                });
                        });

                        $query->orWhere(function ($query) use ($customer_group_id) {

                                $query->whereDoesntHave('customer');

                                $query->whereHas('customergroup', function ($query) use ($customer_group_id) {

                                        $query->where('id', $customer_group_id);
                                });
                        });

                        $query->orWhere(function ($query) {

                                $query->whereDoesntHave('customer');

                                $query->whereDoesntHave('customergroup');
                        });
                });
    }

    public function scopeFilter($query, $params)
    {

        if ($params['date_from']) // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('date', '>=', $params['date_from'] . ' 00:00:00');
        }

        if ($params['date_to']) {
            $query->where('date', '<=', $params['date_to'] . ' 23:59:59');
        }


        if (isset($params['reference']) && trim($params['reference']) !== '')
        {
            $stub = $params['reference'];

            $query->whereHas('product', function($q) use ($stub) 
            {
                $q->where('reference', 'LIKE', '%' . $stub . '%');

            });
        }

        if (isset($params['name']) && trim($params['name']) !== '')
        {
            $stub = $params['name'];

            $query->whereHas('product', function($q) use ($stub) 
            {
                $q->where('name', 'LIKE', '%' . $stub . '%');

            });
        }

        if (isset($params['rule_type']) && in_array($params['rule_type'], static::$types)) {
            $query->where('rule_type', '=', $params['rule_type']);
        }

        return $query;
    }

}