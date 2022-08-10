<?php

namespace Queridiam\POS\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegisterSession extends Model
{
//    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'starting_cash_denominations' => 'array', 
        'closing_cash_denominations'  => 'array', 
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['open_date', 'close_date'];
    
    protected $fillable = [ 'starting_expected_cash', 'starting_cash', 'starting_added_cash', 'starting_total_cash', 
                            'expected_cash', 'closing_cash', 'taken_cash', 'closing_total_cash', 
                            'starting_total_cash_denominations', 'closing_total_cash_denominations', 
                            'open_date', 'close_date', 
                            'starting_notes', 'closing_notes', 
                            'cashier_user_id', 'cash_register_id', 
                          ];

    public static $rules = [
//                            'alias'   => 'required|min:2|max:32',
//                            'name'    => 'required|min:2|max:128',
                           ];

    
        
    /*
    |--------------------------------------------------------------------------
    | Methods & Accessors
    |--------------------------------------------------------------------------
    */

    public function isOpen()
    {
        return $this->close_date == NULL;
    }

    public function isClosed()
    {
        return $this->close_date != NULL;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cashieruser()
    {
        return $this->belongsTo(CashierUser::class, 'cashier_user_id');
    }

    public function cashregister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

}
