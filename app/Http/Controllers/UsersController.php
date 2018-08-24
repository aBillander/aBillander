<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User as User;
use App\Language as Language;
use View;

class UsersController extends Controller {


   protected $user;

   public function __construct(User $user)
   {
        $this->user = $user;
   }

	/**
	 * Display a listing of users
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = $this->user->with('language')->orderBy('id', 'ASC')->get();

		return view('users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new user
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('users.create');
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(!$request->input('home_page')) $request->merge( ['home_page' => '/'] );

		$this->validate($request, User::$rules);
		
		$password = \Hash::make($request->input('password'));
		$request->merge( ['password' => $password] );

		$user = $this->user->create($request->all());

		return redirect('users')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $user->id], 'layouts') . $user->getFullName());
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->edit($id);
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);

		return view('users.edit', compact('user'));
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		if(!$request->input('home_page')) $request->merge( ['home_page' => '/'] );

		$user = User::findOrFail($id);

		if ( $request->input('password') != '' ) {
			$this->validate( $request, User::$rules );

			$password = \Hash::make($request->input('password'));
			$request->merge( ['password' => $password] );
			$user->update($request->all());
		} else {
			$this->validate($request, array_except( User::$rules, array('password')) );
			$user->update($request->except(['password']));
		}

		if ( \Auth::user()->id == $id ) {
			$language = Language::find( $request->input('language_id') );
			\App::setLocale($language->iso_code);
		}

		return redirect('users')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $user->id], 'layouts') . $user->getFullName());
	
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if ( $this->user->find($id)->delete() )
			return redirect('users')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
		else
			return redirect('users')
				->with('error', l('Unable to delete this record &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}

}
