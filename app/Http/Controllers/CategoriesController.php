<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Configuration;
use App\Models\Category;
use View;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class CategoriesController extends Controller {


   protected $category;

   public function __construct(Category $category)
   {
        $this->category = $category;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($parentId=0)
    {
        $parent=null;
        if ($parentId>0) $parent=$this->category->find($parentId);

        $categories = $this->category
                        ->with('children')
                        ->where('parent_id', '=', intval($parentId))
                        ->orderBy('position', 'asc')
                        ->orderBy('name', 'asc')
                        ->get();

        return view('categories.index', compact('parentId', 'parent', 'categories'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($parentId=0)
    {
        $parent=null;
        if ($parentId>0) $parent=$this->category->findOrFail($parentId);

        return view('categories.create', compact('parentId', 'parent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($parentId=0, Request $request)
    {
        // Check WebShop Category
        if ( Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') && $request->has('webshop_id') )
        {
            // REST API Connect to shop & check for $request->input('webshop_id') existance
            try {

                $woo_category = WooCommerce::get('products/categories/' . $request->has('webshop_id')); // Array
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

                return redirect()->back()
                    ->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

            }

            if (empty($woo_category))
                $request->merge(['webshop_id' => null]);
        }


        // Check position
        if ( (int) $request->input('position') == 0 )
        {
            $max_position = (int) Category::where('parent_id', $parentId)->max('position');

            $request->merge( ['position' => $max_position + 10] );
        }

        $this->validate($request, Category::$rules['main_data']);

        $category = $this->category->create($request->all());

        if ($parentId>0) {
            $parent=$this->category->findOrFail($parentId);
            $parent->children()->save($category);

            return redirect('categories/'.$parentId.'/subcategories')
                    ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $category->id], 'layouts') . $request->input('name'));
        }

        return redirect()->back()
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $category->id], 'layouts') . $request->input('name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($parentId=0, $id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($parentId=0, $id)
    {
        $category = $this->category->findOrFail($id);
        if ($parentId<0) $parentId=$category->parent_id;
        
        $parent=null;
        if ($parentId>0) $parent=$this->category->findOrFail($parentId);

        return view('categories.edit', compact('parentId', 'parent', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($parentId=0, $id, Request $request)
    {
        // Check WebShop Category
        if ( Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') && $request->has('webshop_id') )
        {
            // REST API Connect to shop & check for $request->input('webshop_id') existance
            try {

                $woo_category = WooCommerce::get('products/categories/' . $request->has('webshop_id')); // Array
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

                return redirect()->back()
                    ->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

            }

            if (empty($woo_category))
                $request->merge(['webshop_id' => null]);
        }


        $section =  $request->input('tab_name')     ? 
                    '#'.$request->input('tab_name') :
                    '';

        $category = $this->category->findOrFail($id);

        $this->validate($request, Category::$rules[ $request->input('tab_name') ]);

        $category->update($request->all());

        if ($parentId>0) {

            return redirect('categories/'.$parentId.'/subcategories')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $category->id], 'layouts') . $request->input('name'));
        }

        /*
        return redirect(route('categories.edit', $id) . $section)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $category->name);
        */
        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $category->id], 'layouts') . $category->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($parentId=0, $id)
    {
        $category = $this->category->findOrFail($id);

        try {

            $category->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }

        if ($parentId>0) {

            return redirect('categories/'.$parentId.'/subcategories')
                    ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
        }

        return redirect('categories')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    // https://github.com/marcovaccarini/laravel5-category-tree-view
    public function manageCategory()
    {

        $categories = Category::where('parent_id', '=', 0)->get();

        $allCategories = Category::pluck('name','id')->all();

        return view('categories.categoryTreeview',compact('categories','allCategories'));

    }

    // Better: https://github.com/kun391/laravel-categories
    // https://github.com/lazychaser/laravel-nestedset
    // https://github.com/redooor


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                Category::where('id', '=', $position[0])->update(['position' => $position[1]]);
            }
        });

        return response()->json($positions);
    }




/*
    /**
     * Update/Publish to web the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     * /
    public function publish($id, Request $request)
    {
        $section =  $request->input('tab_name')     ? 
                    '#'.$request->input('tab_name') :
                    '';

        $category = $this->category->findOrFail($id);

        // Do the Mambo!!


// Here we define constants /!\ You need to replace this parameters
define('DEBUG', false);
define('PS_SHOP_PATH', 'http://localhost/ps16014/');
define('PS_WS_AUTH_KEY', 'HPIIUGHGGKUCF89MYTDSZP587XFPQMNV');
// require_once('./PSWebServiceLibrary.php');

$webService = new \App\Models\PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
$xml = $webService -> get(array('url' => PS_SHOP_PATH . '/api/categories?schema=blank'));

$resources = $xml -> children() -> children();

// http://stackoverflow.com/questions/19954675/add-new-category-in-prestashop-via-web-services-use-c-sharp
// https://www.prestashop.com/forums/topic/194797-solved-add-new-category-via-webservice/

$n_name = $request->input('name');
$n_desc = $request->input('description');
$n_link_rewrite = $request->input('link_rewrite');    // PrestaShop $friendly_url = Tools::link_rewrite($seccion['DESSEC']);
$n_meta_title = $request->input('meta_title');
$n_meta_description = $request->input('meta_description');
$n_meta_keywords = $request->input('meta_keywords');    // Separated by ","
$n_active = $request->input('active');

$n_id_parent = '2';     // Some Root Category
$n_l_id = '1';          // Language Spanish
$n_is_root_category = '0'; // Regular category


unset($resources -> id);
unset($resources -> position);
unset($resources -> id_shop_default);
unset($resources -> date_add);
unset($resources -> date_upd);
unset($resources -> associations);

$resources -> active = $n_active;
$resources -> id_parent = $n_id_parent;
$resources -> id_parent['xlink:href'] = PS_SHOP_PATH . '/api/categories/' . $n_id_parent;
$resources -> is_root_category = $n_is_root_category;

$node = dom_import_simplexml($resources -> name -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_name));
$resources -> name -> language[0][0] = $n_name;
$resources -> name -> language[0][0]['id'] = $n_l_id;
$resources -> name -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> description -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_desc));
$resources -> description -> language[0][0] = $n_desc;
$resources -> description -> language[0][0]['id'] = $n_l_id;
$resources -> description -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> link_rewrite -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_link_rewrite));
$resources -> link_rewrite -> language[0][0] = $n_link_rewrite;
$resources -> link_rewrite -> language[0][0]['id'] = $n_l_id;
$resources -> link_rewrite -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> meta_title -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_title));
$resources -> meta_title -> language[0][0] = $n_meta_title;
$resources -> meta_title -> language[0][0]['id'] = $n_l_id;
$resources -> meta_title -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> meta_description -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_description));
$resources -> meta_description -> language[0][0] = $n_meta_description;
$resources -> meta_description -> language[0][0]['id'] = $n_l_id;
$resources -> meta_description -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> meta_keywords -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_keywords));
$resources -> meta_keywords -> language[0][0] = $n_meta_keywords;
$resources -> meta_keywords -> language[0][0]['id'] = $n_l_id;
$resources -> meta_keywords -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

try {
    $opt = array('resource' => 'categories');
    $opt['postXml'] = $xml -> asXML();
    $xml = $webService -> add($opt);
    $result = $xml->children()->children();
    $web_id = $result->{'id'};

    $category->webshop_id = $web_id;
    $category->save();

       return redirect(route('categories.edit', $id) . $section)
                ->with('success', l('This record has been successfully published &#58&#58 (:id) :name as id=:web_id', ['id' => $id, 'name' => $category->name, 'web_id'=> $web_id], 'layouts') );
    
    // echo "Successfully added $web_id .";
} catch (PrestaShopWebserviceException $ex) {
    // czarodziej_log("PS/SYNCHRONIZACJA KATEGORII: " . $e -> getMessage(), 1);
    // my log function
    // echo "<br>LOG: ".$e->getMessage();

    // Here we are dealing with errors
    $trace = $ex->getTrace();
    if ($trace[0]['args'][0] == 404) $msg = '404 - Bad ID';
    else if ($trace[0]['args'][0] == 401) $msg = '401 - Bad auth key';
    else $msg = 'Other error:<br />'.$ex->getMessage();

       return redirect(route('categories.edit', $id) . $section)
                ->with('error', $msg );
}



     
    }
*/

}