<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionSheet extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'document_prefix', 'document_id', 'document_reference', 
    						'due_date', 'name', 'notes', 'is_dirty'
                          ];

    public static $rules = array(
    	'due_date'    => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /* Customer Orders */
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder')->orderBy('date_created', 'desc');
    }
    
    public function nbr_customerorders()
    {
        return $this->customerorders->count();
    }
    
    public function customerorderlines()
    {
        return $this->hasManyThrough('App\CustomerOrderLine', 'App\CustomerOrder', 'production_sheet_id', 'customer_order_id', 'id', 'id');
    }
    
    public function customerorderlinesQuantity()
    {
        $mystuff = $this->customerorderlines;

        $num = $mystuff->groupBy('product_id')->map(function ($row) {
            return $row->sum('quantity');
        });

        return $num;
    }

    public function customerorderlinesGrouped()
    {
        $mystuff = $this->customerorderlines;

        $num = $mystuff->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, [
                        'product_id' => $group->first()->product_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'quantity' => $group->sum('quantity'),
                      ]);
                    }, collect());

        return $num;
    }

    /* Production Orders */
    
    public function productionorders()
    {
        return $this->hasMany('App\ProductionOrder')->with('workcenter')->orderBy('work_center_id', 'asc');
    }
    
    public function nbr_productionorders()
    {
        return $this->productionorders->count();
    }
    
    public function productionorderlines()
    {
        return $this->hasManyThrough('App\ProductionOrderLine', 'App\ProductionOrder', 'production_sheet_id', 'production_order_id', 'id', 'id');
    }
    
    public function productionorderlinesQuantity()
    {
        $mystuff = $this->productionorderlines;

        $num = $mystuff->groupBy('product_id')->map(function ($row) {
            return $row->sum('required_quantity');
        });

        return $num;
    }

    public function productionorderlinesGrouped()
    {
        $mystuff = $this->productionorderlines;

        $num = $mystuff->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, collect([
                        'product_id' => $group->first()->product_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'quantity' => $group->sum('required_quantity'),
                      ]));
                    }, collect());

        return $num;
    }

    /* Products not Scheduled */
    
    public function productsNotScheduled()
    {
        $list = [];

        $required  = $this->customerorderlinesGrouped();
        $scheduled = $this->productionorders;

//        abi_r($required, true);

        if (!$scheduled->count()) return $required;

//        abi_r($scheduled->first(), true);

        foreach ($required as $pid => $line) {

//            abi_r($line, true);

            $pid = $line['product_id'] ;
            $sch = $scheduled->first(function($item) use ($pid) {
                return $item->product_id == $pid;
            });

//                abi_r($sch);

            if ( $sch ) {
                $qty = $line['quantity'] - $sch->planned_quantity;

                if ($qty) {
                    $line['quantity'] = $qty;
                    $list[] = $line;
                }

            } else {
                $list[] = $line;
            }
        }
// die();
        return collect($list);
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsOpen($query)
    {
        return $query->where( 'due_date', '>=', \Carbon\Carbon::now()->toDateString() );
    }
}
