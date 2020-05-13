<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

use App\Traits\SearchableTrait;

class ProductBOM extends Model
{

    use ViewFormatterTrait;

    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'product_b_o_ms.name' => 10,
//            'product_b_o_ms.notes' => 4,
            'product_b_o_ms.alias' => 7,
 //           'products.description' => 10,
 //           'posts.title' => 2,
 //           'posts.body' => 1,
        ],
 //       'joins' => [
 //           'posts' => ['users.id','posts.user_id'],
 //       ],
    ];

    public static $statuses = array(
            'certified',
            'new',
            'development',      // Only BOMs with this status are available for use in Production Orders
            'closed',
        );

    protected $fillable =  ['alias', 'name', 'quantity', 'measure_unit_id',
                            'status', 'notes',
                            ];

	// Add your validation rules here
	public static $rules = [
                            'alias'    => 'required|min:2|max:32|unique:product_b_o_ms,alias',   // ,{$id},id', //,deleted_at,NULL',
                            'name'     => 'required|min:2|max:128',
                            'measure_unit_id' => 'exists:measure_units,id',
	];

    public static function getStatusList()
    {
            $list = [];
            foreach (self::$statuses as $status) {
                $list[$status] = l($status, [], 'appmultilang');
            }

            return $list;
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        if ( isset($params['term']) && trim($params['term']) !== '' )
        {
            $query->search( trim($params['term']) );
        }

        return $query;



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

        return $query;
    }
    


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }
    
    public function BOMlines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\ProductBOMLine', 'product_bom_id')->orderBy('line_sort_order', 'ASC');
    }
    
    public function BOMmanufacturablelines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->BOMlines()
                    ->whereHas('product', function($query) {
                       $query->  where('procurement_type', 'manufacture');
                       $query->orWhere('procurement_type', 'assembly');
                    });
    }
    
    public function bomitems()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\BOMItem', 'product_bom_id');
    }
    
    public function products()
    {
        // Products that own this BOM
        return $this->hasManyThrough('App\Product', 'App\BOMItem', 'product_bom_id', 'id', 'id', 'product_id')->with('measureunit');
    }
    
    public function child_products()
    {
        // Products owned by this BOM
        return $this->hasManyThrough('App\Product', 'App\ProductBOMLine', 'product_bom_id', 'id', 'id', 'product_id')->with('measureunit');
    }

    public function hasProduct_V0( $product_id )
    {
        $products = $this->child_products;

        // abi_r($products, true);

        foreach ($this->BOMlines as $line) {
            # code...

            // abi_r($line->product->id.' - '.$line->product->name);

            if ( $line->product->id == $product_id ) return true;

            if ( $line->product->bom )
            {
                // abi_r('BOM start');
                if ( $line->product->bom->hasProduct( $product_id ) ) return true;
                // abi_r('BOM end');
            }
        }

        return false;
    }

    public function hasProduct( $product_id )
    {
        $products = $this->child_products;

        // abi_r($products, true);

        foreach ($products as $product) {
            # code...

            // abi_r($product->id.' - '.$product->name);

            if ( $product->id == $product_id ) return true;

            if ( $product->bom )
            {
                // abi_r('BOM start');
                if ( $product->bom->hasProduct( $product_id ) ) return true;
                // abi_r('BOM end');
            }
        }

        return false;
    }
}
