<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class ProductTool extends Model {

    use ViewFormatterTrait;
//    use SoftDeletes;

//    protected $appends = ['fullName'];

//    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'product_id', 'tool_id', 'active' ];

    public static $rules = [
        'product_id' => 'required|exists:products,id',
        'tool_id' => 'required|exists:tools,id',
    	];
    
    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function tool()
    {
        return $this->belongsTo('App\Tool');
    }
}