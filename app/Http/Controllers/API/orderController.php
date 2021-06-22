<?php

namespace App\Http\Controllers\API;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Appointment;
use App\User;
use Carbon\Carbon;
use Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class orderController extends Controller
{
    public function __construct( Request $request )
    {
        /** Set Lang **/
        $request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
        /** Set Carbon Language **/
        $request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

    }


    public function Order(Request $request)
    {
    
        /*
        // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_user')]);

        */

      //make validation
      $validator = Validator::make($request->all(), [
        'appoint_id'  => 'required'
      ]);

      if ($validator->passes()) {
        $order                = new Order();
        $order -> user_id     = 445;
        $order -> appoint_id  = $request -> appoint_id;

        $order -> save();



        return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success')]);

      }//if validation failed
      else {

        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.missing_data')]);
      }
    }

    public function myOrder(Request $request)
    {
                /*
        // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_user')]);

        */

        $user = User::find(445);
        
        $data['order'] = null;

    
        foreach($user->appointments as $appointment){

            $data['order'][] = [

                'appoint_day'               => $appointment->appoint_day,
                'start_at'                  => $appointment->start_at,
                'end_at'                    => $appointment->end_at,
                'price'                     => $appointment->price,
                'address'                   => $appointment->address,
                'wait_time'                 => $appointment->wait_time,
                'doctor'                    => $appointment->doctor->name

            ];
        }

        return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success'),
        'data'  => $data]);
    }
}
