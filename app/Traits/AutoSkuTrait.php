<?php 

namespace App\Traits;

use App\Models\Configuration;

trait AutoSkuTrait
{
    /**
    * Return (string) SKU
    */
    public function autoSKU( $autosave = true )
    {
        // Daddy is a Combination?
        if ( array_key_exists('product_id', $this->attributes) ) {
            $pid = $this->product_id;
            $cid = $this->id;
        } else {
            $pid = $this->id;
            $cid = '';
        }

        // Calculate SKU
        $pid_str = strval(intval( $pid + Configuration::get('SKU_PREFIX_OFFSET') ));
        $lp = strlen( $pid_str );
        if ( $lp < Configuration::get('SKU_PREFIX_LENGTH') ) 
             $lp = Configuration::get('SKU_PREFIX_LENGTH');

        $sku = str_pad($pid_str, $lp, '0', STR_PAD_LEFT);

        // Daddy is a Combination?
        if ( array_key_exists('product_id', $this->attributes) ) {
            // Calculate SKU suffix
            $cid_str = strval(intval( $cid ));
            $lc = strlen( $cid_str );
            if ( $lc < Configuration::get('SKU_SUFFIX_LENGTH') ) 
                 $lc = Configuration::get('SKU_SUFFIX_LENGTH');

            $sku .= Configuration::get('SKU_SEPARATOR') . str_pad($cid_str, $lc, '0', STR_PAD_LEFT);
        }

        if ($autosave) {
            $this->reference = $sku;
            $this->save();
        }

        return $sku;
    }
}