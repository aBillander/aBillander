<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Validation\Rule;

use \Lang as Lang;

class Sequence extends Model {

    use SoftDeletes;

    public static $types = array(
//            'Product', 
//            'Customer', 
            'CustomerQuotation',
            'CustomerOrder',
            'CustomerShippingSlip',
            'CustomerInvoice',
            'StockCount',

            'SepaDirectDebit',
        );

    // Move this to config folder? Maybe yes...
    public static $models = array(
//            Product::class         => 'Product', 
//            Customer::class        => 'Customer', 
            CustomerQuotation::class    => 'CustomerQuotation',
            CustomerOrder::class        => 'CustomerOrder',
            CustomerShippingSlip::class => 'CustomerShippingSlip',
            CustomerInvoice::class      => 'CustomerInvoice',
            
            StockCount::class      => 'StockCount',

            \aBillander\SepaSpain\SepaDirectDebit::class      => 'SepaDirectDebit',
        );

    protected $dates = ['deleted_at', 'last_date_used'];
	
    protected $fillable = [ 'name', 'model_name', 
    						'prefix', 'length', 'separator', 
    						'next_id', 'active'
                          ];

    public static $rules = array(
    	'name'    => 'required|min:2|max:128',
        'model_name' => 'required|in:',
//                            'required',
//                            Rule::in( self::$types ),
//                        ],
        'prefix' => 'required_with:separator',
        'length' => 'integer|min:2|max:12',
        'next_id' => 'integer|min:1',
    	);

    public static function get_rules()
    {
        $rules = self::$rules;

        $rules['model_name'] .= implode(',', self::$types);

        return $rules;
    }

    /**
     * Mutators
     */
    public function setPrefixAttribute($value)
    {
        $this->attributes['prefix'] = $value ?? '';
    }

    public function setSeparatorAttribute($value)
    {
        $this->attributes['separator'] = $value ?? '';
    }

    
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
            $list[$type]    = l($type, [], 'appmultilang');

        ksort($list);

        return $list;
    }

    public static function getTypeName( $types )
    {
            return l($types, [], 'appmultilang');
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
