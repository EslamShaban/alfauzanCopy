<?php

	namespace App\Http\Controllers\API;

	use App\Http\Controllers\Controller;
	use App\Models\Ads;
	use App\Models\Chat;
	use App\User;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\App;
	use Illuminate\Support\Facades\DB;
	use Tymon\JWTAuth\Facades\JWTAuth;
	use Validator;

	class ChatController extends Controller
	{

		public function __construct( Request $request )
		{
			/** Set Lang **/
			$request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
			/** Set Carbon Language **/
			$request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

		}

		/******************* Send new message *******************/

		public function sendMsg( Request $request )
		{

			// check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

			//make validation
			$validator = Validator ::make( $request -> all(), [
				'other_id' => 'required',
				'ads_id'   => 'required',
				'message'  => 'required',
			] );

			if ( $validator -> passes() ) {

				//check if other user exist
				$receiver = User ::find( $request -> other_id );
				if ( !$receiver )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

				//check if ads exist
				$ads = Ads ::find( $request -> ads_id );
				if ( !$ads )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_ads' ) ] );

				//check if reciver not ads owner
				if ( $request -> other_id != $ads -> user_id )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.wrong_reciver' ) ] );

				//create room
				$room = room_name( $user -> id, $request -> other_id, $request -> ads_id );

				//create message
				$msg            = new Chat();
				$msg -> message = $request -> message;
				$msg -> room    = $room;
				$msg -> s_id    = $user -> id;
				$msg -> r_id    = $request -> other_id;
				$msg -> ads_id  = $request -> ads_id;
				$msg -> save();

				return response() -> json( [ 'value' => '1', 'key' => 'success' ] );

			}//if validation fail
			else {

				return response() -> json( [ 'value' => '0', 'key' => 'fail',
				                             'msg'   => trans( 'api.missing_data' ) ] );
			}

		}

		/******************* all rooms *******************/

		public function allRooms( Request $request )
		{

			// check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );


			//get id for latest user rooms messages
			$chat_id = Chat ::select( DB ::raw( 'max(id) as lid' ) )
			                -> where( 's_id', $user -> id )
			                -> orWhere( 'r_id', $user -> id )
			                -> groupBy( 'room' )
			                -> orderBy( 'lid', 'desc' )
							-> pluck( 'lid' ) -> toArray();				
							

			$rooms = Chat ::whereIn( 'id', $chat_id )
			              -> latest()
						  -> paginate( 10 );
						  

			//return $rooms;
			$name = 'name_' . App ::getLocale();

			$data            = [];
			$data[ 'rooms' ] = [];
			foreach ( $rooms as $room ) {

				$unseen = Chat ::where('seen', 0)->where('room', $room->room)->get();
				$user 	= User ::find($room->r_id);

				$c                 = collect();
				$c[ 'other_name' ] = $user -> name;
				$c[ 'other_image' ] = url( 'dashboard/uploads/users/' . $user-> avatar );
				$c[ 'message' ]    = $room -> message;
				$c[ 'name' ]       = $room -> ads -> $name;
				$c[ 'image' ]      = url( 'dashboard/uploads/adss/' . $room -> ads -> image[ 0 ] -> image );
				$c[ 'created_at' ] = $room -> created_at -> format( 'm/d/Y' );
				$c[ 'author_id' ]  = $room -> s_id == $user -> id ? (string)$room -> r_id : (string)$room -> s_id;
				$c[ 'ad_id' ]      = (string)$room -> ads_id;
				$c[ 'unseen' ]       = count($unseen);
				$data[ 'rooms' ][] = $c;

			}
/*
			// make paginate instance
			$c                    = collect();
			$c[ 'total' ]         = $rooms -> total();
			$c[ 'count' ]         = $rooms -> count();
			$c[ 'per_page' ]      = 10;
			$c[ 'next_page_url' ] = $rooms -> nextPageUrl();
			$c[ 'prev_page_url' ] = $rooms -> previousPageUrl();
			$c[ 'current_page' ]  = $rooms -> currentPage();
			$c[ 'total_pages' ]   = $rooms -> lastPage();
			$data[ 'paginate' ]   = $c;
			*/

			return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );
		}

		/******************* all chat messages *******************/

		public function chat( Request $request )
		{

			// check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

			//make validation
			$validator = Validator ::make( $request -> all(), [
				'other_id' => 'required',
				'ads_id'   => 'required',
			] );

			if ( $validator -> passes() ) {

				//check if other user exist
				$receiver = User ::find( $request -> other_id );
				if ( !$receiver )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

				//check if ads exist
				$ads = Ads ::find( $request -> ads_id );
				if ( !$ads )
					return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_ads' ) ] );

				//check if reciver not ads owner
				if ( $request -> other_id != $ads -> user_id )
					return response() -> json( [ 'value' => '0', 'key' => 'fail',
					                             'msg'   => trans( 'api.wrong_reciver' ) ] );

				// room name
				$room = room_name( $user -> id, $request -> other_id, $request -> ads_id );

				//make message seen
				Chat ::where( 'room', $room ) -> where( 'r_id', $user -> id ) -> update( [ 'seen' => 1 ] );

				//get all message from room
				$msgs = Chat ::with( 'sender' ) -> where( 'room', $room ) -> get();

				//custom data

				$data               = [];
				$data[ 'messages' ] = [];
				foreach ( $msgs as $msg ) {

					$c                 = collect();
					$c[ 'message' ]    = $msg -> message;
					$c[ 'me' ]         = $user -> id == $msg -> s_id ? 1 : 0;
					$c[ 'created_at' ] = $msg -> created_at -> format( 'm/d/Y' );

					//get user image if reciver
					if ( $user -> id != $msg -> s_id )
						$c[ 'image' ] = url( 'dashboard/uploads/users/' . $msg -> sender -> avatar );

					$data[ 'messages' ][] = $c;
				}


				return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

			}//if validation fail
			else {

				return response() -> json( [ 'value' => '0', 'key' => 'fail',
				                             'msg'   => trans( 'api.missing_data' ) ] );
			}

		}

	}
