<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;
use App\Traits\AutoSkuTrait;

use DB;

class Combination extends Model {

    use ViewFormatterTrait;
    use AutoSkuTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $appends = ['name', 'quantity_available'];
    
    protected $fillable = [ 'reference', 'ean13', 'warranty_period', 
                            'reorder_point', 'maximum_stock', 'price', 'cost_price', 'supply_lead_time', 
                            'location', 'width', 'height', 'depth', 'weight', 
                            'notes', 'publish_to_web', 'blocked', 'active', 'is_default',
                            'product_id',
                          ];

    public static $rules = array();
    

    public static function boot()
    {
        parent::boot();

        static::created(function($combination)
        {
            if ( Configuration::get('SKU_AUTOGENERATE') )
                if ( !$combination->reference )
                    $combination->autoSKU();
        });
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getNameAttribute()
    {
        return $this->name();
    }

    public function getQuantityAvailableAttribute()
    {
        $value =      $this->quantity_onhand  
                    + $this->quantity_onorder 
                    - $this->quantity_allocated 
                    + $this->quantity_onorder_mfg 
                    - $this->quantity_allocated_mfg;

        return $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    

    public function name($separator = ' - ')
    {        
        // Gather Attributtes & Groups
        $combination = Combination::with('options')
                        ->with('options.optiongroup')
                        ->findOrFail($this->id);
        $name = [];

        foreach ($combination->relations['options'] as $att) {
            $name[] = $att->optiongroup->name . ': ' . $att->name; 
        }
        return implode($separator, $name);
    }

    /**
     * Returns a Combination id after options array.
     *
     * @param  Array $options('option_group' => 'option')
     * @return int Combination id
     */
    public static function getCombinationByOptions( $product_id, array $groups = [] )
    {
        if (count($groups) == 0) return 0;

        $q = '';

        foreach ($groups as $option) {
            $q .= ' (co.option_id = '.$option.') or';
        }
        $q = substr($q, 0, -2);

        $q = 'SELECT combination_id, COUNT(combination_id) tot FROM `combinations` as c
                left join combination_option as co
                on co.combination_id = c.id
                WHERE c.product_id = '.$product_id.'
                AND ( '.$q.' )
                GROUP BY combination_id ORDER BY tot DESC
                LIMIT 1';

        $result = DB::select(DB::raw($q));

        if ($result)
        {    
            $cid = $result[0]->combination_id;
            if( $result[0]->tot == count($groups) ) return $cid;
        }

        return 0;
    }
    

    public function getStockByWarehouse( $warehouse )
    { 
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;

    //    $combination = Combination::find($this->combination_id);

        $whs = $this->warehouses;
        if ($whs->contains($wh_id)) {
            $wh = $this->warehouses()->get();
            $wh = $wh->find($wh_id);
            $quantity = $wh->pivot->quantity;
        } else {
            $quantity = 0;
        }

        return $quantity;
    }
    
    public function getStock()
    { 
        $warehouses = Warehouse::get();
        $count = 0;

        foreach ($warehouses as $warehouse) {
            # code...
            $count += $this->getStockByWarehouse( $warehouse->id );
        }

        return $count;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
	}
    
    public function options()
    {
        return $this->belongsToMany(Option::class)->withTimestamps();
    }
    
    public function stockmovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->withPivot('quantity')->withTimestamps();
    }

    public function images()
    {
        return $this->belongsToMany(Image::class)
                ->where('imageable_id', '=', $this->product_id)
                ->andWhere('imageable_type', '=', Product::class);
    }
}
