<?php

namespace App\Http\Controllers\API;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Star;
use Carbon\Carbon;
use Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class doctorController extends Controller
{
    public function __construct( Request $request )
    {
        /** Set Lang **/
        $request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
        /** Set Carbon Language **/
        $request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

    }

    public function doctor_info(Request $request)
    {
        /*

            // check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                */
			//make validation
			$validator = Validator ::make( $request -> all(), [
				'doctor_id' => 'required',
			] );

			if ( $validator -> passes() ) {

                $doctor_info = Doctor::where('id', $request ->doctor_id)->get()->first();

                //return $doctor_info;
                if(!count($doctor_info)){

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                }else{


                    //$appoint_info = Appointment::where('doctor_id', $doctor_info->id)->get()->first();
                    
                $sum = $doctor_info->stars->sum(function ($stars) {
                    return $stars['star'];
                });

                $star = $sum/count($doctor_info->stars);


                    $data['doctor_info'] = [
                        'name'              => $doctor_info->name,
                        'specialization'    => $doctor_info->specialization,
                        'description'       => $doctor_info->description,
                        'star'              => $star,
                        'image'             => url('dashboard/uploads/doctors/' . $doctor_info->image)
                    ];

                    $data['appointement_info'] = null;

                    if(count($doctor_info->appointments)){

                        foreach($doctor_info->appointments as $appoint_info){

                            $data['appointement_info'][] = [

                                'appoint_day'               => $appoint_info->appoint_day,
                                'start_at'                  => $appoint_info->start_at,
                                'end_at'                    => $appoint_info->end_at,
                                'price'                     => $appoint_info->price,
                                'address'                   => $appoint_info->address,
                                'wait_time'                 => $appoint_info->wait_time
    
                            ];
                        }


                    }

                    return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success'),
                    'data'  => $data]);

                }




            }else {

				return response() -> json( [ 'value' => '0', 'key' => 'fail',
				                             'msg'   => trans( 'api.missing_data' ) ] );
			}
        
    }

    public function stars(Request $request)
    {
                /*

            // check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                */
			//make validation
			$validator = Validator ::make( $request -> all(), [
				'doctor_id' => 'required',
                'star'      => 'required',
                'comment'   => 'nullable'
			] );

			if ( $validator -> passes() ) {

                $star = new Star();
                $star->user_id      = 445;
                $star->doctor_id    = $request->doctor_id;
                $star->star         = $request->star;
                $star->comment         = $request->comment;

                $star->save();

                return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success')]);


            }else {

				return response() -> json( [ 'value' => '0', 'key' => 'fail',
				                             'msg'   => trans( 'api.missing_data' ) ] );
			}
        

    }

}
