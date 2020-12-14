<?php

namespace App\Services\Products;

use App\Services\Products\ProductDomain;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Business;
use App\Product;
use App\Image;
use App\User;





class ProductServiceProvider extends ProductDomain
{

  public function getProduct()
  {
    return $this->getProductAttribute();
  }

  public function filterProducts(Request $request)
  {
    return [
      "products"  => $this->filter($request)->get(),
      "business"  => $this->getBusiness(),
      "categories" => $this->getCategories()->get()
    ];

  }

  public function createProduct(Request $request)
  {
    $this->addProduct($request);
    return $this->product;
  }









}
