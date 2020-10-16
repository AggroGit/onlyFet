<?php

namespace App\Http\Middleware;

use Closure;
use App\Publication;

class existPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->post_id) {
          if($post = Publication::find($request->post_id)) {
            $request->post = $post;
            return $next($request);
          } else {
            return app()->call('App\Http\Controllers\ChatsController@incorrect',
                                ['code'=> 1500]);
          }
        }
        return $next($request);

    }
}
