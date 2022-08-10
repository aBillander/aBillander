<?php

namespace Queridiam\POS\Http\Controllers;

use App\Http\Controllers\Controller;
use Queridiam\POS\Models\CashierUser;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class POSCashierUsersController extends Controller
{

   protected $cashier_user;

   public function __construct(CashierUser $cashier_user)
   {
        $this->middleware('auth:cashier');

        $this->cashier_user = $cashier_user;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\CashierUser  $cashierUser
     * @return \Illuminate\Http\Response
     */
    public function show(CashierUser $cashierUser)
    {
        //
    }

    /**
     * Show the form for editing the profile of logged in user.
     *
     * @param  \App\Models\CashierUser  $cashierUser
     * @return \Illuminate\Http\Response
     */
    public function edit(CashierUser $cashierUser)
    {
        // Get logged in user
        $user = $cashier_user = Auth::user();
        // $customer      = Auth::user()->customer;

        $tab_index = 'account';

        $languageList = Language::pluck('name', 'id')->toArray();
        
        return view('pos::account.edit', compact('cashier_user', 'user', 'tab_index', 'languageList'));
    }

    /**
     * Update user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashierUser  $cashierUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashierUser $cashierUser)
    {
        try {
            $user_id = $request->session()->get('user.id');
            $input = $request->only(['surname', 'first_name', 'last_name', 'email', 'language', 'marital_status',
                'blood_group', 'contact_number', 'fb_link', 'twitter_link', 'social_media_1',
                'social_media_2', 'permanent_address', 'current_address',
                'guardian_name', 'custom_field_1', 'custom_field_2',
                'custom_field_3', 'custom_field_4', 'id_proof_name', 'id_proof_number', 'gender', 'family_number', 'alt_number']);

            if (!empty($request->input('dob'))) {
                $input['dob'] = $this->moduleUtil->uf_date($request->input('dob'));
            }
            if (!empty($request->input('bank_details'))) {
                $input['bank_details'] = json_encode($request->input('bank_details'));
            }

            $user = User::find($user_id);
            $user->update($input);

            Media::uploadMedia($user->business_id, $user, request(), 'profile_photo', true);

            //update session
            $input['id'] = $user_id;
            $business_id = request()->session()->get('user.business_id');
            $input['business_id'] = $business_id;
            session()->put('user', $input);

            $output = ['success' => 1,
                        'msg' => __('lang_v1.profile_updated_successfully')
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                        'msg' => __('messages.something_went_wrong')
                    ];
        }
        return redirect()->route('pos::account.edit')->with('status', $output);
    }
    
    /**
     * Update user password.
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = $cashier_user = Auth::user();
            // $2y$10$b3bC4GwpaXr2Si/.jFHp.O/NTw1mGop/jv8ECybAYCbsxuI/5qamu
            
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->save();
                $output = ['success' => 1,
                            'msg' => __('lang_v1.password_updated_successfully')
                        ];
            } else {
                $output = ['success' => 0,
                            'msg' => __('lang_v1.u_have_entered_wrong_password')
                        ];
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('messages.something_went_wrong')
                        ];
        }
        return redirect()->route('pos::account.edit')->with('status', $output);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashierUser  $cashierUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashierUser $cashierUser)
    {
        //
    }
}
