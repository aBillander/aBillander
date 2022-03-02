<?php 

namespace App\Traits;

use ReflectionClass;

use App\Sequence;

trait HasSquenceTrait
{
    public function xgetClassName()
    {
        static $classname_full;

        return $classname_full ?: $classname_full = ( new ReflectionClass($this) )->getName();
    }

	public function sequenceList()
    {
    	return Sequence::listFor( $this->getClassName() );
    }
}
