<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use ReflectionClass;

use App\CommissionSettlementLine;

use App\Traits\ViewFormatterTrait;

class CommissionSettlement extends Model
{
    // https://study.com/academy/lesson/markup-markdown-calculation-examples.html
/*

 the business process used in almost all industries for calculating the sales commission for sales partners and to do the commission settlement: The commission is calculated on order net value (before tax) and based on fixed percentages (sales_rep->commission_percent), mark-up value (margin) and mark-down value (discount). 

*/

    use ViewFormatterTrait;

    public static $statuses = array(
            'pending',
            'closed',           // Settlement cannot be modified after this date
        );

    protected $dates = ['document_date', 'date_from', 'date_to', 'close_date', 'posted_at'];
	
    protected $fillable = [ 'document_date', 'date_from', 'date_to', 'paid_documents_only',  
    						'status', 'onhold', 
    						'total_commissionable', 'total_settlement', 
    						'notes',
    						'close_date', 'posted_at',
    						'sales_rep_id'
                          ];

    public static $rules = [
		    	'document_date'    => 'required|date',
		    	'date_from'    => 'required|date',
		    	'date_to'    => 'required|date',
		        'sales_rep_id'  => 'required|exists:sales_reps,id',
    	];
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, 'appmultilang');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l(get_called_class().'.'.$status, 'appmultilang');
    }

    public function getStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->status, 'appmultilang');
    }


    public function close()
    {
        // Can I ...?
        if ( $this->status == 'closed' ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Do stuf...
        $this->status = 'closed';
        $this->close_date = \Carbon\Carbon::now();

        $this->save();

        return true;
    }


    public function addDocuments( $documents = [] )
    {
        foreach ($documents as $document) {
        	# code...
        	$this->addDocument( $document );
        }

        // Update Settlement
        $this->updateTotal();

    }

    public function addDocument( $document = null )
    {
        if ( !$document ) return;

        $this->load('salesrep');
        $commission_percent = $this->salesrep->commission_percent;
        $commissionable = Configuration::get('SALESREP_COMMISSION_METHOD') == 'TAXEXC' ? 
                                        $document->total_tax_excl : 
                                        $document->total_tax_incl;
        $commission = $commissionable * $commission_percent / 100.0;

        $data = [
        		'commissionable_id' => $document->id, 
        		'commissionable_type' => ( new ReflectionClass($document) )->getName(),
        		'document_reference' => $document->document_reference, 
        		'document_date' => $document->document_date, 

    			'document_commissionable_amount' => $commissionable, 
    			'commission_percent' => $commission_percent, 
    			'commission' => $commission,

 //   			'commission_settlement_id' => $this->id
        ];

        $line = CommissionSettlementLine::create( $data );

        $this->commissionsettlementlines()->save($line);

    }

    public function updateTotal()
    {
         $total_commissionable = $this->commissionsettlementlines()->sum('document_commissionable_amount');
         $total_settlement = $this->commissionsettlementlines()->sum('commission');

         return $this->update( compact('total_commissionable', 'total_settlement') );
    }

    // Alias
    public function nbrItems()
    {
        return $this->nbr_commissionsettlementlines();
    }

    public function calculateCommission()
    {
        return 'Not implemented, so far';
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function commissionsettlementlines()
    {
        return $this->hasMany('App\CommissionSettlementLine', 'commission_settlement_id');
    }
    
    public function nbr_commissionsettlementlines()
    {
        return $this->commissionsettlementlines->count();
    }


    public function salesrep()
    {
        return $this->belongsTo('App\SalesRep', 'sales_rep_id');
    }
    
}
