<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelAttachment extends Model
{
	
    protected $fillable = [ 'name', 'description', 'position', 'filename', 'attachmentable_id', 'attachmentable_type' ];


    /**
     * Get Path for Attachments.
     * public static $products_path = '/uploads/images_p/';
     */
    public static function full_pathAttachments()
    {
        return abi_tenant_attachments_full_path();
    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get all of the owning attachmentable models.
     */
    public function attachmentable()
    {
        return $this->morphTo();
    }
    
}
