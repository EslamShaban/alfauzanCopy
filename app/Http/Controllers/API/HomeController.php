<?php

	namespace App\Http\Controllers\API;

	use App\Http\Controllers\Controller;
	use App\Models\Ads;
	use App\Models\Category;
	use App\Models\Branch;
	use App\Models\Banner;
	use App\Models\Order;
	use App\Models\Reason;
	use App\Models\City;
	use App\User;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\App;
	use Tymon\JWTAuth\Facades\JWTAuth;
	use Validator;

	class HomeController extends Controller
	{
		public function __construct( Request $request )
		{
			/** Set Lang **/
			$request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
			/** Set Carbon Language **/
			$request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

		}

		/******************* order reasons  *******************/

		public function orderReason( Request $request )
		{

			//get order types
			$name = 'name_' . App ::getLocale();

			$reasons = Reason ::select( 'id', $name . ' as name' )->whereNotIn('id',[4,14]) -> get();

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $reasons ] );
		}

		/******************* add order  *******************/

		public function addOrder( Request $request )
		{


			// check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

			//make validation
			$validator = Validator ::make( $request -> all(), [

				'phone'     => 'required',
				'name'      => 'required',
				'ads_id'    => 'required',
				'reason_id' => 'required',
			] );

			if ( $validator -> passes() ) {

				//check if name is correct
				if ( substr( $request -> name, 0, 17 ) === "android.support.v" )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.wrong_data' ) ] );

				//check if phone is correct
				if ( substr( $request -> phone, 0, 17 ) === "android.support.v" )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.wrong_data' ) ] );

				//convert arabic numbers
				$phone = convert2english( request( 'phone' ) );
				if ( !valid_phone( $phone ) )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.wrong_phone' ) ] );

				// check if phone exist
				$unique = User ::where( 'phone', $phone ) -> first();
				if ( $unique && $unique -> id != $user -> id ) {
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.phone_exist' ) ] );
				}

				//check if reason exist
				$reason = Reason ::find( $request -> reason_id );
				if ( !$reason )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.no_reason' ) ] );

				//check if ads exist
				$ads = Ads ::find( $request -> ads_id );
				if ( !$ads )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_ads' ) ] );

				//insert user order
				$order              = new Order();
				$order -> name      = $request -> name;
				$order -> phone     = $phone;
				$order -> user_id   = $user -> id;
				$order -> ads_id    = $request -> ads_id;
				$order -> reason_id = $request -> reason_id;
				$order -> save();

				return response() -> json( [ 'value' => '1', 'key' => 'success' ] );
			}//if validation fail
			else {


				foreach ( (array)$validator -> errors() as $error ) {
					if ( isset( $error[ 'phone' ] ) ) {
						$msg = trans( 'api.phone_req' );
					} elseif ( isset( $error[ 'name' ] ) ) {
						$msg = trans( 'api.username_req' );
					} elseif ( isset( $error[ 'ads_id' ] ) ) {
						$msg = trans( 'api.ads_req' );
					} elseif ( isset( $error[ 'reason_id' ] ) ) {
						$msg = trans( 'api.reason_req' );
					} else {
						$msg = trans( 'api.error' );
					}

					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
				}
			}
		}

		/************ all branches ***********/
		public function branches()
		{

			//get order types
			$name = 'name_' . App ::getLocale();


			$branches = Branch ::select( 'id', $name . ' as name', 'location', 'lat', 'lng', 'start_at', 'end_at' )
			                   -> latest() -> get();
			foreach ( $branches as $branch ) {
				$branch[ 'start_day' ] = App ::getLocale() == 'ar' ? 'السبت' : 'Saturday';
				$branch[ 'end_day' ]   = App ::getLocale() == 'ar' ? 'السبت' : 'Thursday';
			}
			//return $categories;
			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $branches ] );

		}

        /************ all banners ***********/
        public function banners()
        {



            $banners = Banner :: latest() -> get();
            $data = [];
            foreach ( $banners as $banner ) {
                $data[]=[
                    'image' => url('dashboard/uploads/banners/'.$banner->image),
                    'url'    => $banner->url
                ];
            }
            //return $categories;
            return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

				}
				

				public function homescreen()
        {
						$data = [];

						$categories = Category ::latest() -> get();
						$data['categories']=$categories;

				
						
						// banners
						$banners = Banner ::latest() -> get();
						foreach ( $banners as $banner ) {
							$banar_data[]=[
									'image' => url('dashboard/uploads/banners/'.$banner->image),
									'url'    => $banner->url
							];
					}

					$data['banners'] = $banar_data;

					
            // the latest ad   
						$ads = Ads ::latest()-> first();
						$image_name = $ads->image->first()->image ?? '';
						$data_ads = $ads->toArray();
						$data_ads['image'] = $image_name == '' ? '' : url('dashboard/uploads/adss/'. $image_name);
						$data['ads'] = $data_ads;

						return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

		}
		
		public function cities()
		{
			$cities = City::all();

			$data = [];
			$data['cities'] = $cities;

			if(count($cities) == 0){
				return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => 'api.no_cities_founded' ] );

			}

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

		}
	}
