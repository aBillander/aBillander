<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use Illuminate\Http\Request;

class ActivityLoggersController extends Controller
{

   protected $logger;

   public function __construct(ActivityLogger $logger)
   {
        $this->logger = $logger;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggers = $this->logger->filter( $request->all() )->orderBy('id', 'desc');


        $loggers = $loggers->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($loggers, true);

        $loggers->setPath('activityloggers');     // Customize the URI used by the paginator

        $logger_errors = $this->logger->filter( $request->all() )->where('level_name', 'ERROR')->count();

        $logger_warnings = $this->logger->filter( $request->all() )->where('level_name', 'WARNING')->count();

        $log_names = $this->logger->distinct('log_name')->orderBy('log_name', 'desc')->pluck('log_name','log_name')->toArray();

//        $loggers = collect([]);

        return view('activity_loggers.index', compact('loggers', 'logger_errors', 'logger_warnings', 'log_names'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ActivityLogger  $activityLogger
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLogger $activityLogger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ActivityLogger  $activityLogger
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityLogger $activityLogger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ActivityLogger  $activityLogger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityLogger $activityLogger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ActivityLogger  $activityLogger
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityLogger $activityLogger)
    {
        //
    }


    public function empty()
    {
        // $this->logger->empty();

        // Preserve aBillander_messenger
        $this->logger->where('log_name', '!=', 'aBillander_messenger')->delete();

        return redirect('activityloggers')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }
}
