<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Publication;
use App\Purchase;
use App\Business;
use App\Category;
use App\Product;
use App\Order;
use App\Image;
use App\User;

class AdminController extends Controller
{
    //
    public function listModel($modelName)
    {
      $model = $this->getModel($modelName);
      if(!$model) {
        return back();
      }
      $tabletate = $model::tabletate($model::orderBy('created_at','DESC')->get());
      //
      return view('admin.layouts.tableList')->with([
        'tabletate' => $tabletate,
        'noTypeScript'  => true
      ]);
    }



    public function addModel($modelName)
    {
      $model = $this->getModel($modelName);
      $tabletate = $model::tabletate();
      //
      return view('admin.layouts.tableAdd')->with([
        'tabletate' => $tabletate,
        'model'     => $model
      ]);
    }

    public function editModelExists($model,$id)
    {
      $model = $this->getModel($model)->find($id);

      if($cat = $model::find($id)) {
        $tabletate = $model::tabletate($cat);
        //
        return view('admin.layouts.tableAdd')->with([
          'tabletate' => $tabletate,
          'model'     => $model
        ]);
      }
    }

    public function addDataModel($modelName, Request $request)
    {
      $model = $this->getModel($modelName);
      if($request->id !== false and $request->id !== null and $model::find($request->id))  {
        $model = $model::find($request->id);
      }
      // campos
      foreach ($request->all() as $key => $value) {
        if($key !== '_token') {
          if (Schema::hasColumn($model->getTable(), $key)) {
            // excepciones
            if($key == "price") {
              $value = str_replace(',','.',$value);
            }
            $model[$key] = $value;

          }

          else {
              $model->save();
              // para un multiselect
              // if($key !== "image" and $key !== "images")
              // $a = $model->{$key.'s'}()->sync($value);
              if(strpos($key,"image") === false)
              $a = $model->{$key.'s'}()->sync($value);
          }

        }

      }
      //
        if (Schema::hasColumn($model->getTable(), 'image_id') and $request->has('image')) {
          $image = new Image();
          $image->create($request->image,$modelName);
          $model->image_id = $image->id;
        } else {
          // es multi-image?
          // debemos mirar primero si hay de nuevas
          if ($request->has('images'))
          foreach ($request->images as $newImage) {
            // code...
            // try {
              $image = new Image();
              $image->create($newImage);
              $model->images()->save($image);
            // } catch (\Exception $e) {}
          }
          // ahora actualizar las viejas
          //cogemos las imagenes del modelo

          // les sacamos su id
          if ($modelImages = $model->images)
          foreach ($modelImages as $oldImage) {
            // si hay una imagen que coincida con el nombre, entonces se elimina
            if($request->has('image_'.$oldImage->id)) {
              // creamos la nueva
              $image = new Image();
              $image->create($request->all()['image_'.$oldImage->id]);
              $model->images()->save($image);
              // borramos la antogua
              $oldImage->delete();
            }
          }


        }




      $model->save();
      return redirect("admin/$modelName");
    }

    public function deleteDataModel($modelName,$id)
    {
      $model = $this->getModel($modelName);
      if($model = $model::find($id))  {
        $model->delete();
      } else {
        return "ERROR";
      }
      if($modelName == "image" ) {
        return back();
      }
      return redirect("admin");
    }

    public function dashboard()
    {
      return view('admin.layouts.dashboard')->with([
        'numProducts' => Product::count(),
        'numBusiness' => Business::count(),
        'numUsers'    => User::count(),
        'numOrders'   => Order::count(),
        'numImages'   => Image::count()
      ]);
    }

    //











    public function catsView()
    {
      $categories = Category::with('subCategories')->paginate(50);
      // dd($categories);
      return view('admin.categories')->with("categories",$categories);
    }

    public function getModel($name)
    {
      // code...
      $className = 'App\\'.ucwords($name);

      if(class_exists($className)) {
          $model = new $className;
          return $model;
      }
      return false;
    }

