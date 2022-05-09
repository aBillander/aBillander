<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ActivityLogger;
use App\Models\Configuration;

use App\Helpers\Exports\ArrayExport;
use Excel;

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
        $loggers = $this->logger->where('description', 'NOT LIKE', '%devMessenger%')->orderBy('updated_at', 'desc');
//                ->orderBy( function ($activitylogger) { return $activitylogger->last_modified_at; } );

        // See: ProductionSheetsController->index



        $loggers = $loggers->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($loggers, true);

        $loggers->setPath('activityloggers');     // Customize the URI used by the paginator

//        $loggers = collect([]);

        return view('activity_loggers.index', compact('loggers'));

/*
        $loggers = $this->logger->filter( $request->all() )->orderBy('id', 'desc');


        $loggers = $loggers->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($loggers, true);

        $loggers->setPath('activityloggers');     // Customize the URI used by the paginator

        $logger_errors = $this->logger->filter( $request->all() )->where('level_name', 'ERROR')->count();

        $logger_warnings = $this->logger->filter( $request->all() )->where('level_name', 'WARNING')->count();

        $log_names = $this->logger->distinct('log_name')->orderBy('log_name', 'desc')->pluck('log_name','log_name')->toArray();

//        $loggers = collect([]);

        return view('activity_loggers.index', compact('loggers', 'logger_errors', 'logger_warnings', 'log_names'));
*/
        
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
    public function show(ActivityLogger $activitylogger, Request $request)
    {

        $level = $request->input('level');

        $loggers = $activitylogger->activityloggerlines()->when($level, function($query) use ($level) {

                                $query->where('level_name', $level);

                        })->orderBy('id', 'desc');



        $logger_all = $activitylogger->activityloggerlines()->count();

        $logger_errors = $activitylogger->activityloggerlines()->where('level_name', 'ERROR')->count();

        $logger_warnings = $activitylogger->activityloggerlines()->where('level_name', 'WARNING')->count();


        $loggers = $loggers->paginate( Configuration::get('DEF_LOGS_PERPAGE') );

        // abi_r($loggers, true);

        $loggers->setPath('');     // Customize the URI used by the paginator

        $autorefresh = $request->has('autorefresh');

        // if ($autorefresh)
        //    $activitylogger->empty();



        return view('activity_loggers.show', compact('activitylogger', 'loggers', 'logger_errors', 'logger_warnings', 'logger_all', 'autorefresh'));
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
        $id = $activitylogger->id;

        // Lines
        $activitylogger->activityloggerlines()->delete();

        // Logger
        $activitylogger->delete();

        return redirect('activityloggers')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function empty()
    {
        // $this->logger->empty();

        // Preserve aBillander_messenger
        // $this->logger->where('log_name', '!=', 'aBillander_messenger')->delete();

        return redirect('activityloggers')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(ActivityLogger $activitylogger)
    {        

        $loggers = $activitylogger
                        ->activityloggerlines()
                        ->orderBy('id', 'desc')
                        ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [ l('ID', [], 'layouts'), l('Date/Time'), l('Type'), l('Message')
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($loggers as $logger) {
            // $data[] = $line->toArray();

            $row = [];

            $row[] = $logger->id;
            $row[] = $logger->date_added . ' '. sprintf( "(.%04s)",   intval(intval($logger->secs_added)/100.0) );
            $row[] = $logger->level_name;
            $row[] = strip_tags(htmlspecialchars_decode($logger->message));

            $data[] = $row;
        }

        $styles = [];

        $sheetTitle = 'Activity LOG';

        $export = (new ArrayExport($data, $styles))->setTitle($sheetTitle);

        $id = $activitylogger->id;

        $sheetFileName = 'Activity_Logger_'.$id;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}
