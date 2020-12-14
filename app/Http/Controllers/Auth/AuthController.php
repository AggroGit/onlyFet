<?php

namespace App\Http\Controllers\Auth;

use App\Services\Suscriptions\SuscriptionServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Algo;
use App\Notification;
use Carbon\Carbon;
use App\Message;
use App\Image;
use App\Token;
use App\User;


class AuthController extends Controller
{


    public function __construct()
    {
      Carbon::setLocale(auth()->user()->lang?? 'es');
        // $this->middleware('auth');
    }

    // return the logged user
    public function currentUser(Request $request)
    {

      return auth()->user()?
      $this->correct(User::with('plans')->find(auth()->user()->id)) : $this->incorrect(13);
    }

    /**
     * make the login via API
     *
     * @return Response
     */
    public function login(Request $request)
    {
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.login')) {
        return $this->incorrect(0,$missings);
      }
      // ya verificados vemos si existe el user, en caso que si autenticamos,
      // sino error
      if (!Auth::attempt([
        "email"   => $request->email,
        "password"=> $request->password
      ])) {
        return $this->incorrect(3);
      }
      // devolvemos el usuario autenticado
      return $this->correct(auth()->user()->creteTokenUser());

    }

    /**
     * make the register via API
     *
     * @return Response
     */
    public function register(Request $request)
    {
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.register')) {
        if ($u = User::where('email',$request->email)->first()) {
           return $this->incorrect(2);
        }
        return $this->incorrect(0,$missings);
      }
      // creamos el usuario
      $new = new User($request->all());
      $new->password = bcrypt($request->password);
      $new->save();
      $new->nickname = $request->name.$new->id;
      $new->save();
      if($request->influencer == true) {
        $new->influencerEmail();
      }
      // devolvemos logeado
      return $this->login($request);
    }


    public function loginRRSS($social, Request $request)
    {
      // if errors
      if ($missings = $this->hasError($request->all(),'validation.login_rrss')) {
        return $this->incorrect(0,$missings);
      }
      // si hay un usuario con el mismo token y red social
      if($user = User::where([
        ['social_token',  $request->social_token],
        ['social_name',   $social],
      ])->first()) {
        Auth::login($user);
        return $this->correct($user->creteTokenUser());
      }
      return $this->incorrect(3);

    }


    public function registerRRSS($social, Request $request)
    {
      // if errors
      if ($missings = $this->hasError($request->all(),'validation.register_rrss')) {
        return $this->incorrect(0,$missings);
      }
      // si hay un usuario con el mismo token y red social se da error
      if($user = User::where([
        ['email',  $request->social_user_email],
      ])->first()) {
        return $this->incorrect(2);
      }
      // sino creamos
      $new = new User([
        'email'         => $request->social_user_email,
        'name'          => $request->social_user_name,
        'social_token'  => $request->social_token,
        'social_name'   => $social
      ]);
      // case of emergency put a password
      $new->password = bcrypt("$social $request->social_token");
      $new->save();
      Auth::login($new);
      //
      return $this->correct($new->creteTokenUser());
    }

    public function logout(Request $request)
    {


      if ($request->is('api/*'))  {
        return $this->logoutApi();
      } else {
        if(auth()->user()) {
          Auth::logout();
        }
      }
      return redirect('/');


    }

    // vista para resetear la contraseña
    public function forgetView(Request $request)
    {
      // get the user by token
      if($request->has('token') and $user = User::where('remember_token',$request->token)->first()) {
        $user->remember_token = md5(uniqid(rand(), true));
        $user->save();
        return view('authh.reset')->with('token',$user->remember_token);
      }
      return redirect('/');
    }

    public function changePass(Request $request)
    {
      // if ($missings = $this->hasError($request->all(),'validation.changePass')) {
      //   return redirect('/');
      // }
      if($request->has('token') and $user = User::where('remember_token',$request->token)->first()) {
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect('/')->with('correct','Se ha cambiado correctamente su contraseña');
      }
      return redirect('/');
    }

    public function requestChangePassword(Request $request)
    {
      // envia un correo que solicita cambiar la contraseña
      if ($missings = $this->hasError($request->all(),'validation.resetPass')) {
        return $this->incorrect(0,$missings);
      }
      //
      if($user = User::where('email',$request->email)->first()) {
        $user->forgetPass();
      }
      return $this->correct();
    }

    // read all notifications
    public function allNotifications()
    {
      $notifications = auth()->user()->allNotifications();
      $notis = $notifications->get();
      foreach ($notis as $noty) {
        $noty->read = true;
        $noty->save();
      }
      return $this->correct($notis);
    }

    public function logoutApi()
    {

        // obtenemos el usuario
        $user = auth()->user();
        //buscamos su token, como al pasar por el middleware justo lo habremos,
        // actualizado, revocaremos el último actualizado
        $tokens = Token::where('user_id', $user->id)
                ->where('revoked',false)
                ->orderBy('updated_at','desc');
        // desactivamos el token
        $tokens->update([
          'revoked' => true
        ]);
        // cerramos sesión
        //
        return $this->correct();

    }

    // edit current user
    public function editCurrent(Request $request)
    {
      if($request->has('email')){
        if(User::where('email',$request->email)->first()) {
          return $this->incorrect(2);
        }
      }
      if($request->has('nickname')){
        if(User::where('nickname',$request->nickname)->first()) {
          return $this->incorrect(6);
        }
      }
      // envia un correo que solicita cambiar la contraseña
      if ($missings = $this->hasError($request->all(),'validation.editAuth')) {
        return $this->incorrect(0,$missings);
      }
      if($request->has('image')) {
        if(auth()->user()->image !== null) {
          auth()->user()->image->updateImage($request->image);
        } else {
          $new = new Image();
          $new->save();
          $new->create($request->image,"user");
          auth()->user()->image_id = $new->id;
        }
        auth()->user()->save();
        return $this->correct(User::find(auth()->user()->id));
      }
      $this->quitNulls($request);
      // fill
      auth()->user()->fill($request->all());
      // encrypt pass
      if($request->has('password')) {
        auth()->user()->password = bcrypt($request->password);
      }
      // image
      auth()->user()->save();
      //
      return $this->correct(User::find(auth()->user()->id));

    }

    public function notifications()
    {
      $notifications = auth()->user()->allNotifications();//->where('type','chat');
      $notis = $notifications->get();
      // $notifications->delete();
      auth()->user()->allNotifications()->where('type','chat')->delete();
      return $this->correct($notis);
    }

    public function unsuscribe()
    {
      // code...
      auth()->user()->want_emails = true;
      auth()->user()->save();
      return redirect('/success');
    }

    public function classicLogin()
    {
      return view('auth/login');
    }



}
