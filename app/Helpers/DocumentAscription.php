<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class DocumentAscription extends Model {


//    protected $table = 'parent_child';

    protected $guarded = ['id'];

    public static $rules = [
            'leftable_id'    => 'required',
            'leftable_type'  => 'required',

            'rightable_id'   => 'required',
            'rightable_type' => 'required',

            'type' => 'required',   // in: 'traceability', etc.
            ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function rightable()
    {
       return $this->morphTo();
    }

    public function leftable()
    {
       return $this->morphTo();
    }

/*
    public function customerinvoices()
    {
       return $this->belongsTo('CustomerInvoice', 'childable_id');
    }

    public function wc_orders()
    {
       return $this->belongsTo('aBillander\WooConnect\WooOrder', 'parentable_id');
    }
*/
}