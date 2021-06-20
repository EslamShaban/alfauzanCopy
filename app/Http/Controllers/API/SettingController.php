<?php

	namespace App\Http\Controllers\API;

	use App\Http\Controllers\Controller;
	use App\Models\AdsCategory;
	use App\Models\Category;
	use App\Models\Contact;
	use App\Models\Job;
	use App\Models\Project;
	use App\Models\Repair;
	use App\Models\RepairType;
	use App\Models\SiteSetting;
	use App\Models\Social;
	use App\User;
	use Auth;
	use Carbon\Carbon;
	use File;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\App;
	use Tymon\JWTAuth\Facades\JWTAuth;
	use Validator;

	class SettingController extends Controller
	{
		public function __construct( Request $request )
		{
			/** Set Lang **/
			$request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
			/** Set Carbon Language **/
			$request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

		}

		/******************* Get main categories *******************/

		public function mainCategories( Request $request )
		{

			$name = 'name_' . App ::getLocale();

			//get all main  categories
			$categories = Category ::select( 'id', $name . ' as name' )
			                       -> where( 'offer', 0 )
			                       -> get();

			$data = [];

			//insert الكل
			$c_name      = $request -> header( 'lang' ) == 'en' ? 'all' : 'الكل';
			$c           = collect();
			$c[ 'id' ]   = 0;
			$c[ 'name' ] = $c_name;
			$data[]      = $c;

			//insert other categories
			foreach ( $categories as $cat ) {
				$c           = collect();
				$c[ 'id' ]   = $cat -> id;
				$c[ 'name' ] = $cat -> name;
				$data[]      = $c;
			}

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );
		}

		/******************* Get offer categories *******************/

		public function offerCategories( Request $request )
		{

			$name = 'name_' . App ::getLocale();

			//get all offer  categories
			$categories = Category ::select( 'id', $name . ' as name' )
			                       -> where( 'offer', 1 )
			                       -> get();

			$data = [];

			//insert الكل
			$c_name      = $request -> header( 'lang' ) == 'en' ? 'all' : 'الكل';
			$c           = collect();
			$c[ 'id' ]   = 0;
			$c[ 'name' ] = $c_name;
			$data[]      = $c;

			//insert other categories
			foreach ( $categories as $cat ) {
				$c           = collect();
				$c[ 'id' ]   = $cat -> id;
				$c[ 'name' ] = $cat -> name;
				$data[]      = $c;
			}

			//			$categories = collect(['0' => 'all'], $categories->all());

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );
		}


		/******************* Get project categories *******************/

		public function projectCategories( Request $request )
		{
			//make validation
			$validator = Validator ::make( $request -> all(), [
				'project_id' => 'required',
			] );

			if ( $validator -> passes() ) {

				//check if project exist
				$project = Project ::find( $request -> project_id );
				if ( !$project )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.no_project' ) ] );

				$name = 'name_' . App ::getLocale();

				//get all offer  categories
				$categories = AdsCategory ::select( 'id', $name . ' as name' )
				                          -> where( 'project_id', $request -> project_id )
				                          -> get();

				$data = [];

				//insert الكل
				$c_name      = $request -> header( 'lang' ) == 'en' ? 'all' : 'الكل';
				$c           = collect();
				$c[ 'id' ]   = 0;
				$c[ 'name' ] = $c_name;
				$data[]      = $c;

				//insert other categories
				foreach ( $categories as $cat ) {
					$c           = collect();
					$c[ 'id' ]   = $cat -> id;
					$c[ 'name' ] = $cat -> name;
					$data[]      = $c;
				}

				//			$categories = collect(['0' => 'all'], $categories->all());

				return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );
			}//validator faild
			else {
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.project_req' ) ] );
			}
		}

		/******************* Get Repair types *******************/

		public function repairType( Request $request )
		{
			//get repair types
			$name = 'name_' . App ::getLocale();

			$types = RepairType ::select( 'id', $name . ' as name' ) -> get();

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $types ] );
		}

		/******************* Add Job *******************/

		public function addJob( Request $request )
		{

			// check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

			//make validation
			$validator = Validator ::make( $request -> all(), [
				'name'    => 'required',
				'email'   => 'required',
				'phone'   => 'required',
				'lat'     => 'required',
				'lng'     => 'required',
				'details' => 'required',
				'cv'      => 'required',
			] );

			if ( $validator -> passes() ) {

				//check if name is correct
				if ( substr( $request -> name, 0, 17 ) === "android.support.v" )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.wrong_data' ) ] );

				//check if phone is correct
				if ( substr( $request -> phone, 0, 17 ) === "android.support.v" )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.wrong_data' ) ] );

				//check if email is correct
				if ( substr( $request -> email, 0, 17 ) === "android.support.v" )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.wrong_data' ) ] );

				$number = convert2english( $request -> phone );
				if ( !valid_phone( $number ) )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.wrong_phone' ) ] );


				//upload cv
				$name = $request -> cv;

				$cv = time() . '-' . $name -> getClientOriginalName();

				//remove all spaces from name
				$cv = str_replace( ' ', '-', $cv );


				//set file path
				$path = $name -> move( 'dashboard/uploads/cv/', $cv );

//				return $path;

				//add job to database
				$job             = new Job();
				$job -> name     = $request -> name;
				$job -> email    = $request -> email;
				$job -> phone    = $number;
				$job -> user_id  = $user -> id;
				$job -> location = $request -> location;
				$job -> lat      = $request -> lat;
				$job -> lng      = $request -> lng;
				$job -> details  = $request -> details;
				$job -> cv       = $cv;

				$job -> save();

				return response() -> json( [ 'value' => '1', 'key' => 'success' ] );

			}//if validation failed
			else {

				foreach ( (array)$validator -> errors() as $error ) {
					if ( isset( $error[ 'name' ] ) ) {
						$msg = trans( 'api.username_req' );
					} elseif ( isset( $error[ 'email' ] ) ) {
						$msg = trans( 'api.email_req' );
					} elseif ( isset( $error[ 'phone' ] ) ) {
						$msg = trans( 'api.phone_req' );
					} elseif ( isset( $error[ 'location' ] ) ) {
						$msg = trans( 'api.location_req' );
					} elseif ( isset( $error[ 'lat' ] ) ) {
						$msg = trans( 'api.lat_req' );
					} elseif ( isset( $error[ 'lng' ] ) ) {
						$msg = trans( 'api.lng_req' );
					} elseif ( isset( $error[ 'details' ] ) ) {
						$msg = trans( 'api.details_req' );
					} elseif ( isset( $error[ 'cv' ] ) ) {
						$msg = trans( 'api.cv_req' );
					} else {
						$msg = trans( 'api.error' );
					}
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
				}

			}

			//get repair types
			$name = 'name_' . App ::getLocale();

			$types = RepairType ::select( 'id', $name . ' as name' ) -> get();

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $types ] );
		}

		/******************* Add Repair *******************/

		public function addRepair( Request $request )
		{

			//make validation
			$validator = Validator ::make( $request -> all(), [
				'repair_type' => 'required',
				'phone'       => 'required',
				'details'     => 'required',
			] );

			if ( $validator -> passes() ) {

				//check if repair type exist
				$type = RepairType ::find( $request -> repair_type );
				if ( !$type )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.repair_type_not_exist' ) ] );

				$number = convert2english( $request -> phone );
				if ( !valid_phone( $number ) )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.wrong_phone' ) ] );


				Repair ::create( [
					                 'type_id' => $request -> repair_type,
					                 'phone'   => $number,
					                 'details' => $request -> details
				                 ] );

				return response() -> json( [ 'value' => '1', 'key' => 'success' ] );

			}//if validation failed
			else {

				foreach ( (array)$validator -> errors() as $error ) {
					if ( isset( $error[ 'repair_type' ] ) ) {
						$msg = trans( 'api.repair_type_req' );
					} elseif ( isset( $error[ 'phone' ] ) ) {
						$msg = trans( 'api.phone_req' );
					} elseif ( isset( $error[ 'details' ] ) ) {
						$msg = trans( 'api.details_req' );
					} else {
						$msg = trans( 'api.error' );
					}
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
				}

			}

			//get repair types
			$name = 'name_' . App ::getLocale();

			$types = RepairType ::select( 'id', $name ) -> get();

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $types ] );
		}

		/******************* Contact us *******************/

		public function contactUs( Request $request )
		{

			// check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'key' => '0', 'msg' => trans( 'api.no_user' ) ] );

			//make validation
			$validator = Validator ::make( $request -> all(), [
				'subject' => 'required',
				'message' => 'required',
			] );

			if ( $validator -> passes() ) {

				$contact            = new Contact();
				$contact -> name    = $user -> name;
				$contact -> email   = $user -> email;
				$contact -> phone   = $user -> phone;
				$contact -> subject = $request -> subject;
				$contact -> message = $request -> message;
				$contact -> save();

				return response() -> json( [ 'value' => '1', 'key' => 'success' ] );

			}//if validate fail
			else {
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.missing_data' ) ] );
			}
		}

		/******************* About us *******************/

		public function aboutUs( Request $request )
		{

			// get site descripton
			$desc = SiteSetting ::first();

			if ( App ::getLocale() == 'ar' )
				$data = $desc -> site_description;
			else
				$data = $desc -> site_description_en;


			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

		}

		/******************* terms and privacy *******************/

		public function terms( Request $request )
		{

			// get site descripton
			$desc = SiteSetting ::first();

			if ( App ::getLocale() == 'ar' )
				$data = $desc -> site_copyrigth;
			else
				$data = $desc -> site_copyrigth_en;

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

		}


		/******************* app social *******************/

		public function social( Request $request )
		{

			// get site phone
			$phone = SiteSetting ::select( 'site_phone' ) -> first();

			// get site social
			$social = Social ::select( 'name', 'link' ) -> get();


			//insert الكل
			$c            = collect();
			$c[ 'phone' ] = $phone -> site_phone;
			foreach ( $social as $s ) {
				$c [ $s -> name ] = $s -> link;
			}
			$data = $c;


			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

		}

		
		public function terms_social( Request $request )
		{

			// get site descripton
			$settigs = SiteSetting ::first();

			

			// get site social
			$social = Social ::select( 'name', 'link' ) -> get();


			$data          		= [];
			$data[ 'terms' ] 	= [];
			
			if ( App ::getLocale() == 'ar' )
				$data[ 'terms' ]['desc']= $settigs -> site_copyrigth;
			else
				$data[ 'terms' ]['desc'] = $settigs -> site_copyrigth_en;

			$data[ 'terms' ]['phone']= $settigs -> site_phone;


			//insert الكل
			$c            = collect();
			$c['facebook'] = null;
			$c['twitter'] = null;
			$c['instgram'] = null;

			foreach ( $social as $s ) {
				$c [ $s -> name ] = $s -> link;
			}
			
			$data[ 'social' ][] = $c;


			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

		}
	}
