<?php

	namespace App\Http\Controllers;

	use App\Mail\SendEmail;
	use App\Models\Role;
	use App\User;
	use Auth;
	use File;
	use Illuminate\Http\Request;
	use Image;
	use Mail;
	use Session;

	//    use Illuminate\Support\Facades\Mail;

	class UsersController extends Controller
	{
		#users page
		public function Users()
		{
			$users = User ::with( 'Role' ) -> latest() -> get();
			$roles = Role ::latest() -> get();
			return view( 'dashboard.users.users', compact( 'users', $users, 'roles', $roles ) );
		}

		#add user
		public function AddUser( Request $request )
		{
			//                    return $request->all();
			$this -> validate( $request, [
				'name'     => 'required|min:2|max:190',
				'email'    => 'required|email|min:2|max:190|unique:users',
				'phone'    => 'required|min:2|max:190|unique:users',
				'avatar'   => 'required|image',
				'password' => 'required|min:6|max:190'
			] );

			$user          = new User;
			$user -> name  = $request -> name;
			$user -> email = $request -> email;
			$user -> phone = $request -> phone;
			$user -> role  = $request -> role;
			$user -> active  = 1;

			if ( $request -> block ==1 ) {
				$user -> block = $request -> block;
			} else {
				$user -> block = 0;
			}

			$user -> password = bcrypt( $request -> password );
			$photo            = $request -> avatar;
			$name             = date( 'd-m-y' ) . time() . rand() . '.' . $photo -> getClientOriginalExtension();
			Image ::make( $photo ) -> save( 'dashboard/uploads/users/' . $name );
			$user -> avatar = $name;
			$user -> save();
			Report( Auth ::user() -> id, 'بأضافة عضو جديد' );
			Session ::flash( 'success', 'تم اضافة العضو' );
			return back();
		}

		#update user
		public function UpdateUser( Request $request )
		{

//			return $request->all();
			$this -> validate( $request, [
				'edit_name'     => 'required|min:2|max:190',
				'edit_email'    => 'required|email|min:2|max:190',
				'edit_phone'    => 'required|min:2|max:190',
				'edit_avatar'   => 'nullable|image',
				'edit_password' => 'nullable|min:6|max:190'
			] );

			$user = User ::findOrFail( $request -> id );


			//check if email exist
			$u = User ::where( 'email', $request -> edit_email ) -> where( 'id', '!=', $request -> id ) -> first();

			if ( $u ) {
				Session ::flash( 'danger', 'البريد الالكترونى غير متاح' );
				return back();
			}


			//check if phone exist
			$u = User ::where( 'phone', $request -> edit_phone ) -> where( 'id', '!=', $request -> id ) -> first();

			if ( $u ) {
				Session ::flash( 'danger', 'رقم الهاتف غير متاح' );
				return back();
			}
			$user -> name  = $request -> edit_name;
			$user -> email = $request -> edit_email;
			$user -> phone = $request -> edit_phone;


			if ( !is_null( $request -> edit_password ) ) {
				$user -> password = bcrypt( $request -> edit_password );
			}

			if ( !is_null( $request -> role ) ) {
				if ( $user -> id != 1 ) {
					$user -> role = $request -> role;
				} else {
					Session ::flash( 'danger', 'لا يمكن تغير صلاحية هذا العضو' );
					return back();
				}
			}


			if ( $request -> block ==1 ) {

				if ( $user -> id == Auth ::id() ) {
					Session ::flash( 'danger', 'لا يمكن حظر هذا العضو' );
					return back();
				}

				if ( $user -> id != 1 ) {

					$user -> block = $request -> block;
				} else {
					Session ::flash( 'danger', 'لا يمكن حظر هذا العضو' );
					return back();
				}
			} else {
				$user -> block = 0;
			}

			if ( !empty( $request -> edit_avatar ) ) {

				if ( $user -> avatar != 'default.png' ) {

					File ::delete( 'dashboard/uploads/users/' . $user -> avatar );
				}
				$photo = $request -> edit_avatar;
				$name  = time() . rand() . '.' . $photo -> getClientOriginalExtension();
				Image ::make( $photo ) -> save( 'dashboard/uploads/users/' . $name );
				$user -> avatar = $name;

			}

			$user -> save();
			Report( Auth ::user() -> id, 'بتحديث بيانات  ' . $user -> name );
			Session ::flash( 'success', 'تم حفظ التعديلات' );
			return back();
		}

		#delete user
		public function DeleteUser( Request $request )
		{
			if ( $request -> id == 1 ) {
				Session ::flash( 'danger', 'لا يمكن حذف هذا العضو' );
				return back();
			} else {
				$user = User ::findOrFail( $request -> id );

				if ( $user -> id == Auth ::id() ) {
					Session ::flash( 'danger', 'لا يمكن حذف هذا العضو' );
					return back();
				}
				if ( $user -> avatar != 'default.png' ) {
					File ::delete( 'dashboard/uploads/users/' . $user -> avatar );

					$user -> avatar = 'default.png';


				}


				$user -> save();

				$user -> delete();
				Report( Auth ::user() -> id, 'بحذف العضو ' . $user -> name );
				Session ::flash( 'success', 'تم الحذف' );
				return back();
			}
		}

		#email correspondent for all users
		public function EmailMessageAll( Request $request )
		{

			$this -> validate( $request, [
				'email_message' => 'required|min:1'
			] );

			$users = User ::get();


			try {

				foreach ( $users as $u ) {

					Mail ::to( $u -> email ) -> send( new SendEmail( $request -> email_message ) );
				}

				Session ::flash( 'success', 'تم ارسال الرساله' );
				return back();

			} catch ( \Exception $e ) {

				Session ::flash( 'danger', 'لم يتم ارسال الرساله ! .. يرجى مراجعة بيانات ال SMTP' );
				return back();
			}


		}

		#sms correspondent for all users
		public function SmsMessageAll( Request $request )
		{
			$this -> validate( $request, [
				'sms_message' => 'required'
			] );

			//			return $request -> all();

			$numbers = User ::pluck( 'phone' ) -> toArray();

			//            Mobily::send($numbers, 'Your Message Here');
			//            return $users;
			            foreach ( $numbers as $u ) {
			                send_mobile_sms( $u, $request -> sms_message );
			            }

			Session ::flash( 'success', 'تم ارسال الرساله' );
			return back();
		}


		#end email for current user
		public function SendEmail( Request $request )
		{

			$this -> validate( $request, [
				'email_message' => 'required|min:1',
				'email'         => 'required',
			] );

			try {

				Mail ::to( $request -> email ) -> send( new SendEmail( $request -> email_message ) );
				Session ::flash( 'success', 'تم ارسال الرساله' );
				return back();

			} catch ( \Exception $e ) {

				Session ::flash( 'danger', 'لم يتم ارسال الرساله ! .. يرجى مراجعة بيانات ال SMTP' );
				return back();
			}


		}

		#send sms for current user
		public function SendSMS( Request $request )
		{


			$this -> validate( $request, [
				'sms_message' => 'required',
				'phone'       => 'required',

			] );

//			            return $request->all();

			//            Mobily::send($request->phone, 'Your Message Here');
			send_mobile_sms( $request -> phone, $request -> sms_message );

			Session ::flash( 'success', 'تم ارسال الرساله' );
			return back();
		}


	}
