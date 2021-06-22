<?php

  namespace App\Http\Controllers\API;

  use App;
  use App\Http\Controllers\Controller;
  use App\Models\Ads;
  use App\Models\Favourite;
  use App\Models\Review;
  use App\Models\View;
  use App\Models\Order;
  use App\User;
  use Carbon\Carbon;
  use Illuminate\Http\Request;
  use Session;
  use Tymon\JWTAuth\Facades\JWTAuth;
  use Validator;

  class AdsController extends Controller
  {
    public function __construct(Request $request)
    {
      /** Set Lang **/
      $request->header('lang') == 'en' ? App::setLocale('en') : App::setLocale('ar');
      /** Set Carbon Language **/
      $request->header('lang') == 'en' ? Carbon::setLocale('en') : Carbon::setLocale('ar');

    }

    /******************* ads info *******************/

    public function adsInfo(Request $request)
    {

      //check if there is jwt token then increment view
      try {
        $tokenFetch = "";
        if ($request->header('Authorization'))
          $tokenFetch = JWTAuth::parseToken()->authenticate();
        if ($tokenFetch) {

          // check if user exist
          $user_data = JWTAuth::parseToken()->toUser();
          $user      = User::find($user_data->id);

          if (!$user)
            return response()->json(['value' => '0', 'key' => 'fail',
                                     'msg'   => trans('api.no_user')]);


        }
      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {//general JWT exception

        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.error')]);
      }

      //make validation
      $validator = Validator::make($request->all(), [
        'ads_id' => 'required',
      ]);

      if ($validator->passes()) {


        $ads = Ads::with('review.user', 'image', 'view')->find($request->ads_id);
        //if ads not exist
        if (!$ads)
          return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_ads')]);

        $tax  = (float)($ads->cost * $ads->tax / 100.00);
        $vat  = (float)($ads->cost * $ads->vat / 100.00);
        $vat2 = (float)($vat * 5 / 100.00);

        //get ads data
        $name = 'name_' . App::getLocale();
        $data = [
          'id'           => $ads->id,
          'name'         => $ads->$name,
          'desc'         => $ads->desc,
          'area'         => $ads->area,
          'area_number'  => $ads->area_number,
          'block_number' => $ads->block_number,
          'cost'         => $ads->cost,
          'tax'          => $ads->no_tax ? $tax : 0,
          'vat'          => $vat,
          'vat2'         => $vat2,
          'total_cost'   => round($ads->cost + $tax + $vat + $vat2, 2),
          'created_at'   => $ads->created_at->format('m/d/Y'),
          'views'        => count($ads->view),
          'lat'          => $ads->lat,
          'lng'          => $ads->lng,
          'details'      => $ads->details,
          'author_id'    => $ads->user_id,
          //                'component'  => $ads -> component,
          'video'        => $ads->video ? getYoutubeVideoId($ads->video) : '',
          // 'link'         => 'https://fouzan.aait-sa.com/ad-info/' . $ads->id,
          'link'         => url("ad-info/$ads->id"),
          //'avatar'     => url( 'dashboard/uploads/adss/' . $user->avatar ),
        ];

        if (isset($user)) {
          //check if ad is favourite
          $data['isFav'] = isFavourite($user->id, $ads->id);

          // increase view if this is first time to user
          View::firstOrCreate([
            'ads_id'  => $ads->id,
            'user_id' => $user->id
          ]);

        }


        //custom review fields
        $review = [];
        foreach ($ads->review as $r) {
          $c               = collect();
          $c['rate']       = (float)$r->rate;
          $c['comment']    = $r->comment;
          $c['name']       = $r->user->name;
          $c['created_at'] = $r->created_at->diffForHumans();
          $c['avatar']     = url('dashboard/uploads/users/' . $r->user->avatar);

          $review[] = $c;
        }
        $data['reviews'] = $review;

        //custom image fields
        $image = [];
        foreach ($ads->image as $r) {
          $c          = collect();
          $c['image'] = url('dashboard/uploads/adss/' . $r->image);

          $image[] = $c;
        }
        $data['images'] = $image;


        return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success'),
                                 'data'  => $data]);
      } else {

        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.ads_req')]);
      }


    }

    /******************* Add review *******************/

    public function addReview(Request $request)
    {

      // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_user')]);

      //make validation
      $validator = Validator::make($request->all(), [
        'ads_id'  => 'required',
        'rate'    => 'required',
        'comment' => 'required',
      ]);

      if ($validator->passes()) {

        //get ads info
        $ads = Ads::find($request->ads_id);

        //if ads not exist
        if (!$ads)
          return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_ads')]);

        //store review

        $review          = new Review();
        $review->rate    = $request->rate;
        $review->comment = $request->comment;
        $review->ads_id  = $request->ads_id;
        $review->user_id = $user->id;
        $review->save();

        return response()->json(['value' => '1', 'key' => 'success', 'msg' => trans('api.success')]);

      }//if validation failed
      else {

        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.missing_data')]);
      }

    }

    /******************* All Favourites  *******************/

    public
    function allFav(Request $request)
    {

      // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['value' => 1, 'key' => 'success', 'msg' => trans('api.no_user')]);

      // get ads id
      $ads_id = Favourite::where('user_id', $user->id)->pluck('ads_id')->toArray();

      $name = 'name_' . App::getLocale();

      //get all ads
      $ads = Ads::with('image', 'category')->whereIn('id', $ads_id)
        ->paginate(10);

      $data        = [];
      $data['ads'] = [];
      foreach ($ads as $ad) {

        $c          = collect();
        $c['id']    = $ad->id;
        $c['name']  = $ad->$name;
        $c['desc']  = $ad->desc;
        $c['image'] = url('dashboard/uploads/adss/' . $ad->image[0]->image);
        //check if ads is offer
        if ($ad->category_id) {

          $c['category_id'] = $ad->category->id;
          $c['category']    = $ad->category->$name;
        } else {

          $c['category_id'] = $ad->adsCategory->id;
          $c['category']    = $ad->adsCategory->$name;
        }

        $data['ads'][] = $c;
      }

      // make paginate instance
      /*
      $c                  = collect();
      $c['total']         = $ads->total();
      $c['count']         = $ads->count();
      $c['per_page']      = 10;
      $c['next_page_url'] = $ads->nextPageUrl();
      $c['perv_page_url'] = $ads->previousPageUrl();
      $c['current_page']  = $ads->currentPage();
      $c['total_pages']   = $ads->lastPage();
      $data['paginate']   = $c;
      */

      return response()->json(['value' => '1', 'key' => 'success', 'data' => $data]);

    }

    /******************* Add to favourite  *******************/

    public
    function addFav(Request $request)
    {

      // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['key' => '0', 'msg' => trans('api.no_user')]);

      //make validation
      $validator = Validator::make($request->all(), [
        'ads_id' => 'required',
      ]);

      if ($validator->passes()) {

        //get ads info
        $ads = Ads::find($request->ads_id);

        //if ads not exist
        if (!$ads)
          return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_ads')]);

        //check if ads is already favourite for current user
        $isFav
          = Favourite::where('user_id', $user->id)->where('ads_id', $request->ads_id)
          ->first();

        if ($isFav)
          return response()->json(['value' => '0', 'key' => 'fail',
                                   'msg'   => trans('api.fav_exist')]);
        Favourite::create([
          'user_id' => $user->id,
          'ads_id'  => $request->ads_id,
        ]);

        return response()->json(['value' => '1', 'key' => 'success']);

      }//if validation failed
      else {

        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.missing_data')]);
      }
    }

    /******************* delete from favourite  *******************/

    public
    function removeFav(Request $request)
    {

      // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['key' => '0', 'msg' => trans('api.no_user')]);

      //make validation
      $validator = Validator::make($request->all(), [
        'ads_id' => 'required',
      ]);

      if ($validator->passes()) {

        //get ads info
        $ads = Ads::find($request->ads_id);

        //if ads not exist
        if (!$ads)
          return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_ads')]);

        //check if ads is  favourite for current user
        $isFav
          = Favourite::where('user_id', $user->id)->where('ads_id', $request->ads_id)
          ->first();

        if (!$isFav)
          return response()->json(['value' => '0', 'key' => 'fail',
                                   'msg'   => trans('api.fav_not_exist')]);

        $isFav->delete();

        return response()->json(['value' => '1', 'key' => 'success']);

      }//if validation failed
      else {

        return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.missing_data')]);
      }
    }

    /******************* orders *******************/

    public function orders(Request $request)
    {

      // check if user exist
      $user_data = JWTAuth::parseToken()->toUser();
      $user      = User::find($user_data->id);

      if (!$user)
        return response()->json(['key' => '0', 'msg' => trans('api.no_user')]);

      $orders = Order::where('user_id', $user->id)->latest()->get();

      $wait   = App::getLocale() == 'ar' ? 'قيد الانتظار' : 'Waited';
      $accept = App::getLocale() == 'ar' ? 'تم الموافقة' : 'Accepted';
      $refuse = App::getLocale() == 'ar' ? 'تم الرفض' : 'Refused';
      $rent   = App::getLocale() == 'ar' ? 'ايجار' : 'Rent';
      $sell   = App::getLocale() == 'ar' ? 'بيع' : 'Sell';


      $data = [];

      foreach ($orders as $order) {
        if ($order->accept == 0)
          $status = $wait;
        elseif ($order->accept == 1)
          $status = $accept;
        else
          $status = $refuse;

        $type   = $order->is_rent ? $sell : $rent;
        $data[] = [
          'id'       => $order->ads_id,
          'ad_name'  => $order->ads['name_' . App::getLocale()] . ' - ' . $type,
          'ad_image' => $order->ads->image ? url('dashboard/uploads/adss/' . $order->ads->image[0]->image) : '',
          'status'   => $status,
          'date'     => $order->created_at->format('Y-m-d h:i a'),
        ];
      }
      return response()->json(['value' => '1', 'key' => 'success', 'data' => $data]);


    }
        public function ads(Request $request)
    {


        // check if user exist
        $user_data = JWTAuth::parseToken()->toUser();
        $user      = User::find($user_data->id);

         if($request->has('category_id') && $request->category_id != null){
          
          $category_id = request('category_id');
        
             
         }
        

        if (!$user)
            return response()->json(['value' => '0', 'key' => 'fail', 'msg' => trans('api.no_user')]);

        
        if(isset($category_id) && $category_id != null){
            

          $ads = Ads::where('category_id', $category_id)->get();
          
        }else{

          $ads = Ads::latest()->get();
          
         

        }
        $favourites = Favourite::select('ads_id')->where('user_id', $user->id)->get();

        if(count($ads) == 0){
          
          $data[ 'ads' ] = null;
            
          return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans('api.no_ads_exist'), 'data' => $data]);

        }

        // custom data shape
        $data          = [];
        $data[ 'ads' ] = [];
        $name          = 'name_' . App ::getLocale();

        foreach ( $ads as $ad ) {

            $c                      = collect();
            $c[ 'id' ]              = $ad -> id;
            $c[ 'name' ]            = $ad -> $name;
            $c[ 'lat' ]             = $ad -> lat;
            $c[ 'lng' ]             = $ad -> lng;
            $c[ 'desc' ]            = $ad -> desc;
            $c[ 'cost' ]            = $ad -> cost;
            $c[ 'area' ]            = $ad -> area;
            $c[ 'area_number' ]     = $ad -> area_number;
            $c[ 'block_number' ]    = $ad -> block_number;
            $c[ 'tax' ]             = $ad -> tax;
            $c[ 'is_rent' ]         = $ad -> is_rent;
            $c[ 'no_tax' ]          = $ad -> no_tax;
            $c[ 'image' ]           = url( 'dashboard/uploads/adss/' . $ad -> image[ 0 ] -> image );
            $c['favourite']         = 'no';
            
            foreach($favourites as $favourite){
                if($ad->id == $favourite->ads_id){
                    $c['favourite'] = 'yes';
                    break;
                }
            }
            $data[ 'ads' ][] = $c;
        }

        
        return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );


    }
  }
