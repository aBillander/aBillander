<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionOrderLine extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'type', 'product_id', 'reference', 'name', 
    						'bom_line_quantity', 'bom_quantity', 'required_quantity', 'warehouse_id'
                          ];

    public static $rules = array(
    	'product_id'    => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productionorder()
    {
        return $this->belongsTo('App\ProductionOrder');
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }
}
