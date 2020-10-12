<?php 

namespace App;

use Carbon\Carbon;

class Modelo347 {

    // https://www.youtube.com/watch?v=JzoYPB77WaY

    public $year;
    public $max_amount;
	
    public function __construct( $year = 0, $max_amount = 3005.06 )
    {
        $this->year = $year > 0 ? $year : Carbon::now()->year;
        $this->max_amount = $max_amount;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods - Customers
    |--------------------------------------------------------------------------
    */

    public function getCustomers( $params = [] )
    {
        $customer_id = array_key_exists('customer_id', $params)
                            ? $params['customer_id'] 
                            : 0;

        $year = $this->year;
        $max_amount = $this->max_amount;

        $customers = Customer::
                          with('address')
                        ->whereHas('customerinvoices', function ($query) use ( $year, $max_amount, $customer_id ) {

                                // Closed Documents only
                                $query->where('status', 'closed');

                                if ( $customer_id > 0 )
                                    $query->where('customer_id', $customer_id);

                                // Date range
                                $query->whereYear('document_date', $year);

                                // Modelo 347 condition
                                $query->havingRaw('SUM(total_tax_incl) > ?', [$max_amount]);
                        })
                        ->get();
        
        return $customers;
    }

    public function getCustomerQuarterlySales($customer_id = null, $quarter = null) 
    {
        if ( ($customer_id == null) || ($quarter == null) )
            return 0.0;

        $year = $this->year;        
        $month_from = 3 * $quarter - 2;
        $month_to   = 3 * $quarter;
        // abi_r($year);abi_r($month_from );abi_r($month_to);die();
        $date_from = Carbon::create($year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($year, $month_to  , 1)->endOfMonth()->endOfDay();

        $sales = CustomerInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Customer
                        ->when($customer_id>0, function ($query) use ($customer_id) {

                                $query->where('customer_id', $customer_id);
                        })
                        // Date range
                        ->where('document_date', '>=', $date_from)
                        ->where('document_date', '<=', $date_to  )
                        // Final result
                        ->sum('total_tax_incl');

        return $sales;
    }

    public function getCustomerYearlySales($customer_id = null) 
    {
        if ( $customer_id == null )
            return 0.0;

        $year = $this->year;        
        $month_from = 1;
        $month_to   = 12;
        // abi_r($year);abi_r($month_from );abi_r($month_to);die();
        $date_from = Carbon::create($year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($year, $month_to  , 1)->endOfMonth()->endOfDay();

        $sales = CustomerInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Customer
                        ->when($customer_id>0, function ($query) use ($customer_id) {

                                $query->where('customer_id', $customer_id);
                        })
                        // Date range
                        ->whereYear('document_date', $year )
                        // Final result
                        ->sum('total_tax_incl');

        return $sales;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods - Suplliers
    |--------------------------------------------------------------------------
    */

    public function getSuppliers( ) 
    {
        $suppliers = collect([]);

        return $suppliers;
    }
}