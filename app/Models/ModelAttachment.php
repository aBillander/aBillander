<?php

namespace App\Models;

// use Illuminate\Http\File;
use Illuminate\Database\Eloquent\Model;

use File;

class ModelAttachment extends Model
{
	
    protected $fillable = [ 'name', 'description', 'position', 'filename', 'attachmentable_id', 'attachmentable_type' ];
    

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment)
        {
            // before delete() method call this 
            $class = ModelAttachment::getClassFolder( $attachment->attachmentable_type );

            // Delete file from storage
            $fname = ModelAttachment::full_pathAttachments( $class ) .'/'. $attachment->filename;
            if ($fname)
                @unlink($fname);

            });

    }


    /**
     * Get Path for Attachments.
     * public static <storage_path>/tenants/<tenant>/attachments
     */
    public static function full_pathAttachments( $classname = 'other' )
    {
        $directoryPath = abi_tenant_attachments_full_path( '/'.$classname);

        if(!File::isDirectory($directoryPath)){
            //make the directory because it doesn't exists
            File::makeDirectory($directoryPath);    // , 0777, true, true);
        }

        return $directoryPath;
    }
    
    
    public static function getClassFolder( $classname = 'other' )
    {
        $segments = array_reverse(explode('\\', $classname));

        return $segments[0];
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
