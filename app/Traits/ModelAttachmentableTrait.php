<?php 

namespace App\Traits;

use App\Models\ModelAttachment;


trait ModelAttachmentableTrait
{

    // Attachments
    public function attachments()
    {
        return $this->morphMany(ModelAttachment::class, 'attachmentable');
    }

    // Alias
    public function modelattachments()
    {
        return $this->attachments();
    }



/* ********************************************************************************************* */    


}