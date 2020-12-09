<?php

namespace App\Http\Controllers;

use App\Services\Products\ProductServiceProvider;
use Illuminate\Http\Request;

class productsController extends Controller
{
    public function __construct(Request $request)
    {
      $this->provider = new ProductServiceProvider();
    }
    // obtiene los productos
    public function getProducts(Request $request)
    {
      //
      if($missings = $this->hasError($request->all(),'validation.searchProducts')) {
        return $this->incorrect(0,$missings);
      }
      return $this->correct($this->provider->filterProducts($request));

    }

    public function getProduct($product_id)
    {
      try {
        $this->provider = new ProductServiceProvider($product_id);
        //
        return $this->correct($this->provider->getProduct());

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }

    }
}
