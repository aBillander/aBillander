<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

use App\EmailLog;

use App\Traits\DateFormFormatterTrait;

class EmailLogsController extends Controller
{
   
   use DateFormFormatterTrait;

   protected $emaillog;

   public function __construct(EmailLog $emaillog)
   {
        $this->emaillog = $emaillog;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );
        

        $emaillogs = $this->emaillog
                                ->filter( $request->all() )
                                ->with('userable')
                                ->orderBy('created_at', 'DESC');

//         abi_r($mvts->toSql(), true);

        $emaillogs = $emaillogs->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );
        // $mvts = $mvts->paginate( 1 );

        $emaillogs->setPath('emaillogs');     // Customize the URI used by the paginator

        return view('email_logs.index')->with(compact('emaillogs'));
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
     * @param  \App\Models\ActivityLogger  $activitylogger
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLogger $activitylogger)
    {

        return 'OKKO';


        return view('activity_loggers.show', compact('activitylogger', 'loggers', 'logger_errors', 'logger_warnings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActivityLogger  $activitylogger
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityLogger $activitylogger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActivityLogger  $activitylogger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityLogger $activitylogger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityLogger  $activitylogger
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityLogger $activitylogger)
    {
        //
    }
}
