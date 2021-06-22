<?php

    namespace App\Http\Controllers\API;

    use App;
    use App\Http\Controllers\Controller;
    use App\User;
    use Auth;
    use Carbon\Carbon;
    use File;
    use Hash;
    use Illuminate\Http\Request;
    use Image;
    use Session;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Validator;

    class AuthController extends Controller
    {
        public function __construct( Request $request )
        {
            /** Set Lang **/
            $request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
            /** Set Carbon Language **/
            $request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

        }

        /******************* Register *******************/

       

        public function register( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'name'     => 'required',
                'email'    => 'required',
                'phone'    => 'required',
                'password' => 'required'
            ] );

            // if validation pass
            if ( $validator -> passes() ) {

                //convert arabic numbers
               /*    $phone = convert2english( request( 'phone' ) );

                if ( !valid_phone( $phone ) )
                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.wrong_phone' ) ] );


                // check if phone exist
                $unique = is_unique( 'phone', $phone );

                if ( $unique ) {
                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.phone_exist' ) ] );
                }
                */
                // check if email exist
                $unique = is_unique( 'email', request( 'email' ) );
                if ( $unique ) {
                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.email_exist' ) ] );
                }

                //$code = generate_code();
                //add user to database
                $user             = new User();
                $user -> name     = $request -> name;
                $user -> email    = $request -> email;
                $user -> phone    = $request -> phone;
                $user -> active   = 0;
                //$user -> code     = $code;
                $user -> password = Hash ::make( $request -> password );
                $user -> role     = 2;
                $user -> active   = 1;  //added
                $user -> save();

                //send verivication code
                // send_mobile_sms( $user -> phone, 'كود التفعيل الخاص بك : ' . $user -> code );

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ), 'data' => $user->id/*  -> code */ ] );


                // get token

                $user              = User ::find( $user -> id );
                $token             = JWTAuth ::fromUser( $user );
                $user -> jwt_token = $token;
                $user -> save();

                $data = [
                    'name'   => $user -> name,
                    'phone'  => $user -> phone,
                    'email'  => $user -> email,
                    'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
                    'token'  => $user -> jwt_token,
                ];

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ),
                                             'data'  => $data ] );

            } else {
                foreach ( (array)$validator -> errors() as $error ) {
                    {

                        if ( isset( $error[ 'name' ] ) ) {
                            $msg = trans( 'api.username_req' );
                        } elseif ( isset( $error[ 'phone' ] ) ) {
                            $msg = trans( 'api.phone_req' );
                        } elseif ( isset( $error[ 'email' ] ) ) {
                            $msg = trans( 'api.email_req' );
                        } elseif ( isset( $error[ 'password' ] ) ) {
                            $msg = trans( 'api.password_req' );
                        } else {
                            $msg = trans( 'api.error' );
                        }
                    }
                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }
        }

        public function phoneRegistration (Request $request)
        {
              //make validation
              $validator = Validator ::make( $request -> all(), [
                'id'    => 'required|exists:users,id',
                'phone'    => 'required',
            ] );

            // if validation pass
            if ( $validator -> passes() ) 
            {
                //convert arabic numbers
                $phone = convert2english( request( 'phone' ) );

                if ( !valid_phone( $phone ) )
                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.wrong_phone' ) ] );


                // check if phone exist
                $unique = is_unique( 'phone', $phone );

                if ( $unique ) {
                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.phone_exist' ) ] );
                }
                $user = User ::find( $request -> id );
                $user -> phone    = $phone;
                $user -> save();

                send_mobile_sms( $user -> phone, 'كود التفعيل الخاص بك : ' . $user -> code );

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ), 'data' => $user -> code ] );

            } else {

                foreach ( (array)$validator -> errors() as $error ) {
                    {
                      
                        if ( isset( $error[ 'phone' ] ) ) {
                            $msg = trans( 'api.phone_req' );
                        } elseif ( isset( $error[ 'id' ] ) ){
                            $msg = trans( 'api.no_user' );
                        } else {
                            $msg = trans( 'api.error' );
                        }
                    }
                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }
        }
        
        
                public function sendOtp(Request $request){

            //make validation
            $validator = Validator ::make( $request -> all(), [
            'id'    => 'required|exists:users,id',
            'phone'    => 'required',
        ] );

        // if validation pass
        if ( $validator -> passes() ) 
        {
            //convert arabic numbers
            $phone = convert2english( request( 'phone' ) );

            if ( !valid_phone( $phone ) )
                return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                            'msg'   => trans( 'api.wrong_phone' ) ] );


            // check if phone exist
            $unique = is_unique( 'phone', $phone );

            if ( $unique ) {
                return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                            'msg'   => trans( 'api.phone_exist' ) ] );
            }

            $code = generate_code();

            $user = User ::find( $request -> id );
            $user -> code    = $code;
            $user -> save();

            send_mobile_sms( $phone, 'كود التفعيل الخاص بك : ' . $user -> code );

            return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ), 'data' => $user -> code ] );

        } else {

            foreach ( (array)$validator -> errors() as $error ) {
                {
                    
                    if ( isset( $error[ 'phone' ] ) ) {
                        $msg = trans( 'api.phone_req' );
                    } elseif ( isset( $error[ 'id' ] ) ){
                        $msg = trans( 'api.no_user' );
                    } else {
                        $msg = trans( 'api.error' );
                    }
                }
                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
            }
        }


        }

        public function updateUserPhone(Request $request)
        {
            //make validation
            $validator = Validator ::make( $request -> all(), [
            'id'    => 'required|exists:users,id',
            'phone'    => 'required',
            'code'     => 'required'
        ] );

        // if validation pass
        if ( $validator -> passes() ) 
        {
            //convert arabic numbers
            $phone = convert2english( request( 'phone' ) );
            $code  = request( 'code' );

            if ( !valid_phone( $phone ) )
                return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                            'msg'   => trans( 'api.wrong_phone' ) ] );


            // check if phone exist
            $unique = is_unique( 'phone', $phone );

            if ( $unique ) {
                return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                            'msg'   => trans( 'api.phone_exist' ) ] );
            }

            $user = User ::find( $request -> id );
            
            
            if( $user -> code == $code){

                $user -> update( [ 'phone' => $phone, 'code' => null ] );
                
                $token = JWTAuth ::fromUser( $user );
                $user -> update( [ 'jwt_token' => $token ] );
                //				return $token;
    
    
                //get user data
                $data = [
                    'name'   => $user -> name,
                    'email'  => $user -> email,
                    'phone'  => $user -> phone,
                    'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
                    'token'  => $token
                ];
    
                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ),
                                             'data'  => $data ] );

            }else{

                return response() -> json( [ 'value' => '0', 'key' => 'fail',
                'msg'   => trans( 'api.wrong_code' ) ] );
            }


        } else {

            foreach ( (array)$validator -> errors() as $error ) {
                {
                    
                    if ( isset( $error[ 'phone' ] ) ) {
                        $msg = trans( 'api.phone_req' );
                    } elseif ( isset( $error[ 'id' ] ) ){
                        $msg = trans( 'api.no_user' );
                    } elseif ( isset( $error[ 'code' ] ) ){
                        $msg = trans( 'api.code_req' );
                    }else {
                        $msg = trans( 'api.error' );
                    }
                }
                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
            }
        }
            
        }

        /******************* Login *******************/

        public function login( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'phone'    => 'required',
                'password' => 'required',
            ] );

            //if validation pass
            if ( $validator -> passes() ) {

                $perms[ 'phone' ]    = $request -> phone;
                $perms[ 'password' ] = $request -> password;

                //make auth check
                try {
                    if ( !$token = JWTAuth ::attempt( $perms ) ) {

                        return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                     'msg'   => trans( 'api.wrong_data' ) ] );
                    }
                } catch ( JWTException $ex ) {

                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.wrong_data' ) ] );
                }

                //check if user blocked
                if ( Auth ::user() -> block ) {

                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.blocked_user' ) ] );
                }


                //check if user not active
                if ( !Auth ::user() -> active ) {

                    return response() -> json( [ 'value' => '2', 'key' => 'fail',
                                                 'msg'   => trans( 'api.inActive_user' ) ] );
                }

                //set jwt_token
                $user              = User ::find( Auth ::user() -> id );
                $user -> jwt_token = $token;
                $user -> save();

                //get user data
                $data = [
                    'name'   => $user -> name,
                    'email'  => $user -> email,
                    'phone'  => $user -> phone,
                    'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
                    'token'  => $user -> jwt_token
                ];

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => 'Success'/* trans( 'api.success' ) */,
                                             'data'  => $data ] );


            } // if validation failed
            else {
                foreach ( (array)$validator -> errors() as $error ) {

                    if ( isset( $error[ 'phone' ] ) ) {
                        $msg = trans( 'api.phone_req' );
                    } elseif ( isset( $error[ 'password' ] ) ) {
                        $msg = trans( 'api.password_req' );
                    } else {
                        $msg = trans( 'api.error' );
                    }

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => "failure"/* $msg */ ] );
                }
            }
        }

        /******************* forget password *******************/

        public function forgetPassword( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'phone' => 'required'
            ] );

            //if validation pass
            if ( $validator -> passes() ) {

                //get user with such phone
                $user = User ::where( 'phone', $request -> phone ) -> first();

                if ( $user ) {

                    //					//generate code ,token and assigin these to user table
                    //					$token = JWTAuth::fromUser( $user );
                    //
                    $user -> code = generate_code();
                    //					$user->jwt_token = $token;
                    $user -> save();

                    //send sms to user
                    $msg   = trans( 'api.vertify_code' ) . $user -> code;
                    $phone = $user -> phone;

                    send_mobile_sms( $phone, $msg );


                    $data = [
                        //						'token' => $token,
                        'code' => $user -> code
                    ];
                    return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ),
                                                 'data'  => $data ] );
                } // if no account with such phone
                else {

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_phone' ) ] );
                }
            }// if validation fail
            else {
                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.phone_req' ) ] );
            }
        }

        /******************* reset password *******************/

        public function resetPassword( Request $request )
        {

            //			// check if user exist
            //			$user_data = JWTAuth::parseToken()->toUser();
            //			$user      = User::find( $user_data->id );
            //
            //			if ( !$user)
            //				return response()->json( ['value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' )] );

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'code'     => 'required',
                'phone'    => 'required',
                'password' => 'required',
            ] );

            if ( $validator -> passes() ) {

                //check if user with such phone is exist
                $user = User ::where( 'phone', $request -> phone ) -> first();
                if ( !$user )
                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                //compare user code with request code
                if ( $user -> code != $request -> code ) {

                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.wrong_code' ) ] );
                }

                $user -> password = bcrypt( $request -> password );
                $user -> save();

                return response() -> json( [ 'value' => '1', 'key' => 'success',
                                             'msg'   => trans( 'api.password_updated' ) ] );

            } else {
                foreach ( (array)$validator -> errors() as $error ) {
                    if ( isset( $error[ 'code' ] ) ) {
                        $msg = trans( 'api.code_req' );
                    } elseif ( isset( $error[ 'password' ] ) ) {
                        $msg = trans( 'api.password_req' );
                    } elseif ( isset( $error[ 'phone' ] ) ) {
                        $msg = trans( 'api.phone_req' );
                    } else {
                        $msg = trans( 'api.error' );
                    }
                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }
        }

        /******************* code check *******************/

        public function codeCheck( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'code'  => 'required',
                'phone' => 'required'
            ] );

            if ( $validator -> passes() ) {

                $user = User ::where( 'phone', $request -> phone ) -> first();
                if ( !$user )
                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                //compare user code with request code
                if ( $user -> code != $request -> code ) {

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.wrong_code' ) ] );
                }

                $user -> update( [ 'active' => 1, 'code' => null ] );

                $token = JWTAuth ::fromUser( $user );
                $user -> update( [ 'jwt_token' => $token ] );
                //				return $token;


                //get user data
                $data = [
                    'name'   => $user -> name,
                    'email'  => $user -> email,
                    'phone'  => $user -> phone,
                    'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
                    'token'  => $token
                ];

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ),
                                             'data'  => $data ] );


            } else {
                foreach ( (array)$validator -> errors() as $error ) {

                    if ( isset( $error[ 'phone' ] ) ) {
                        $msg = trans( 'api.phone_req' );
                    } elseif ( isset( $error[ 'code' ] ) ) {
                        $msg = trans( 'api.code_req' );
                    } else {
                        $msg = trans( 'api.error' );
                    }

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }
        }


        /******************* User profile *******************/

        public function profile( Request $request )
        {
            // check if user exist
            $user_data = JWTAuth ::parseToken() -> toUser();
            $user      = User ::find( $user_data -> id );

            if ( !$user )
                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

            //get user info
            $data = [
                'name'   => $user -> name,
                'email'  => $user -> email,
                'phone'  => $user -> phone,
                'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
            ];
            return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

        }

        /******************* Edit profile *******************/

        public function editProfile( Request $request )
        {

            // check if user exist
            $user_data = JWTAuth ::parseToken() -> toUser();
            $user      = User ::find( $user_data -> id );

            if ( !$user )
                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'phone'    => 'nullable|unique:users,phone,' . $user -> id,
                'email'    => 'nullable|unique:users,email,' . $user -> id,
                'avatar'   => 'nullable',
                'name'     => 'nullable',
                'password' => 'nullable',
            ] );

            if ( $validator -> passes() ) {

                //check phone
                if ( $request -> has( 'phone' ) && $request -> phone != null ) {

                    $number = convert2english( $request -> phone );
                    if ( !valid_phone( $number ) )
                        return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                     'msg'   => trans( 'api.wrong_phone' ) ] );
                    $user -> phone = $number;
                }

                //check email
                if ( $request -> has( 'email' ) && $request -> email != null ) {

                    $user -> email = $request -> email;
                }

                //check name
                if ( $request -> has( 'name' ) && $request -> name != null ) {

                    $user -> name = $request -> name;
                }

                //check avatar
                //				if ($request->avatar && $request->avatar != null) {
                //
                //
                //
                //
                //
                //					$base64_img = $request->avatar;
                //					$path       = 'dashboard/uploads/users/';
                //					$fileName   = upload_img( $base64_img, $path );
                //
                //					$user->avatar = $fileName;
                //
                //
                //				}


                //				if ($request->file('avatar')) {
                //						$photo = $request->file('avatar');
                //						$name = date('d-m-y') . time() . rand() . '.' . $photo->getClientOriginalExtension();
                //						Image::make($photo)->resize(1200, 1200)->orientate()->save('dashboard/uploads/users/' . $name);
                //						$user->avatar = $name;
                //						$user->save();
                //
                //
                //				}

                //check avatar
                if ( $request -> file( 'avatar' ) ) {

                    if ( $user -> avatar != 'default.png' )
                        File ::delete( 'dashboard/uploads/users/' . $user -> avatar );

                    $photo = $request -> file( 'avatar' );
                    $name  = date( 'd-m-y' ) . time() . rand() . '.' . $photo -> getClientOriginalExtension();
                    Image ::make( $photo ) -> resize( 1200, 1200 ) -> orientate()
                          -> save( 'dashboard/uploads/users/' . $name );
                    $user -> avatar = $name;
                }

                //password
                if ( $request -> has( 'password' ) && $request -> password != null ) {

                    $user -> password = bcrypt( $request -> password );


                }

                $user -> save();

                $data = [
                    'name'   => $user -> name,
                    'email'  => $user -> email,
                    'phone'  => $user -> phone,
                    'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
                    'token'  => $user -> jwt_token,
                ];
                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ),
                                             'data'  => $data ] );
            }// if validation failed
            else {

                foreach ( (array)$validator -> errors() as $error ) {
                    if ( isset( $error[ 'phone' ] ) ) {
                        $msg
                            = trans( 'api.phone_exist' );
                    } elseif
                    ( isset( $error[ 'email' ] ) ) {
                        $msg = trans( 'api.email_exist' );
                    } else {
                        $msg = trans( 'api.error' );
                    }
                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }
        }

        /******************* logout *******************/

        public
        function logout( Request $request )
        {

            // check if user exist
            $user_data = JWTAuth ::parseToken() -> toUser();
            $user      = User ::find( $user_data -> id );

            if ( !$user )
                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

            // make token invalid and logout
            JWTAuth ::invalidate();
            $user -> jwt_token = null;
            $user -> save();

            return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.logout' ) ] );

        }
        
        public function social_login(Request $request)
        {
           

                if ( $request -> has( 'phone' ) && $request -> phone != null ) {

                    $user = User ::where( 'phone', $request -> phone )->orWhere('email', $request -> phone) -> first();

                    if($user){

                        //get user info
                        $data = [
                            'id'     => $user->id,
                            'name'   => $user -> name,
                            'email'  => $user -> email,
                            'phone'  => $user -> phone,
                            'avatar' => url( 'dashboard/uploads/users/' . $user -> avatar ),
                            'token'  => $user -> jwt_token
                        ];

                        return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.success' ),
                        'data'  => $data ] );

                    }else{

                        return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                    }


                }



                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.email_or_phone_required' ) ] );



        }


    }
