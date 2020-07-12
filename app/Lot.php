<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Product;
use App\Combination;
use App\MeasureUnit;

use App\Traits\ViewFormatterTrait;

class Lot extends Model
{
    use ViewFormatterTrait;

    protected $dates = [
            'manufactured_at',
            'expiry_at',
    ];

    protected $fillable = ['reference', 'product_id', 'combination_id',
    					   'quantity_initial', 'quantity', 
    					   'measure_unit_id', 'package_measure_unit_id', 'pmu_conversion_rate',
    					   'manufactured_at', 'expiry_at',
    					   'notes',
    					];

    public static $rules = [
        'reference'         => 'required|min:2|max:32',

        'product_id'   => 'required|exists:products,id',
//        'combination_id'   => 'nullable|sometimes|exists:combinations,id',  // <= Ensure Combination belongs to Product!!

    	'quantity_initial'   => 'numeric|min:0',
    	'quantity'   => 'numeric|min:0',

        'manufactured_at' => 'nullable|date',
        'expiry_at'  => 'nullable|date',
    	];

    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public  function product()
    {
       return $this->belongsTo('App\Product');
    }

    public function combination()
    {
       return $this->belongsTo('App\Combination');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    public function package_measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'package_measure_unit_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {

        if ($params['date_from'])
            // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('date', '>=', $params['date_from'].' 00:00:00');
        }

        if ($params['date_to'])
        {
            $query->where('date', '<=', $params['date_to']  .' 23:59:59');
        }


        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
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


        if ( isset($params['document_reference']) && trim($params['document_reference']) !== '' )
        {
            $query->where('document_reference', 'LIKE', '%' . trim($params['document_reference']) . '%');
        }
        

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['warehouse_id']) && $params['warehouse_id'] > 0 )
        {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }

        if ( isset($params['movement_type_id']) && $params['movement_type_id'] > 0 )
        {
            $query->where('movement_type_id', '=', $params['movement_type_id']);
        }

        return $query;
    }
}
