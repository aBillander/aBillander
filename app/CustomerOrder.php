<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'customer_id', 'reference', 'created_via', 
    						'date_created', 'date_paid', 'delivery_date', 'total', 
    						'customer', 'customer_note', 
    						'status', 
    						'production_at', 'production_sheet_id'
                          ];

    public static $rules = array(
//    	'id'    => 'required|unique',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methodss
    |--------------------------------------------------------------------------
    */
    
    public function customerCard()
    {
        $customer = unserialize( $this->customer );

        $card = $customer["first_name"].' '.$customer["last_name"] .'<br />'.
                $customer["address_1"] .'<br />'.
                $customer["city"].' - '.($customer["state_name"] ?? '').' <a href="#" class="btn btn-grey btn-xs disabled">'. $customer["phone"] .'</a>';

        return $card;
    }
    
    public function customerCardFull()
    {
        $customer = unserialize( $this->customer );

        $card = ($customer["company"] ? $customer["company"] .'<br />' : '').
                ($customer["first_name"] ? $customer["first_name"].' '.$customer["last_name"] .'<br />' : '').
                $customer["address_1"] . ($customer["address_2"] ? ' - ' : '') . $customer["address_2"] .'<br />'.
                $customer["city"].' - '.($customer["state_name"] ?? '').' <a href="#" class="btn btn-grey btn-xs disabled">'. $customer["phone"] .'</a>';

        return $card;
    }
    
    public function customerCardMini()
    {
        $customer = unserialize( $this->customer );

        $card = $customer["city"].' - '.($customer["state_name"] ?? '').' <a href="#" class="btn btn-grey btn-xs disabled">'. $customer["phone"] .'</a>';

        return $card;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productionsheet()
    {
        return $this->belongsTo('App\ProductionSheet', 'production_sheet_id');
    }
    
    public function customerorderlines()
    {
        return $this->hasMany('App\CustomerOrderLine', 'customer_order_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsOpen($query)
    {
        return $query->where( 'due_date', '>=', \Carbon\Carbon::now()->toDateString() );
    }
}
