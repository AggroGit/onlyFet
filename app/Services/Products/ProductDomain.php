<?php

namespace App\Services\Products;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Product;
use App\Image;
use App\User;


use Exception;



class ProductDomain
{
  private $product;

  public function __construct(Int $product_id=null)
  {

    // producto doesnt exist
    if($product_id !==null and !$product = Product::find($product_id)) {
      throw new Exception('No existe el producto',900);
    }
    $this->product = $product?? null;
  }

  public function filter(Request $request)
  {
    $products = $this->getProducts();
    // mis suscripciones
    if($request->orderBy == "expensiveFirst") {
      $products = $products->orderBy('price','DESC');
    }
    // populares
    if($request->orderBy == "cheapestFirst") {
      $products = $products->orderBy('price','ASC');
    }
    if($request->has('categories') and count($request->categories)>0) {
      $products = $products->whereIn('category_id',$request->categories);
    }
    // texto
    if($request->has('search') and $request->search !== null) {
      $products = $products->where('name','like',"%$request->search%")->orWhere('description','like',"%$request->search%");
    }
    return $products;
  }

  public function getProducts()
  {
    return Product::where('hidden',false)->orderBy('order','ASC')->orderBy('created_at','DESC');
  }

  public function addProduct(Request $request)
  {
    $product = Product::create($request->all());
    $product->refresh();
    //
    $this->product = $product;
  }

  public function getProductAttribute()
  {
    return $this->product;
  }








}
