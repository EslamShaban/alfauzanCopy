<?php

	namespace App\Http\Middleware;

	use Closure;
	use Tymon\JWTAuth\Facades\JWTAuth;

	class JWT
	{
		/**
		 * Handle an incoming request.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Closure $next
		 * @return mixed
		 */
		public function handle( $request, Closure $next )
		{
			//check if there is jwt token then return if it is favourite
			try {
				$tokenFetch = "";
				$tokenFetch = JWTAuth::parseToken()->authenticate();
				if ($tokenFetch) {

					return $next( $request );

				}else{
					$name = $request->header( 'lang' ) == 'en' ? 'unauthorize' : 'غير مصرح';

					return response()->json( ['value' => '0', 'key' => 'fail', 'msg' => $name] );
				}
			}
			catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {//general JWT exception

				$name = $request->header( 'lang' ) == 'en' ? 'unauthorize' : 'غير مصرح';

				return response()->json( ['value' => '0', 'key' => 'fail', 'msg' => $name] );
			}

		}
	}
