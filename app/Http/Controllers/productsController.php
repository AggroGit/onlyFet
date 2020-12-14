<?php

namespace App\Http\Controllers;

use App\Services\Products\ProductServiceProvider;
use App\Services\Shopping\ShoppingServiceProvider;

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

    public function getProduct(Int $product_id)
    {
      try {
        $this->provider = new ProductServiceProvider($product_id);
        //
        return $this->correct($this->provider->getProduct());

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }

    }

    public function addToCart(Int $product_id)
    {
      try {
        $this->provider = new ShoppingServiceProvider();
        $product = $this->provider->getProduct($product_id);
        //
        return $this->correct($this->provider->addOrderToCart($product));

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
    }

    public function getCart()
    {
      try {
        $provider = new ShoppingServiceProvider();
        //
        return $this->correct($provider->getShoppingCart());

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
    }

    public function buy()
    {
      try {
        $provider = new ShoppingServiceProvider();
        //
        return $this->correct($provider->buyShippingCart());

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
    }

    public function history()
    {
      try {
        $provider = new ShoppingServiceProvider();
        //
        return $this->correct($provider->getHistory());

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
    }

    public function removeFromCart(Request $request)
    {
      try {
        $provider = new ShoppingServiceProvider();
        //
        return $this->correct($provider->quitOrdersToCart($request->idsToRemove));

      } catch (\Exception $e) {
        return $this->incorrect($e->getCode());
      }
    }
}
