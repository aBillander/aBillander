<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use \Lang as Lang;

class Sequence extends Model {

    use SoftDeletes;

    public static $types = array(
            'Product', 
            'Customer', 
            'CustomerOrder',
            'CustomerInvoice',
            'StockCount',
        );

    // Move this to config folder? Maybe yes...
    public static $models = array(
            Product::class         => 'Product', 
            Customer::class        => 'Customer', 
            CustomerOrder::class   => 'CustomerOrder',
            CustomerInvoice::class => 'CustomerInvoice',
        );

    protected $dates = ['deleted_at', 'last_date_used'];
	
    protected $fillable = [ 'name', 'model_name', 
    						'prefix', 'length', 'separator', 
    						'next_id', 'active'
                          ];

    public static $rules = array(
    	'name'    => 'required|min:2|max:128',
    	'model_name' => 'required',
    	'next_id' => 'integer|min:0',
    	);

    
    public static function listFor( $model_class = '' )
    {
//        abi_r( $model_class);
//        abi_r( self::$models);

        if ( !$model_class ) return [];

        $model = self::$models[$model_class] ?? '';

//        abi_r($model, true);

        if ( !$model ) return [];

        $list = \App\Sequence::where('model_name', '=', $model)->pluck('name', 'id')->toArray();
        if (!$list) $list = array();

        return $list;
    }

    public static function documentList()
    {
        $list = array();

        $types = self::$types;

        foreach($types as $type)
            $list[$type]    = Lang::get('appmultilang.'.$type);

        ksort($list);

        return $list;
    }

    public function getFormatAttribute()
    {
        $format = $this->prefix . $this->separator . str_pad('XX', $this->length, '0', STR_PAD_LEFT);

        return $format;
    }

	public function getNextDocumentId() {
		$docId = $this->next_id;
		$this->next_id++;
		$this->last_date_used = \Carbon\Carbon::now();
		$this->save();

		return $docId;
	}

    public function getDocumentReference($id = 0)
    {
        $format = $this->prefix . $this->separator . str_pad(strval(intval($id)), $this->length, '0', STR_PAD_LEFT);

        return $format;
    }
}
