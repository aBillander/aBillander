<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller {


   protected $todo;

   public function __construct(Todo $todo)
   {
        $this->todo = $todo;
   }
    /**
     * Display a listing of the resource.
	 * GET /todos
     *
     * @return Response
     */

    public function index()
    {
        $todos = $this->todo
//		        ->where("completed", '0')
		        ->orderBy('id', 'desc');
        // $completed_tasks = Task::where("iscompleted", true)->get();

        $todos = $todos->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $todos->setPath('todos');

        return view('todos.index', compact('todos'));
        
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /todos/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('todos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /todos
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );

		$this->validate($request, Todo::$rules);

		$todo = $this->todo->create($request->all());

		return redirect('todos')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $todo->id], 'layouts') . $request->input('name'));
    }

	/**
	 * Display the specified resource.
	 * GET /todos/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Todo $todo)
	{
		return $this->edit($todo);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /todos/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Todo $todo)
	{
		// $todo = Todo::findOrFail($id);

        $todo->due_date = abi_date_form_short($todo->due_date);

		return view('todos.edit', compact('todo'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /todos/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, Todo $todo)
	{
        $id = $todo->id;

		// $todo = Todo::findOrFail($id);

        $request->merge( ['due_date' => abi_form_date_short( $request->input('due_date') )] );

		$this->validate($request, Todo::$rules);

		$todo->update($request->all());

		return redirect('todos')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /todos/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Todo $todo)
	{
        $id = $todo->id;

        $todo->delete();

        return redirect('todos')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }




	public function complete(Todo $todo)
	{
		$todo->completed = 1;
		$todo->save();

		// Maybe Ajax instead...
		return redirect('todos')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $todo->id], 'layouts') . $todo->name);
	}

}