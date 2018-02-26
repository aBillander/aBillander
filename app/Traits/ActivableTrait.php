<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait ActivableTrait
{
	public function toggleStatus( $obj, Request $request )
    {
    	if ( $request->ajax() )
    	{
			$obj->active = intval(!$obj->active);
			$obj->save();

			return json_encode( array('item_id' => $obj->id, 'active' => $obj->active) );
		} else {
			return false;
		}
    }
}

// See: http://robelluna.com/work/simple-rest-controller-trait-playing-with-laravels-lumen-micro-framework/