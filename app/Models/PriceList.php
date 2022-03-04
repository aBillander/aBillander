<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Helpers\Calculator;

use App\Traits\ViewFormatterTrait;

class PriceList extends Model {

    use ViewFormatterTrait;
    //    use SoftDeletes;

    protected $dates = ['last_imported_at'];

    public static $types = array(
            'price', 
            'discount', 
            'margin',
        );

	protected $fillable = ['name', 'type', 'price_is_tax_inc', 'amount', 'currency_id', 'last_imported_at'];

	public static $rules = array(
                                    'name' => 'required',
                                    'currency_id' => 'exists:currencies,id'
                                );
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['currency'];

    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l($type, [], 'appmultilang');
            }

            return $list;
    }

    /**
     * Handy method
     * 
     */

    public function getType()
    {
        $features = '';

        $features .=  l($this->type, [], 'appmultilang');

        return $features;
    }

    public function getTypeVerbose()
    {
        $features = '';

        $features .=  l($this->type, [], 'appmultilang');

        switch ($this->type) {
            case 'price':
                //
                break;
            
            case 'discount':
            
            case 'margin':
                $features .=  ' ('.$this->as_percent( 'amount' ).'%)';
                break;
            
            default:
                //
                break;
        }

        return $features;
    }

    public function calculatePrice( Product $product )
    {
        switch ($this->type) {
            // Discount percentage
            case 'discount':
                $price = $this->price_is_tax_inc
                         ? $product->price_tax_inc
                         : $product->price;
                $price = $price*(1.0-($this->amount/100.0));
                break;
            // Margin percentage
            case 'margin':
                $bprice = Calculator::price($product->cost_price, $this->amount);
                $price = $this->price_is_tax_inc
                         ? $bprice*(1.0+($product->tax->percent/100.0))
                         : $bprice;
                break;
            // Fixed price
            case 'price':
            default:
                $price = $this->price_is_tax_inc
                         ? $product->price_tax_inc
                         : $product->price;
                break;
        }

        // Convert to Price List Currency
        // $currency = Currency::find( $this->currency_id );
        $currency = $this->currency;    // Currency is eager loaded with model

        if ( !$currency ) // Convention: No currency is defaut currency
            // $currency = Currency::find( intval(Configuration::get('DEF_CURRENCY')) );
            ;
        else
            $price = $price * $currency->conversion_rate / Context::getContext()->currency->conversion_rate;

        return $price;
    }

    public function getPrice( Product $product )
    {
        $line = $this->getLine( $product );

        if ( !$line ) return null;

        $price = new Price( $line->price, $this->price_is_tax_inc, $this->currency);

        if ( $this->price_is_tax_inc )
        {
            $tax_percent = $product->tax->percent;

            $price->applyTaxPercent( $tax_percent );
        }

        $price->price_list_id = $this->id;
        $price->price_list_line_id = $line->id;

        return $price;
    }

    public function addLine( Product $product, $price = null )
    {
        return $this->addOrUpdateLine( $product, $price );
    }

    public function updateLine( Product $product, $price = null )
    {
        return $this->addOrUpdateLine( $product, $price );
    }

    public function addOrUpdateLine( Product $product, $price = null )
    {
        if ($price === null) $price = $this->calculatePrice( $product );

        $line = PriceListLine::firstOrCreate( [ 'price_list_id' => $this->id, 'product_id' => $product->id ], [ 'price' => $price ] );

        return $line;
    }

    public function prepareLine( Product $product, $price = null )
    {
        if ($price === null) $price = $this->calculatePrice( $product );

        // Not persist, please:
        $line = new PriceListLine( [ 'price_list_id' => $this->id, 'product_id' => $product->id , 'price' => $price ] );

        return $line;
    }

    public function getLine( Product $product )
    {
        $line = $this->pricelistlines()->where('product_id', '=', $product->id)->first();

        if ( !$line ) 
            switch ( Configuration::get('PRODUCT_NOT_IN_PRICELIST') ) {
                case 'pricelist':
                    # calculate price according to Price list type
                    return $this->prepareLine( $product );
                    break;
                
                case 'product':
                    # take price from Product data
                    $price = $this->price_is_tax_inc
                         ? $product->price_tax_inc
                         : $product->price;

                    // Convert to Price List Currency
                    $currency = $this->currency;    // Currency is eager loaded with model

                    $price = $price * $currency->conversion_rate / Context::getContext()->currency->conversion_rate;

                    return $this->prepareLine( $product, $price );
                    break;
                
                case 'block':
                    # disallow sales
                
                default:
                    return null;
                    break;
            }

        return $line;
    }

    public function removeLine( Product $product )
    {
        $line = $this->pricelistlines()->where('product_id', '=', $product->id)->first();

        $line->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function pricelistlines() 
    {
        return $this->hasMany(PriceListLine::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
}