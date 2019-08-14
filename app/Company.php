<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    
    protected static $access_rights = 0775;

	protected $fillable = ['name_fiscal', 'name_commercial', 'identification', 'apply_RE', 
                            'website', 'company_logo','notes', 'currency_id', 'language_id'];
	
//    protected $guarded = array('id', 'address_id', 'currency_id');

    // Add your validation rules here
    public static $rules = array(
    	'name_fiscal' => array('required', 'min:2', 'max:128'),
        'website'     => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',     // See: https://laracasts.com/discuss/channels/general-discussion/url-validation
        'currency_id' => 'exists:currencies,id',
        'language_id' => 'exists:languages,id',
    	);

    
    /**
     * Get Path for images.
     * public static $company_path = '/tenants/localhost/company/';
     */
    public static function imagesPath()
    {
        return abi_tenant_local_path( 'company/' );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get all of the customer's Bank Accounts.
     */
    public function bankaccounts()
    {
        return $this->morphMany('App\BankAccount', 'bank_accountable');
    }

    public function bankaccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'bank_account_id')
                   ->where('bank_accountable_type', Company::class);
    }
    
    
    public function address()
    {
        // return $this->morphMany('App\Address', 'addressable')->first();
        // See: https://stackoverflow.com/questions/22012877/laravel-eloquent-polymorphic-one-to-one
        // https://laracasts.com/discuss/channels/general-discussion/one-to-one-polymorphic-inverse-relationship-with-existing-database
        return $this->hasOne('App\Address', 'addressable_id','id')
                   ->where('addressable_type', 'App\Company');
    }
    
    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function language()
    {
        return $this->belongsTo('App\Language');
    }
}