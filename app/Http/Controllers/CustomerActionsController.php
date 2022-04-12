<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\SalesRep;
use App\Models\User;
use App\Traits\DateFormFormatterTrait;
use Auth;
use Illuminate\Http\Request;

class CustomerActionsController extends  Controller
{
   use DateFormFormatterTrait;

   protected $customer;
   protected $action;

   public function __construct(Customer $customer, Action $action)
   {
        $this->customer = $customer;
        $this->action = $action;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customerId, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');
        
        $customer = $this->customer->findOrFail($customerId);

        $actions = $this->action
                    ->whereHas('customer', function ($query) use ($customerId) {
                            $query->where('id', $customerId);
                        })
                    ->filter( $request->all() )
                    ->with('actiontype')
                    ->with('salesrep')
                    ->with('contact')
                    ->orderBy('due_date', 'desc');

        $actions = $actions->paginate( $items_per_page );

        $actions->setPath('actions');

        $statusList = Action::getStatusList();

        $action_typeList = ActionType::orderby('name', 'desc')->pluck('name', 'id')->toArray();


        return view('customer_actions.index_by_customer', compact('customer', 'actions', 'statusList', 'action_typeList', 'items_per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customerId, Request $request)
    {
        $customer = $this->customer
                         ->with('contacts')
                         ->findOrFail($customerId);
        
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $statusList = Action::getStatusList();

        $action_typeList = ActionType::pluck('alias', 'id')->toArray();

        $customer_contactList = $customer->contacts->pluck('full_name', 'id')->toArray();

        $priorityList = Action::getPriorityList();

        $salesrepList = SalesRep::pluck('alias', 'id')->toArray();

        return view('customer_actions.create', compact('customer', 'back_route', 'statusList', 'action_typeList', 'customer_contactList', 'priorityList', 'salesrepList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($customerId, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['start_date', 'due_date', 'finish_date'], $request );

        $customer = $this->customer
                         ->with('actions')
                         ->findOrFail($customerId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Action::$rules);

        $request->merge(['user_created_by_id' => Auth::id()]);

        // Handy conversions
        if ( !$request->input('position') ) $request->merge( ['position' => 0  ] );

        $action = $this->action->create($request->all());

        $customer->actions()->save($action);

        return redirect($back_route)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $action->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function show($customerId, $id)
    {
        return $this->edit($customerId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function edit($customerId, $id, Request $request)
    {
        $action = $this->action
                            ->with('customer')
                            ->with('actiontype')
                            ->with('contact')
                            ->with('salesrep')
                            ->whereHas('customer', function($q) use ($customerId) {
                                $q->where('id', $customerId);
                            })
                            ->findOrFail($id);
        
        $customer = $action->customer;

        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $statusList = Action::getStatusList();

        $action_typeList = ActionType::pluck('alias', 'id')->toArray();

        $customer_contactList = $customer->contacts->pluck('full_name', 'id')->toArray();

        $priorityList = Action::getPriorityList();

        $salesrepList = SalesRep::pluck('alias', 'id')->toArray();


        // Dates (cuen)
        $this->addFormDates( ['start_date', 'due_date', 'finish_date'], $action );

        return view('customer_actions.edit', compact('customer', 'action', 'back_route', 'statusList', 'action_typeList', 'customer_contactList', 'priorityList', 'salesrepList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function update($customerId, $id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['start_date', 'due_date', 'finish_date'], $request );

        $action = $this->action
//                            ->with('customer')
                            ->whereHas('customer', function($q) use ($customerId) {
                                $q->where('id', $customerId);
                            })
                            ->findOrFail($id);
        
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Action::$rules);

        $action->update($request->all());

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $action->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId, $id, Request $request)
    {
        $action = $this->action->findOrFail($id);
        $back_route = $request->input('back_route', '');

        try {

            $action->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }
        
        return redirect( $back_route )
            ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') );
    }


    /* **************************************************************************************** */


    public function dashboard(Request $request)
    {
        // Dates (cuen)
        // $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $actions = $this->action
                    ->filter( $request->all() )
                    ->with('actiontype')
                    ->with('customer')
                    ->with('salesrep')
                    ->with('contact')
                    ->orderBy('due_date', 'desc');

        $actions = $actions->paginate( $items_per_page );

        $actions->setPath('dashboard');

        $statusList = Action::getStatusList();

        $action_typeList = ActionType::orderby('name', 'desc')->pluck('name', 'id')->toArray();


        return view('customer_actions.index', compact('actions', 'statusList', 'action_typeList', 'items_per_page'));
    }
}
