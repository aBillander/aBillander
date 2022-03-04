<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class ProductMeasureUnit extends Model {

    use ViewFormatterTrait;
//    use SoftDeletes;

//    protected $appends = ['fullName'];

//    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'product_id', 'measure_unit_id', 'stock_measure_unit_id', 
                            'conversion_rate', 'active' ];

    public static $rules = [
//        'product_id' => 'required|exists:products,id',
        'measure_unit_id' => 'required|exists:measure_units,id',
//        'stock_measure_unit_id' => 'required|exists:measure_units,id',
        'conversion_rate'      => 'required|numeric|min:0',
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
        return $this->belongsTo(Product::class);
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }

    public function mainmeasureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'stock_measure_unit_id');
    }
}