<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;
use App\Category;
use App\Product;

// php artisan make:controller CategoryProductsController --resource

class CategoryProductsController extends Controller
{

   protected $category;
   protected $product;

   public function __construct(Category $category, Product $product)
   {
        $this->category = $category;
        $this->product  = $product;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // http://laravel-school.com/posts/laravel-php-artisan-route-list-command-9
        // php artisan route:list --name=categories
        // GET categories/{category}/subcategories/{subcategory}/edit | categories.subcategories.edit    | App\Http\Controllers\CategoriesController@edit

        $category = $this->category
                        ->with('parent')
                        ->with('products')
                        ->findOrFail($id);
        
        $parent  = $category->parent;
        $parentId = $category->parent_id;
        $products = $category->products;

        return view('category_products.index', compact('parentId', 'parent', 'category', 'products'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function sortProducts(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                Product::where('id', '=', $position[0])->update(['position' => $position[1]]);
            }
        });

        return response()->json($positions);
    }
}
