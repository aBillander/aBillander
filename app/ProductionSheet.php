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
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function xgetDueDateAttribute($value)
    {
        return abi_date_short($value);
    }
    
    public function xsetDueDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $value ) 
                        : null;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /* Customer Orders */
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder')->orderBy('created_at', 'desc');
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
        $mystuff = collect([]);
        $lines = $this->customerorderlines;     // ()->whereHas('product');

// abi_r($lines, true);

        foreach($lines as $line)
        {
            if ( $line->product )
                if ( ($line->product->procurement_type == 'manufacture') ||
                 ($line->product->procurement_type == 'assembly') ) {

                    $mystuff->push($line);
                }

        }

// abi_r($mystuff, true);

        $num = $mystuff
//                    ->where('procurement_type', 'manufacture')
//                    ->where('procurement_type', 'assembly')
//                    ->filter(function($line) {
//                        return ($line->product->procurement_type == 'manufacture') ||
//                               ($line->product->procurement_type == 'assembly');
//                    })
                    ->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, [
                        'product_id' => $group->first()->product_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'quantity' => $group->sum('quantity'),
                      ]);
                    }, collect());

        // Sort order
        return $num->sortBy('reference');
    }

    /* Production Orders */
    
    public function productionorders()
    {
        return $this->hasMany('App\ProductionOrder')->orderBy('work_center_id', 'asc')->orderBy('product_reference', 'asc');
    }
    
    public function productionordersGrouped()
    {
        $mystuff = $this->productionorders;

//        $num = $mystuff->groupBy('product_id')->map(function ($row) {
//            return $row->sum('planned_quantity');
//        });


        $num = $mystuff->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, collect([
                        'product_id' => $group->first()->product_id,
                        'planned_quantity' => $group->sum('planned_quantity'),
                      ]));
                    }, collect());

//        abi_r($num, true);

        return $num;
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

/*
        $sorted = $num->sortBy(function ($product, $key) {
            abi_r($key);
            abi_r($product);
            return $product['reference'];
        });
*/
        return $num->sortBy('reference');
    }

    /* Products not Scheduled */
    
    public function productsNotScheduled()
    {
        $list = [];

        $required  = $this->customerorderlinesGrouped();
        $scheduled = $this->productionordersGrouped();

//        abi_r($required, true);

        if (!$scheduled->count()) return $required;

//        abi_r($scheduled->first(), true);
//         abi_r($scheduled);

        foreach ($required as $pid => $line) {

//            abi_r($line, true);

            $pid = $line['product_id'] ;
            $sch = $scheduled->first(function($item) use ($pid) {
//                abi_r($item, true);
                return $item['product_id'] == $pid;
            });

//                abi_r($sch);

            if ( $sch ) {
                $qty = $line['quantity'] - $sch['planned_quantity'];

                if ($qty) {
                    $line['quantity'] = $qty;
                    $list[] = $line;
                }

            } else {
                $list[] = $line;
            }
        }

        return collect($list)->sortBy('reference');
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
