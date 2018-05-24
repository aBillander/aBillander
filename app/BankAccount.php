<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model {

    use SoftDeletes;

    protected $dates = ['mandate_date', 'first_recurring_date', 'deleted_at'];

	protected $fillable = ['name', 'iban', 'swift', 
						   'mandate_reference', 'mandate_date', 'first_recurring_date'];

	public static $rules = [
    	'name'    			=> array('required', 'min:2', 'max:64'),
        'iban' => array('required', 'min:4', 'max:34'),
        'swift' => array('min:8', 'max:11'),

        'mandate_reference' => 'max:35',
        'mandate_date' => 'date',
        'first_recurring_date' => 'date',
	];
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get all of the owning bank_accountable models.
     */
    public function bank_accountable()
    {
        return $this->morphTo();
    }
}