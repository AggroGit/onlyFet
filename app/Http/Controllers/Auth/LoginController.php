<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Socialite;
use Str;
use App\User;
use App\Image;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider) {
      if($provider == "twitter")
        return Socialite::driver($provider)->redirect();
      else
        return Socialite::driver($provider)->stateless()->redirect();
    }

    // logea al usuario pero no por el método tradicional,
    public function loginWithToken(User $user)
    {
      // dd($user->creteTokenUser());
      $token = $user->creteTokenUser()['access_token'];
      // dd($token);
      // ponemos el token
      setcookie('token', $token, time() + (86400 * 30), "/");
      //
      return redirect('/');
    }

  public function handleProviderCallback($provider, Request $request)
  {

    // Obtenemos los datos del usuario
    if($provider == "twitter") {
      try {
        $social_user = Socialite::driver('twitter')->user();

      } catch (\Exception $e) {
        return redirect('login/twitter');
      }

      // dd($social_user);

      }
    else {
      $social_user = Socialite::driver($provider)->stateless()->user();
    }
    // dd($social_user); // Sirve para visualizar que llega el callback antes de seguir con el codigo
    // debemos ver si existe usuario o no,
    // si existe ->  login
    // si no existe-> creamos user y login
    if(!$user = User::where('email',$social_user->email)->orWhere('id',$social_user->id)->first() ) {
      // lo creamos
      // creamos el usuario
      $user = User::create([
            'name'      => $social_user->getName(),
            'email'     => $social_user->getEmail(),
            'password'  => Hash::make(Str::random(24)),
            'provider'  => $provider,
            'id'        => $social_user->id?? null
          ]);
      // profile Image
      if($social_user->avatar_original !== null) {
        $image = new Image();
        $image->createFromUrl($social_user->avatar_original,"user");
        $image->save();
        $user->image_id = $image->id;
      }
      $user->save();
      $user->nickname  = str_replace(' ', '',   $user->name.$user->id);
      $user->save();
      // si influencer
      if($request->influencer == true) {
        $user->influencerEmail();
      }

    }
    return $this->loginWithToken($user);

  }

  // Login y redirección
  public function authAndRedirect($user) {
    Auth::login($user);
    return redirect($this->redirectTo);
  }
}
