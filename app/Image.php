<?php 

namespace App;

// use App\Http\Requests;
use Illuminate\Http\Request;

use \iImage as iImage;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
	
    public static $products_path = '/uploads/images_p/';
    protected static $access_rights = 0775;   
    // See: https://laracasts.com/discuss/channels/general-discussion/where-do-you-set-public-directory-laravel-5
    
    public static $products_types = array(
            'mini_default'   => ['width' => 45,  'height' => 45 ],
            'small_default'  => ['width' => 98,  'height' => 98 ],
            'medium_default' => ['width' => 125, 'height' => 125],
//            'home_default'   => ['width' => 250, 'height' => 250],
            'large_default'  => ['width' => 458, 'height' => 458],
        );
	
    protected $fillable = [ 'extension', 'caption', 'position', 'is_featured', 'active' ];

    public static $rules = array(
    	'caption'  => array('max:128'),
        'position' => array('min:0'),
        'image'    => 'required | mimes:jpeg,jpg,gif,png,svg | max:8000',
        // Seems Laravel cannot validate png if it is the last mime (?)
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public static function createForProduct(Request $request)     // Check: https://stackoverflow.com/questions/19404030/extend-override-eloquent-create-method-cannot-make-static-method-non-static
    {
        $extension = $request->file('image')->getClientOriginalExtension();
        // $image_raw = self::create($request->except(['image']));
        $request->merge(['extension' => $extension]);
        $image_raw = self::create($request->all());

        $imageName = $image_raw->id;
        $destinationFolder = self::$products_path . $image_raw->getImageFolder();
        // Create Folder
        $image_raw->createImageFolder();
       
        $file = $request->file('image');

        /* Note to self:
        $path = 'https://i.stack.imgur.com/koFpQ.png';
        $filename = basename($path);

        Image::make($path)->save(public_path('images/' . $filename));
        */

        //create instance of image from temp upload

        $image = iImage::make($file->getRealPath())
                ->save(public_path() . $destinationFolder . $imageName . '.' . $extension);

//        foreach (array_reverse(self::$products_types) as $type => $size) {
        foreach (self::$products_types as $type => $size) {
            $imager = iImage::make(public_path() . $destinationFolder . $imageName . '.' . $extension);

            // This will generate an image with transparent background
            // If you need to have a background you can pass a third parameter (e.g: '#000000')
            $canvas = iImage::canvas($size['width'], $size['height']);
    
            $imager->resize($size['width'], $size['height'], function ($constraint) {
                    $constraint->aspectRatio();
 //                   $constraint->upsize();
                });
    
            $canvas->insert($imager, 'center');
            $canvas->save(public_path() . $destinationFolder . $imageName . '-' . $type . '.' . $extension, 100);
            // $image->save(public_path() . $destinationFolder . $imageName . '-' . $type . '.' . $extension, 100);

            // ImageMagick module not available with this PHP installation.
        }

        return $image_raw;
                
    }

    
    public static function createForProductFromPath( $img_path = '', $params = [] )
    {
        if (!$img_path || !file_exists($img_path)) return null;

        // http://image.intervention.io/api/make

        $extension = pathinfo($img_path, PATHINFO_EXTENSION);

        $data = [
                'caption' => null,
                'extension' => $extension,
                'position' => 0,
                'is_featured' => 0,
                'active' => 1,
                ];
        $data = array_merge($data, $params);
        $image_raw = self::create($data);

        $imageName = $image_raw->id;
        $destinationFolder = self::$products_path . $image_raw->getImageFolder();
        // Create Folder
        $image_raw->createImageFolder();

        //create instance of image from path

        $image = iImage::make($img_path)
                ->save(public_path() . $destinationFolder . $imageName . '.' . $extension);

//        foreach (array_reverse(self::$products_types) as $type => $size) {
        foreach (self::$products_types as $type => $size) {
            $imager = iImage::make(public_path() . $destinationFolder . $imageName . '.' . $extension);

            // This will generate an image with transparent background
            // If you need to have a background you can pass a third parameter (e.g: '#000000')
            $canvas = iImage::canvas($size['width'], $size['height']);
    
            $imager->resize($size['width'], $size['height'], function ($constraint) {
                    $constraint->aspectRatio();
 //                   $constraint->upsize();
                });
    
            $canvas->insert($imager, 'center');
            $canvas->save(public_path() . $destinationFolder . $imageName . '-' . $type . '.' . $extension, 100);
            // $image->save(public_path() . $destinationFolder . $imageName . '-' . $type . '.' . $extension, 100);

            // ImageMagick module not available with this PHP installation.
        }

        return $image_raw;
                
    }

    
    public static function createForProductFromUrl( $img_url = '', $params = [] )
    {
        if (!$img_url) return null;     // ToDo: check if image exists

        $extension = pathinfo($img_url, PATHINFO_EXTENSION);        // See: https://www.w3schools.com/php/func_filesystem_pathinfo.asp

        $data = [
                'caption' => null,
                'extension' => $extension,
                'position' => 0,
                'is_featured'=> false,
                'active' => 1,
                ];
        $data = array_merge($data, $params);
        $image_raw = self::create($data);

        $imageName = $image_raw->id;
        $destinationFolder = self::$products_path . $image_raw->getImageFolder();
        // Create Folder
        $image_raw->createImageFolder();

        /* Note to self:
        $path = 'https://i.stack.imgur.com/koFpQ.png';
        $filename = basename($path);

        Image::make($path)->save(public_path('images/' . $filename));
        */

        //create instance of image from temp upload



// ERROR: https://www.google.com/search?client=ubuntu&channel=fs&q=+Intervention+%5C+Image+%5C+Exception+%5C+NotReadableExceptionUnable+to+init+from+given+url+&ie=utf-8&oe=utf-8
// https://github.com/Intervention/image/issues/283
// https://github.com/Intervention/image/issues/773

$file_data = file_get_contents( $img_url, false, stream_context_create( [
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ],
] ) );


//        $image = iImage::make( $img_url )
        $image = iImage::make( $file_data )
                ->save(public_path() . $destinationFolder . $imageName . '.' . $extension);

//        foreach (array_reverse(self::$products_types) as $type => $size) {
        foreach (self::$products_types as $type => $size) {
            $imager = iImage::make(public_path() . $destinationFolder . $imageName . '.' . $extension);

            // This will generate an image with transparent background
            // If you need to have a background you can pass a third parameter (e.g: '#000000')
            $canvas = iImage::canvas($size['width'], $size['height']);
    
            $imager->resize($size['width'], $size['height'], function ($constraint) {
                    $constraint->aspectRatio();
 //                   $constraint->upsize();
                });
    
            $canvas->insert($imager, 'center');
            $canvas->save(public_path() . $destinationFolder . $imageName . '-' . $type . '.' . $extension, 100);
            // $image->save(public_path() . $destinationFolder . $imageName . '-' . $type . '.' . $extension, 100);

            // ImageMagick module not available with this PHP installation.
        }

        return $image_raw;
                
    }


    /**
     * Create parent folders for the image in the new filesystem
     *
     * @return bool success
     */
    public function createImageFolder()
    {
        if (!$this->id) {
            return false;
        }

 //       echo_r(public_path() . self::$products_path . $this->getImageFolder()); die();

        if (!file_exists( public_path() . self::$products_path . $this->getImageFolder() )) {
            // Apparently sometimes mkdir cannot set the rights, and sometimes chmod can't. Trying both.
            $success = @mkdir(public_path() . self::$products_path . $this->getImageFolder(), self::$access_rights, true);
            $chmod = @chmod(public_path() . self::$products_path . $this->getImageFolder(), self::$access_rights);

            // Create an index.php file in the new folder
            if (($success || $chmod)
                && !file_exists(public_path() . self::$products_path . $this->getImageFolder().'index.php')
                && file_exists(public_path() . self::$products_path .'index.php')) {
                return @copy(public_path() . self::$products_path .'index.php', public_path() . self::$products_path . $this->getImageFolder().'index.php');
            }
        }
        return true;
    }

    /**
     * Delete the product image from disk and remove the containing folder if empty
     */
    public function deleteImage()
    {
        if (!$this->id) {
            return false;
        }

        // Delete base image
        if (file_exists(public_path() . self::$products_path . $this->getImageFolder() . $this->id . '.'.$this->extension)) {
            unlink(public_path() . self::$products_path . $this->getImageFolder() . $this->id . '.'.$this->extension);
        } else {
            return false;
        }

        $files_to_delete = array();

        // Delete auto-generated images
        foreach (self::$products_types as $type => $size) {
            $files_to_delete[] = public_path() . self::$products_path . $this->getImageFolder() . $this->id .
                                '-'. $type . '.' . $this->extension;
        }

        // delete index.php
        $files_to_delete[] = $this->image_dir.$this->getImageFolder().'index.php';

        foreach ($files_to_delete as $file) {
            if (file_exists($file) && !@unlink($file)) {
                return false;
            }
        }

        // Can we delete the image folder?
        if (is_dir( public_path() . self::$products_path . $this->getImageFolder() )) {
            $delete_folder = true;
            foreach (scandir( public_path() . self::$products_path . $this->getImageFolder() ) as $file) {
                if (($file != '.' && $file != '..')) {
                    $delete_folder = false;
                    break;
                }
            }
        }
        if (isset($delete_folder) && $delete_folder) {
            @rmdir( public_path() . self::$products_path . $this->getImageFolder() );
        }

        return true;
    }

    /**
     * Returns the path to the folder containing the image in the new filesystem
     *
     * @return string path to folder
     */
    public function getImageFolder()
    {
        if (!$this->id) {
            return false;
        }

        if (!is_numeric($this->id)) {
            return false;
        }
        $folder = str_split((string)$this->id);
        return implode('/', $folder).'/';
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get all of the owning imageable models.
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    public function combinations()
    {
        return $this->belongsToMany('App\Combination');
    }

}
