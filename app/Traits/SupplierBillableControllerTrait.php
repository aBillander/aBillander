<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait SupplierBillableControllerTrait
{
    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function ajaxSupplierSearch(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;

//        if ( $query )

        if ($request->has('supplier_id'))
        {
            $search = $request->supplier_id;

            $suppliers = \App\Supplier::
                    // select('id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'sales_rep_id', 'invoice_sequence_id')
                                      with('currency')
                                    ->with('addresses')
                                    ->find( $search );

            $suppliers->invoice_sequence_id = $suppliers->getInvoiceSequenceId();

//            return $suppliers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $suppliers );
//            return json_encode( $suppliers );
            return response()->json( $suppliers );
        }

        if ($request->has('term'))
        {
            $search = $request->term;

            $suppliers = \App\Supplier::select('id', 'name_fiscal', 'name_commercial', 'identification', 'sales_equalization', 'payment_method_id', 'currency_id', 'invoicing_address_id')
                                    ->where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
                                    ->isNotBlocked()
                                    ->with('currency')
                                    ->with('addresses')
                                    ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );

//            return $suppliers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $suppliers );
//            return json_encode( $suppliers );
            return response()->json( $suppliers );
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
    }

    public function supplierAdressBookLookup($id)
    {
        if (intval($id)>0)
        {
            $supplier = \App\Supplier::find($id);

            if ( $supplier )
                return response()->json( $supplier->getAddressList() );
        }

        // die silently
        return json_encode( [] );
    }

}