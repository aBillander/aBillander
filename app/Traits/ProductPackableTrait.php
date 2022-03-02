<?php 

namespace App\Traits;


trait ProductPackableTrait
{

    public function getQuantityOnhandAttribute($value)
    {
        if ( $this->product_type != 'grouped')
            return $value;

        $this->load(['packitems', 'packitems.product']);

        foreach ($this->packitems as $item) {
            $item->can_assemble = $item->product->quantity_onhand / $item->quantity;
        }

        $can_assemble = floor($this->packitems->reduce(function($carry, $item){
                                        return $carry === null || $item->can_assemble < $carry ? $item->can_assemble : $carry;
                                    }, null));

        return $can_assemble > 0 ? $can_assemble : 0;
    }


    public function isGrouped()
    {
        return $this->product_type == 'grouped';
    }

    // Alias
    public function isPack()
    {
        return $this->isGrouped();
    }


    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // PackItems
    public function packitems()
    {
        return $this->hasMany('App\PackItem')->orderBy('line_sort_order', 'asc');
    }



/* ********************************************************************************************* */    


}