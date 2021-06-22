<?php

namespace App\Http\Controllers\API;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Doctor;

use Carbon\Carbon;
use Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class categoryController extends Controller
{
    public function __construct( Request $request )
    {
        /** Set Lang **/
        $request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
        /** Set Carbon Language **/
        $request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

    }

    public function offers(Request $request)
    {
        /*

            // check if user exist
			$user_data = JWTAuth ::parseToken() -> toUser();
			$user      = User ::find( $user_data -> id );

			if ( !$user )
				return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.no_user' ) ] );

                */


            if($request->has('category_id') && $request->category_id != null){
        
                
                $offers = Offer::where('category_id', $request ->category_id)->orWhere('doctor_id', $request ->category_id)->get();
                
                      
            }else{

                $offers = Offer::latest()->get();

            }

            $data['offers'] = null;

            $name = 'name_' . App::getLocale();

            if(count($offers)){

                foreach($offers as $offer){

                    $sum = $offer->doctor->stars->sum(function ($stars) {
                        return $stars['star'];
                    });
    
                    $star = $sum/count($offer->doctor->stars);
    
                    
                    $data['offers'][] = [	
    
                        'offer_title'               => $offer->offer_title,
                        'offer_description'         => $offer->offer_description,
                        'price'                     => $offer->price,
                        'discount'                  => $offer->discount,
                        'price_after_discount'      => $offer->price_after_discount,
                        'category'                  => $offer->category->$name,
                        'doctor_name'               => $offer->doctor->name,
                        'doctor_specialization'     => $offer->doctor->specialization,
                        'doctor_description'        => $offer->doctor->description,
                        'doctor_city'               => $offer->doctor->city->city_name,
                        'doctor_star'               => $star,
                        'count'                     => count($offer->doctor->stars),
                        'doctor_image'              => url('dashboard/uploads/doctors/' . $offer->doctor->image),

                    ];
                    
    
                }
            }

            

            return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success'),
            'data'  => $data]);
        
    }

}
