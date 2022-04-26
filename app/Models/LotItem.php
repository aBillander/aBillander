<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class LotItem extends Model
{
    use ViewFormatterTrait;

	protected $fillable = ['lot_id', 'is_reservation', 'quantity'
    ];

    public static $rules = [
        'lot_id' => 'exists:lots,id',
        'quantity' => 'required|numeric'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
    
    public function lotable()
    {
        return $this->morphTo();
    }

    // Reverse relation
//    public function lotitems()
//    {
//        return $this->morphMany(Lot::class, 'lotable');
//    }
/*
    // Alias
    public function lots()
    {
        return $this->lotitems();
    }
*/

    public function getLotableDocumentRoute()
    {
            // static $segment;

            // if ($segment) return $segment;

            $str = $this->lotable_type;
            if ( !$str ) return $segment = '';

            $segments = array_reverse(explode('\\', $str));


            // Last segment
            // $str = substr( $segments[0], 0, -strlen('Line') );
            // Better approach:
            $str = substr( $segments[0], 0, strpos($segments[0], "Line") );

            if ( !$str ) 
                $str = $segments[0];

            $segment = strtolower($str);

            return \Str::plural($segment);
    }
}
