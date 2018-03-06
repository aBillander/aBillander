<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'reference', 'created_via', 
    						'status', 
    						'product_id', 'combination_id', 'product_reference', 'product_name', 
    						'planned_quantity', 'product_bom_id', 'due_date', 'notes', 
    						'work_center_id', 'warehouse_id', 'production_sheet_id'
                          ];

    public static $rules = array(
//    	'id'    => 'required|unique',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methodss
    |--------------------------------------------------------------------------
    */
    
    public static function createWithLines()
    {
        //

//        return $order;
    }
    
    // Update Order ines when Order Product quantity changes
    public function updateLines()
    {
        //
    }


    public function scopeFilter($query, $params)
    {
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('product_reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('product_name', 'LIKE', '%' . trim($params['name'] . '%'));
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
    
    public function productionsheet()
    {
        return $this->belongsTo('App\ProductionSheet', 'production_sheet_id');
    }
    
    public function workcenter()
    {
        return $this->belongsTo('App\WorkCenter', 'work_center_id');
    }
    
    public function productionorderlines()
    {
        return $this->hasMany('App\ProductionOrderLine', 'production_order_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsManual($query)
    {
        return $query->where( 'created_via', 'manual' );
    }

    public function scopeIsFromWeb($query)
    {
        return $query->where( 'created_via', 'webshop' );
    }
}
