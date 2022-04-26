<?php
 
namespace App\Scopes;
 
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
 
class ShowOnlyActiveScope implements Scope
{
    protected $resolver;

    public function __construct ( $resolver )		// (ManagerResolver $resolver)
    {
        // $resolver maybe a Class...
        $this->resolver = $resolver; 
    }

    public function apply(Builder $builder, Model $model)
    {
        if ( $this->resolver )
        // if ( \App\Models\Configuration::isTrue('SHOW_PRODUCTS_ACTIVE_ONLY') )
        	$builder->where('active', '>', 0);
    }
}
 