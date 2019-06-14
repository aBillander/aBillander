<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionOrderToolLine extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'tool_id', 'reference', 'name', 
    						'quantity', 'location'
                          ];

    public static $rules = array(
    	'tool_id'    => 'required',
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

    public function tool()
    {
       return $this->belongsTo('App\Tool');
    }
}
