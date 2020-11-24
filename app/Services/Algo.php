<?php

namespace App\Services;

/**
 *
 */
class Algo
{
  public $name;

  function __construct($n = "sin nombre")
  {
    $this->name = $n;
  }

  public function saludar()
  {
    echo "Hola $this->name, estoy saludando";
  }
}