    public function removeCat($id)
    {
      // code...
      if($cat=Category::find($id)) {
        if($cat->image)
        $cat->image->destroyImage();
        $cat->delete();
      }
      return redirect('/admin/categories');

    }

    public function catView($id)
    {
      if($cat = Category::find($id)) {
        $main = Category::where('category_id',null)->where('id','!=',$id)->get();
        return view('admin.category')->with('category',$cat)->with('main',$main);
      }
      return redirect('/admin/categories');
    }

    public function updateCat(Request $request)
    {
      if($category = Category::find($request->id)) {
        $request->category_id?? null;
        $category->fill($request->all());
        if($request->has('image')) {
          if($category->image_id == null) {
            $image = new Image();
            $image->create($request->image,'categories');
            $category->image_id = $image->id;
          } else {
            // dd($category->image);
            $category->image->updateImage($request->image);
          }
        }
        $category->save();
        return redirect("/admin/categories");
      }

    }

    public function newCat(Request $request)
    {
      $new = new Category($request->all());
      if($request->has('image')) {
          $image = new Image();
          $image->create($request->image,'categories');
          $new->image_id = $image->id;

      }
      $new->save();
      return redirect("/admin/categories");

    }

    public function deliver($purchase_id)
    {
      //
      if(!$purchase = Purchase::find($purchase_id)){
        return redirect('');
      }
      //
      $purchase->completed = true;
      $purchase->status = "delivered";
      $purchase->orders()->update([
        "status" => "delivered"
      ]);
      $purchase->save();
      return redirect('/admin/purchase/edit/'.$purchase_id);

    }

    public function purchaseView($purchase_id)
    {
      if(!$purchase = Purchase::find($purchase_id)){
        return redirect('');
      }
      return view('admin.layouts.addPurchase')->with('purchase',$purchase);
    }

    public function publicationView($post_id)
    {
      $model = $this->getModel('publication')->find($post_id);

      if($cat = $model::find($post_id)) {
        $tabletate = $model::tabletate($cat);
        //
        return view('admin.layouts.addPublication')->with([
          'tabletate' => $tabletate,
          'model'     => $model
        ]);
      }
     }

     public function userView($user_id)
     {
       $model = $this->getModel('user')->find($user_id);

       if($cat = $model::find($user_id)) {
         $tabletate = $model::tabletate($cat);
         //
         return view('admin.layouts.addUser')->with([
           'tabletate' => $tabletate,
           'model'     => $model
         ]);
       }
      }

      public function planmView($plan_id)
      {
        $model = $this->getModel('plan')->find($plan_id);

        if($cat = $model::find($plan_id)) {
          $tabletate = $model::tabletate($cat);
          //
          return view('admin.layouts.addPlan')->with([
            'tabletate' => $tabletate,
            'model'     => $model
          ]);
        }
      }


      public function chatView($chat_id)
      {
        $model = $this->getModel('chat')->find($chat_id);

        if($cat = $model::find($chat_id)) {
          $tabletate = $model::tabletate($cat);
          //
          return view('admin.layouts.addChat')->with([
            'tabletate' => $tabletate,
            'model'     => $model
          ]);
        }
      }

      public function validateView($user_id)
      {
        $model = $this->getModel('user')->find($user_id);

        if($cat = $model::find($user_id)) {
          $tabletate = $model::tabletate($cat);
          //
          return view('admin.layouts.validateUser')->with([
            'tabletate'   => $tabletate,
            'model'       => $model,
            'stripe_url'  => $model->createAccountURL()
          ]);
        }
      }

      public function seeUsersValidate()
      {
        $model = new User();
        $tabletate = $model->tabletate($model::where([
          ['verified',false],
          ['wantToBeInfluencer',true]
        ])->orderBy('created_at','DESC')->get());
        $tabletate['name']="Usuarios pendientes de validación";
        //
        return view('admin.layouts.validateList')->with([
          'tabletate' => $tabletate,
          'noTypeScript'  => true
        ]);
      }


}
