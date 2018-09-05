<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use \Lang as Lang;

class Template extends Model {

    use SoftDeletes;

    public static $types = array(
            'CustomerOrderPdf',
            'CustomerShippingSlipPdf',
            'CustomerInvoicePdf',
            'Pdf',
            'Mail',
        );

    // Move this to config folder? Maybe yes...
    public static $models = array(
            CustomerOrder::class   => 'CustomerOrderPdf',
            CustomerShippingSlip::class   => 'CustomerShippingSlipPdf',
            CustomerInvoice::class => 'CustomerInvoicePdf',
        );

    protected $dates = ['deleted_at'];

	protected $fillable = ['name', 'model_name', 
						   'folder', 'file_name', 
						   'paper', 'orientation'
						  ];

    public static $rules = array(
        'name' => array('required', 'min:2', 'max:128'),
        'model_name' => array('required'),
        'file_name' => array('required'),
    	);

    
    public static function listFor( $model_class = '' )
    {
//        abi_r( $model_class);
//        abi_r( self::$models);

        if ( !$model_class ) return [];

        $model = self::$models[$model_class] ?? '';

//        abi_r($model, true);

        if ( !$model ) return [];

        $list = \App\Template::where('model_name', '=', $model)->pluck('name', 'id')->toArray();
        if (!$list) $list = array();

        return $list;
    }

    public static function documentList()
    {
        $list = array();

        $types = self::$types;

        foreach($types as $type)
            $list[$type]    = l('template.'.$type, [], 'appmultilang');

        ksort($list);

        return $list;
    }

    public static function getTypeName( $types )
    {
            return l('template.'.$types, [], 'appmultilang');;
    }

    public static function getOrientationName( $types )
    {
            return l('orientation.'.$types, [], 'appmultilang');;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerinvoices()
    {
        return $this->hasMany('App\Customerinvoice');
    }

}