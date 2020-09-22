<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;

use App\Configuration;

// See: https://www.salesforcesearch.com/blog/httpwww-salesforcesearch-combid185259the-difference-between-hiring-a-sales-rep-vs-a-sales-agent/

class SalesRep extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
	
    protected $fillable = ['alias', 'identification', 'notes', 'reference_external', 
                           'firstname', 'lastname', 'email', 'phone', 'phone_mobile', 'fax',
    					   'commission_percent', 'max_discount_allowed', 'pitw', 'active'];

    public static $rules = array(
        'alias'    => 'required|min:2|max:32',
        'firstname' => 'max:32',
        'lasttname' => 'max:32',
        'phone' => 'max:32',
        'phone_mobile' => 'max:32',
        'email' => 'required|email|max:128',
        'fax'   => 'max:32',

        'commission_percent' => 'numeric|min:0', 
        'max_discount_allowed' => 'numeric|min:0', 
        'pitw' => 'numeric|min:0',
//        'address1' => 'required|min:2|max:128',
//        'state_id' => 'exists:states,id',           // If State exists, Country must do also!
    	);


    // Get the full name of a User instance using Eloquent accessors
    
    public function getNameAttribute() 
    {
        return $this->firstname . ' ' . $this->lastname;
    }
    
    public function getCommission( \App\Product $product = null, \App\Customer $customer = null ) 
    {
        // ToDo: Apply more complex rules

        $commission_percent = $this->commission_percent;

        return $commission_percent;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function address()
    {
        // return $this->hasOne('App\Address', 'owner_id')->where('model_name', '=', 'SalesRep');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder');
    }


    public function commissionsettlements()
    {
        return $this->hasMany('App\CommissionSettlement');
    }

    /**
     * Get the user record associated with the user.
     */
    public function user()
    {
        return $this->hasOne('App\SalesRepUser', 'sales_rep_id');   // ->where('is_principal', 1);
    }

    public function users()
    {
        return $this->hasMany('App\SalesRepUser', 'sales_rep_id');
    }
}
