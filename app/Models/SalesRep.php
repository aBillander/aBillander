<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;

use App\Traits\ViewFormatterTrait;

// See: https://www.salesforcesearch.com/blog/httpwww-salesforcesearch-combid185259the-difference-between-hiring-a-sales-rep-vs-a-sales-agent/

class SalesRep extends Model {
    
    use ViewFormatterTrait;

    use SoftDeletes;

    public static $types = [
            'external',
            'employee',
        ];

    protected $dates = ['deleted_at'];
	
    protected $fillable = ['sales_rep_type', 'alias', 'identification', 'notes', 
                           'reference_external', 'accounting_id', 
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



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l(SalesRep::class.'.'.$type, [], 'appmultilang');
            }

            return $list;
    }

    public static function getTypeName( $status )
    {
            return l(SalesRep::class.'.'.$status, [], 'appmultilang');
    }



    // Get the full name of a SalesRep instance using Eloquent accessors
    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;

    }
    
    // Alias
    public function getNameAttribute() 
    {
        return $this->full_name;
    }
    
    public function getCommission( Product $product = null, Customer $customer = null ) 
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
        // return $this->hasOne(Address::class, 'owner_id')->where('model_name', '=', 'SalesRep');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function customerorders()
    {
        return $this->hasMany(CustomerOrder::class);
    }


    public function commissionsettlements()
    {
        return $this->hasMany(CommissionSettlement::class);
    }

    /**
     * Get the user record associated with the user.
     */
    public function user()
    {
        return $this->hasOne(SalesRepUser::class, 'sales_rep_id');   // ->where('is_principal', 1);
    }

    public function users()
    {
        return $this->hasMany(SalesRepUser::class, 'sales_rep_id');
    }
}
