<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\PriceRule;
use App\Configuration;

use App\Traits\DateFormFormatterTrait;

class PriceRulesController extends Controller
{

    use DateFormFormatterTrait;

    protected $pricerule;

    public function __construct(PriceRule $pricerule)
    {
        $this->pricerule = $pricerule;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates(['date_from', 'date_to'], $request);

        $rules = $this->pricerule
            ->filter($request->all())
            ->with('category')
            ->with('product')
            ->with('combination')
            ->with('customer')
            ->with('customergroup')
            ->with('currency')
            ->orderBy('product_id', 'ASC')
            ->orderBy('customer_id', 'ASC')
            ->orderBy('from_quantity', 'ASC');

        $rules = $rules->paginate(Configuration::get('DEF_ITEMS_PERPAGE'));

        $rules->setPath('pricerules');     // Customize the URI used by the paginator

        return view('price_rules.index', compact('rules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('price_rules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates(['date_from', 'date_to'], $request);

        $this->validate($request, PriceRule::$rules);

        $pricerule = $this->pricerule->create($request->all() + ['rule_type' => 'price']);

        if ($request->ajax()) {

            return response()->json([
                                        'msg'  => 'OK',
                                        'data' => $pricerule->toArray(),
                                    ]);
        }

        return redirect('pricerules')
            ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $pricerule->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $stockmove = $this->pricerule->findOrFail($id);

        return view('price_rules.show', compact('stockmove'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->pricerule->find($id)->delete();

        return redirect()->back()
                         ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}