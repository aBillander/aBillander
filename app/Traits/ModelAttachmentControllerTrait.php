<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\ModelAttachment;
use App\Configuration;

trait ModelAttachmentControllerTrait
{


    protected function attachmentStore($id, Request $request)
    {
        // github package: laravel-attachments

        // https://www.cloudways.com/blog/laravel-multiple-files-images-upload/
        // https://laravelarticle.com/laravel-ajax-image-upload
        // https://therichpost.com/upload-file-laravel-ajax-jquery/


        $allowedfileExtension = ['pdf','jpg','jpeg','png','docx'];

        $this->validate($request, [
//                'name' => 'required',
//                'attachment_file' => 'required | mimes:jpeg,jpg,gif,png,svg | max:8000',
//                'attachment_file' => 'required|mimes:'.implode(',', $allowedfileExtension).'|max:8000',
                'model_class' => 'required',
                'attachment_file' => 'required|max:8000',
        ]);

        $file = $request->file('attachment_file');

        $class = ModelAttachment::getClassFolder( $request->input('model_class') );

        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $check=in_array($extension,$allowedfileExtension);

        if($check)
        {


//                $filename = $file->store('attachments');        // Folder: /storage/app/attachments/
                // How to name an attachment, since there can be more than one per model?
//                $file_Name='popo'.'.'.$extension;
//                $file->move(storage_path('app/attachments'), $file_Name);

            $fname = $filename;
            $file->move( ModelAttachment::full_pathAttachments( $class ), $fname);

            ModelAttachment::create([
                    'name' => $request->input('attachment_name'), 
 //                   'description'
                    'filename' => $filename, 
                    'attachmentable_id' => $id,
                    'attachmentable_type' => $request->input('model_class'),
            ]);



//                ItemDetail::create([
//                    'item_id' => $items->id,
//                    'filename' => $filename
//                ]);

            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
        }
        else
        {
            return redirect()->back()
                    ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => $id], 'layouts') . 'Sorry Only Upload pdf, png , jpg , doc');
        }
    }


    protected function attachmentShow($id, $aid)
    {
        $attachment = ModelAttachment::findOrFail($aid);

        $class = ModelAttachment::getClassFolder( $attachment->attachmentable_type );

        // Delete file from storage
        $fname = ModelAttachment::full_pathAttachments( $class ) .'/'. $attachment->filename;

        return response()->download( $fname );
    }


    protected function attachmentDestroy($id, $aid)
    {
        $attachment = ModelAttachment::findOrFail($aid);

        $class = ModelAttachment::getClassFolder( $attachment->attachmentable_type );

        // Delete file from storage
        $fname = ModelAttachment::full_pathAttachments( $class ) .'/'. $attachment->filename;
        if ($fname)
            @unlink($fname);


        // Delete now!
        $attachment->delete();

        return redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $aid], 'layouts'));
    }


/* ********************************************************************************************* */    


}