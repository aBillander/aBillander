<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionSheetRegistry
{
  protected $gateways = [];

  function register ($name, Model $instance) {
    $this->gateways[$name] = $instance;
    return $this;
  }

  function get($name) {
    if (array_key_exists($name, $this->gateways)) {
      return $this->gateways[$name];
    } else {
      throw new \Exception("Invalid gateway");
    }
  }
	
}