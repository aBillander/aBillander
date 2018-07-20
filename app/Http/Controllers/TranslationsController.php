<?php namespace App\Http\Controllers;

function extractor($a, $b = NULL, $c = NULL){

/*
echo '<pre>';
print_r($a);
echo '</pre>';

echo '<pre>';
print_r($b);
echo '</pre>';

echo '<pre>';
print_r($c);
echo '</pre>';
*/

return [$a, $b, $c];
}

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request as Request;

use App\Context as Context;
use App\Language as Language;

class TranslationsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// 
		$lang = 'es';
		$language = Context::getContext()->language;
		$lang = $language->iso_code;

		// Retrieve folders within wiews from: realpath(base_path('resources/views'))
		$views        = Directory::listDirectories( realpath(base_path('resources/views')) );
		$translations = Directory::listFiles( realpath(base_path('resources/lang/'.$lang)), 'ASSOC' );

        return view('translations.index', compact('views', 'translations', 'language'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$lang = 'es';
		$language = Context::getContext()->language;
		$lang = $language->iso_code;
		
		// Translation file?
		$file = realpath(base_path('resources/lang')).'/'.$lang.'/'.str_replace('_', '', $id).'.php';
		if ( !file_exists( $file ) ){
			$content = '<?php

						return [];
						';
			$fhandle = fopen($file,"w");
			fwrite($fhandle,$content);
			fclose($fhandle);
		}	
		$t_keys = include ($file);

		// Get keys from view files
		$v_keys = [];
		$viewfiles = Directory::listFiles( realpath(base_path('resources/views/'.$id)) );

		foreach ($viewfiles as $vfile) {
			// 
			$fileContents=file_get_contents( realpath(base_path('resources/views/'.$id.'/'.$vfile)) );
			// preg_match_all("/[^A-Za-z0-9_]+l\((.*?)\)/", $fileContents, $matches);
			$nbr = preg_match_all("/[^A-Za-z0-9_]+l\((.*?)(\]|')\)/", $fileContents, $matches);
			// preg_match_all("/[^A-Za-z0-9_]+l\(\'(.*?)\'(.*?)\)/", $fileContents, $matches);

			// abi_r($matches[1]);

			if ( intval($nbr) > 0 ) {
			foreach ($matches[1] as $k){

				if ($k) {
					// Gorrino style rules!!
					$f = strrpos($k, '[');
					$l = strrpos($k, ']');

					if (!$l && $f) {
						$k .= "]";
						$l = strrpos($k, ']');
					}
					else
						$k .= "'";

					$s='';
					if ( $l && $f && (($l-$f)>1) ) {
						$s = substr ( $k , $f, $l-$f+1 );
						$k=str_replace($s, '[17]', $k);
					}


					// 
					// echo_r( $vfile.' - '."\$values = App\\Http\\Controllers\\extractor(".$k.");".' - '.$s.'' );

					/* */

$exp = "\$values = App\\Http\\Controllers\\extractor( $k );";

 // abi_r( $exp );
 // $values = ['culo',[],''];

try {
  
  	eval( $exp );

}

//catch exception
catch(ParseError $e) {

  	echo 'Caught exception: '.$e->getMessage()."\n";

  	abi_r( $exp, true );

}
					

					


					if ( isset($v_keys[$values[0]]) ) 
						$v_keys[$values[0]]['infile'] .= ', '.$vfile;
					else {
						$forcefile = $values[2];

						if ( is_string($values[1]) && !empty($values[1]) ) $forcefile = $values[1];

						$v_keys[$values[0]] = ['infile' => $vfile, 'forcefile' => $forcefile];
					}
					/* */
				}
			}
			}

			// abi_r($vfile.' => '.$nbr);
			// abi_r($v_keys);
		}

		// Merge translations
		foreach ($t_keys as $k => $v) {
			if ( isset($v_keys[$k]) ) {
				$v_keys[$k]['local'] = $v;
			} else {
				$v_keys[$k] = ['infile' => '', 'forcefile' => '', 'local' => $v];
			}
		}


/*
foreach ($t_keys as $k => $v) {
	if ( isset($v_keys[$k]) ) {
		unset($t_keys[$k]);
		unset($v_keys[$k]);
	}
}

echo '<pre>';
print_r($t_keys);
echo '</pre>';
* /
echo '<pre>';
print_r($v_keys);
echo '</pre>';
/ * */

// die();

//		echo_r($viewfiles);die();
		return view('translations.edit', compact('id', 'v_keys', 'language'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$lang = 'es';
		$language = Context::getContext()->language;
		$lang = $language->iso_code;

		$input = $request->all();
		unset($input['_method']);
		unset($input['_token']);

		// Rebuild translation
		foreach ($input['key'] as $k => $v){
			$t_keys[$v] = $input['val'][$k];
		}

		// Compare
		$msg = '';
		$file = realpath(base_path('resources/lang')).'/'.$lang.'/'.str_replace('_', '', $id).'.php';
		$t_old = include ($file);

		foreach ($t_keys as $k => $v){
			if ( !isset($t_old[$k]) ) $msg .= 'Clave sólo en views: '.$k .' - '. $v.'<br />';
		}

		foreach ($t_old as $k => $v){
			if ( !isset($t_keys[$k]) ) $msg .= 'Clave sólo en fichero: '.$k .' - '. $v.'<br />';
		}
/*
echo_r($t_old['Include a space between symbol and price (e.g. $1,240.15 -&gt; $ 1,240.15)']);
echo_r('Include a space between symbol and price (e.g. $1,240.15 -&gt; $ 1,240.15)');
// echo_r($t_keys['Include a space between symbol and price (e.g. $1,240.15 -&gt; $ 1,240.15)']);

		echo_r($t_old); echo '-------------------';
		echo_r($t_keys); 
*/
		$theContent = 
'<?php

return [

	/*
	|--------------------------------------------------------------------------
	| '.ucwords(str_replace('_', ' ', $id)).' Language Lines :: '.Language::where('iso_code', '=', $lang)->first()->name .'
	|--------------------------------------------------------------------------
	|
	*/

';

		foreach($t_keys as $k => $v){
			if ( $v || 1) $theContent .= 
"	'$k' => '$v',"."\n";
		}

		$theContent .= '

];
';


		file_put_contents($file, $theContent);

		return redirect('translations')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'). '<br />' . $msg);

		/*
		return redirect('currencies')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
		*/
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}


class Directory
{
    /**
     * Provide a list of directory contents minus the top directory.
     *
     * @param  string $path
     * @return array
     */
    public static function listContents($path)
    {
        return array_diff(scandir($path), ['.', '..']);
    }

    /**
     * Provide an associative array of directory contents ['dir' => 'dir'].
     *
     * @param  string $path
     * @return array
     */
    public static function listAssocContents($path)
    {
        $files = self::listContents($path);

        return array_combine($files, $files);
    }

    /**
     * Provide a list of only directories.
     *
     * @param  string $path
     * @return array
     */
    public static function listDirectories($path, $assoc = NULL)
    {
        $directories = self::listContents($path);

        foreach ($directories as $key => $directory)
        {
            if (!is_dir($path . '/' . $directory))
            {
                unset($directories[$key]);
            }
        }

        if ($assoc == 'ASSOC') return array_combine($directories, $directories);

        return $directories;
    }

    /**
     * Provide a list of only files.
     *
     * @param  string $path
     * @return array
     */
    public static function listFiles($path, $assoc = NULL)
    {
        $directories = self::listContents($path);

        foreach ($directories as $key => $directory)
        {
            if (is_dir($path . '/' . $directory))
            {
                unset($directories[$key]);
            }
        }

        if ($assoc == 'ASSOC') return array_combine($directories, $directories);

        return $directories;
    }
}
