<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a correct response
     *
     * @var Response
     */
    public function correct($response = null)
    {
        return response()->json([
            'rc'           => 1,
            'data'         => $response,

        ]);
    }

    /**
     * Return a incorrect response
     *
     * @var Response
     */
     public function incorrect($code = 0,$response = null)
     {
         $response = $response?? config('errors.'.$code);
         return response()->json([
             'rc'           => $code,
             'data'         => $response,

         ]);
     }

     /**
     *  Convierte un request con los datos en data en un tradicional request con los datos de 'data'
     *
     * @param  mixed
     * @return Request
     */
     public function data_to_request(Request $request)
     {
        if($request->has('data'))
        {
          $request = $request->input('data');
          return new Request(json_decode($request, true));
        } else {
          return $request;
        }
     }

     /**
      * Verifica el request con la config
      *
      * @return Error or Nothing
      */
     public function hasError($request, String $name)
     {
         // validamos el request con el nombre por indice del config
         $validator = Validator::make($request,config($name));

         // si la vañidación falla devolvemos los errores
         if ($validator->fails()){
            // var_dump($validator->errors());
           return $validator->errors();
         }
         // continue...
     }

     /**
      * De el request quita los valores nulos
      *
      * @return Error or Nothing
      */
     public function quitNulls(&$request)
     {
        // $request = $request->all();
        foreach ($request->all() as $key => $value) {
          if($value === null) {
            unset($request[$key]);
          }
        }
        $request->request = $request;
        return $request;
     }

























}
