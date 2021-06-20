<?php

namespace App\Http\Controllers\API;

use App;
use App\User;
use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    


    public function __construct( Request $request )
    {
        /** Set Lang **/
        $request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
        /** Set Carbon Language **/
        $request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

    }


     public function notify(Request $request)
    {
    
        // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_user')]);

    //get notifications
    $notifications = Notification::where('user_id', $user->id)->get();

    if(count($notifications) == 0)
    
        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_notify')]);

    foreach($notifications as $notification){

        $date = new Carbon($notification->created_at);

        $notification['date'] = $date->diffForHumans();

    }
        
    return response()->json(['value' => '1', 'key' => 'success', 'data' => $notifications]);




    }

    public function notifilable(Request $request)
    {
        
        // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_user')]);

    
        $user->notifilable ? $user->notifilable=0 : $user->notifilable=1;

        $user->save();

        $notifilable = $user->notifilable ? 'on' : 'off';
    
        return response()->json(['value' => '1', 'key' => 'success', 'notifilable' => $notifilable]);

    }

   

}