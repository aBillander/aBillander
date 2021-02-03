<?php 

namespace App\Traits;


trait ProductPackableTrait
{

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