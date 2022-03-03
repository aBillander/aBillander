<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['alias', 'name', 'notes', 'active'];

    // Add your validation rules here
    public static $rules = array(
        'alias' => 'required|min:2|max:32',
        'name' => 'required',
    	);


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function selectorList()
    {
            return Warehouse::select('id', \DB::raw("concat('[', alias, '] ', name) as selector_full_name"))->pluck('selector_full_name', 'id')->toArray();
    }


    public function getAliasNameAttribute()
    {
            return '['. $this->alias. '] '. $this->name;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function address()
    {
        // See: https://stackoverflow.com/questions/22012877/laravel-eloquent-polymorphic-one-to-one
        // https://laracasts.com/discuss/channels/general-discussion/one-to-one-polymorphic-inverse-relationship-with-existing-database
        return $this->hasOne('App\Address', 'addressable_id','id')
                   ->where('addressable_type', Warehouse::class);
    }

    // Needed to store related address
    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }

    public function products()
    {
/*

    foreach ($ws->products as $product) {
        # code...
        abi_r('['.$product->pivot->product_id.'] ' .$product->pivot->quantity);
    }

*/
        // Return collection of Products. Each one has a property "pivot" with a record from table 'product_warehouse'
        return $this->belongsToMany('App\Product')->withPivot('quantity')->withTimestamps();
    }

    
    /*
    public function currency()
    {
        return $this->hasOne('Currency');
    }
    */  
    
    public function stockmovements()
    {
        return $this->hasMany('App\StockMovement');
    }
    
    public function stockcounts()
    {
        return $this->hasMany('App\StockCount');
    }


    /*
    |--------------------------------------------------------------------------
    | New stuff implementing 'WarehouseProductLine' relations
    |--------------------------------------------------------------------------
    */
    
    // Better approach than Many to Many (Simpler, easy to understand and more flexible)
    public function productline( $product_id )
    {
        $line = $this->hasMany('App\WarehouseProductLine')->where('product_id', $product_id)->first();

        if ( !$line )
            $line = new \App\WarehouseProductLine([
                              'product_id'   => $product_id, 
                              'quantity'     => 0.0, 
                              'warehouse_id' => $this->id,  
            ]);

        return $line;
    }


    /*
    |--------------------------------------------------------------------------
    | New Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productlines()
    {
        return $this->hasMany('App\WarehouseProductLine');
    }

    public function combinations()
    {
        return $this->belongsToMany('App\Combination')->withPivot('quantity')->withTimestamps();
    }
    
}