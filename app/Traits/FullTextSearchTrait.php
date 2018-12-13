<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait FullTextSearchTrait
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
 
        $words = explode(' ', trim($term));

        $minlength = 3;
        if (0 && count($words)>1)
        	$minlength = 2;
 
        foreach($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if(strlen($word) >= $minlength) {
                $words[$key] = '+' . $word . '*';
            }
        }
 
        $searchTerm = implode( ' ', $words);

        // abi_r($searchTerm);die();
 
        return $searchTerm;
    }
 
    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        $columns = implode(',',$this->searchable);
 
        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $this->fullTextWildcards($term));
 
        return $query;
    }

    /**
	 * Scope a query that matches a full text search of term.
	 * This version calculates and orders by relevance score.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $term
	 * @return \Illuminate\Database\Eloquent\Builder
	 *
	 * Usage: $products = \App\Product::select('*')->searchWhithRelevance($term)->get();
	 */
	public function scopeSearchWhithRelevance($query, $term)
	{
	    $columns = implode(',',$this->searchable);
	 
	    $searchableTerm = $this->fullTextWildcards($term);
	 
	    return $query->selectRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE) AS relevance_score", [$searchableTerm])
	        ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
	        ->orderByDesc('relevance_score');
	}
}
