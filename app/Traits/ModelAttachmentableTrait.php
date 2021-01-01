<?php 

namespace App\Traits;


trait ModelAttachmentableTrait
{

    // Attachments
    public function attachments()
    {
        return $this->morphMany('App\ModelAttachment', 'attachmentable');
    }

    // Alias
    public function modelattachments()
    {
        return $this->attachments();
    }



/* ********************************************************************************************* */    


}