<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentAscription extends Model {


    protected $table = 'parent_child';

    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customerinvoices()
    {
       return $this->belongsTo('CustomerInvoice', 'childable_id');
    }

    public function wc_orders()
    {
       return $this->belongsTo('aBillander\WooConnect\WooOrder', 'parentable_id');
    }

}