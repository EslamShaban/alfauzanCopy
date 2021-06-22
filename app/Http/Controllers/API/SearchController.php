<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use App\Models\Ads;
    use App\Models\AdsCategory;
    use App\Models\Category;
    use App\Models\Project;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\App;
    use Validator;

    class SearchController extends Controller
    {
        public function __construct( Request $request )
        {
            /** Set Lang **/
            $request -> header( 'lang' ) == 'en' ? App ::setLocale( 'en' ) : App ::setLocale( 'ar' );
            /** Set Carbon Language **/
            $request -> header( 'lang' ) == 'en' ? Carbon ::setLocale( 'en' ) : Carbon ::setLocale( 'ar' );

        }

        /******************* Search  *******************/

        public function search( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'keyword' => 'required',
            ] );

            if ( $validator -> passes() ) {

                if(!$request->is_rent)
                    $request['is_rent'] = 0;

                // get all ads result
                $ads = Ads ::with( 'image', 'category', 'user')
                           -> where( 'active', 1 )
                           -> where( function ( $query ) use ( $request ) {
                               $query -> where( 'name_ar', 'like', '%' . $request -> keyword . '%' )
                                      -> orWhere( 'name_en', 'like', '%' . $request -> keyword . '%' );

                            } )
                           -> latest()
                           -> paginate( 10 );


                
                $name          = 'name_' . App ::getLocale();
                $data          = [];
                $data[ 'ads' ] = [];
                foreach ( $ads as $ad ) {

                    $c            = collect();
                    $c[ 'id' ]    = $ad -> id;
                    $c[ 'name' ]  = $ad -> $name;
                    $c[ 'desc' ]  = $ad -> desc;
                    $c[ 'cost' ]  = $ad -> cost;
                    $c[ 'area' ]  = $ad -> area;
                    $c[ 'area_number' ]  = $ad -> area_number;
                    $c[ 'block_number' ]  = $ad -> block_number;
                    $c[ 'tax' ]  = $ad -> tax;
                    $c[ 'vat' ]  = $ad -> vat;
                    $c[ 'is_rent' ]  = $ad -> is_rent;
                    $c[ 'no_tax' ]  = $ad -> no_tax;
                    $c[ 'details' ]  = $ad -> details;
                    $c[ 'lat' ]  = $ad -> lat;
                    $c[ 'lng' ]  = $ad -> lng;
                    $c[ 'author_id' ]  = $ad -> user_id;
                    $c[ 'author_phone' ]  = $ad->user->phone ;
                    $c[ 'image' ] = url( 'dashboard/uploads/adss/' . $ad -> image[ 0 ] -> image );

                    //check if ads is offer
                    if ( $ad -> category_id ) {

                        $c[ 'category_id' ] = $ad -> category -> id;
                        $c[ 'category' ]    = $ad -> category -> $name;
                    } else {

                        $c[ 'category_id' ] = $ad -> adsCategory -> id;
                        $c[ 'category' ]    = $ad -> adsCategory -> $name;
                    }

                    $data[ 'ads' ][] = $c;
                }

                // make paginate instance
                $c                    = collect();
                $c[ 'total' ]         = $ads -> total();
                $c[ 'count' ]         = $ads -> count();
                $c[ 'per_page' ]      = 10;
                $c[ 'next_page_url' ] = $ads -> nextPageUrl();
                $c[ 'prev_page_url' ] = $ads -> previousPageUrl();
                $c[ 'current_page' ]  = $ads -> currentPage();
                $c[ 'total_pages' ]   = $ads -> lastPage();
                $data[ 'paginate' ]   = $c;

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

            }//if validation fail
            else {

                foreach ( (array)$validator -> errors() as $error ) {

                    if ( isset( $error[ 'keyword' ] ) ) {
                        $msg = trans( 'api.missing_data' );
                    } else {
                        $msg = trans( 'api.error' );
                    }

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }

        }
        /******************* main ads *******************/

        public function mainAds( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'category' => 'required',
                'lat'      => 'required',
                'lng'      => 'required',
            ] );

            if ( $validator -> passes() ) {

                if(!$request->is_rent)
                    $request['is_rent'] = 0;

                //check if category exist if it not 0=>all categories
                if ( $request -> category != 0 || !is_numeric( $request -> category ) ) {

                    $cat = Category ::find( $request -> category );
                    if ( !$cat )
                        return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                     'msg'   => trans( 'api.cat_not_exist' ) ] );
                }


                //get all projects near to user
                $dist     = 9999999999;
                $projects = Project ::query();
                $projects->whereHas('ads',function ($q) use ($request){
                    $q->where('is_rent',$request->is_rent);
                });

                //get based on category
                if ( $request -> category != 0 )
                    $projects = $projects -> whereHas( 'categories', function ( $q ) use ( $request ) {
                        $q -> where( 'category_id', $request -> category );
                    } );


                $projects =$projects -> selectRaw(
                    '*, ( 6367 * acos( cos( radians(' . $request -> lat . ') )
										* cos( radians( lat ) ) * cos( radians( lng )
										- radians(' . $request -> lng . ') ) + sin( radians(' . $request -> lat . ') )
										* sin( radians( lat ) ) ) ) AS distance' )
                          -> having( 'distance', '<', $dist )
                          -> orderBy( 'distance' );


                $projects_id = $projects -> get();
                //				$projects_id = $projects -> pluck( 'id' ) -> toArray();

                //get all projects in this range
                //				$projects = Project ::whereIn( 'id', $projects_id ) -> latest() -> get();

                // custom data shape
                $data               = [];
                $data[ 'projects' ] = [];
                $name               = 'name_' . App ::getLocale();

                foreach ( $projects_id as $p ) {

                    $c               = collect();
                    $c[ 'id' ]       = $p -> id;
                    $c[ 'name' ]     = $p -> $name;
                    $c[ 'lat' ]      = $p -> lat;
                    $c[ 'lng' ]      = $p -> lng;
                    $c[ 'category' ] = $p -> categories[0] -> $name;


                    $data[ 'projects' ][] = $c;
                }

                // make paginate instance
                //				$c                    = collect();
                //				$c[ 'total' ]         = $projects->total();
                //				$c[ 'count' ]         = $projects->count();
                //				$c[ 'per_page' ]      = 10;
                //				$c[ 'next_page_url' ] = $projects->nextPageUrl();
                //				$c[ 'prev_page_url' ] = $projects->previousPageUrl();
                //				$c[ 'current_page' ]  = $projects->currentPage();
                //				$c[ 'total_pages' ]   = $projects->lastPage();
                //				$data[ 'paginate' ]   = $c;
                //				$projects = $projects->latest()->paginate( 10 );


                return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

            }//if validation faild
            else {

                foreach ( (array)$validator -> errors() as $error ) {

                    if ( isset( $error[ 'category' ] ) ) {
                        $msg = trans( 'api.cat_req' );
                    } elseif ( isset( $error[ 'lat' ] ) ) {
                        $msg = trans( 'api.lat_req' );
                    } elseif ( isset( $error[ 'lng' ] ) ) {
                        $msg = trans( 'api.lng_req' );
                    } else {
                        $msg = trans( 'api.error' );
                    }

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }

        }

        /******************* all ads *******************/

        public function allAds( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'category' => 'required',
            ] );

            if ( $validator -> passes() ) {

                if(!$request->is_rent)
                    $request['is_rent'] = 0;

                //check if category exist if it not 0=>all categories
                if ( $request -> category != 0 || !is_numeric( $request -> category ) ) {

                    $cat = Category ::find( $request -> category );
                    if ( !$cat )
                        return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                     'msg'   => trans( 'api.cat_not_exist' ) ] );
                }

                //get all projects
                if ( $request -> category == 0 )
                    $projects = Project ::whereHas('ads',function ($q) use ($request){
                        $q->where('is_rent',$request['is_rent']);
                    })->latest() -> paginate( 10 );

                else
                    $projects = Project ::whereHas( 'categories', function ( $q ) use ( $request ) {
                        $q -> where( 'category_id', $request -> category );
                    } )->whereHas('ads',function ($q) use ($request){
                        $q->where('is_rent',$request->is_rent);
                    }) -> latest() -> get();

                // custom data shape
                $data               = [];
                $data[ 'projects' ] = [];
                $name               = 'name_' . App ::getLocale();

                foreach ( $projects as $p ) {

                    $c               = collect();
                    $c[ 'id' ]       = $p -> id;
                    $c[ 'name' ]     = $p -> $name;
                    $c[ 'lat' ]      = $p -> lat;
                    $c[ 'lng' ]      = $p -> lng;
                    $c[ 'category' ] = $p -> categories[0] -> $name;

                    $data[ 'projects' ][] = $c;
                }

                // make paginate instance
                //				$c                    = collect();
                //				$c[ 'total' ]         = $projects->total();
                //				$c[ 'count' ]         = $projects->count();
                //				$c[ 'per_page' ]      = 10;
                //				$c[ 'next_page_url' ] = $projects->nextPageUrl();
                //				$c[ 'prev_page_url' ] = $projects->previousPageUrl();
                //				$c[ 'current_page' ]  = $projects->currentPage();
                //				$c[ 'total_pages' ]   = $projects->lastPage();
                //				$data[ 'paginate' ]   = $c;
                //				$projects = $projects->latest()->paginate( 10 );


                return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

            }//if validation faild
            else {

                foreach ( (array)$validator -> errors() as $error ) {

                    if ( isset( $error[ 'category' ] ) ) {
                        $msg = trans( 'api.cat_req' );
                    } else {
                        $msg = trans( 'api.error' );
                    }

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }

        }

        public function ads()
        {
            
            $ads = Ads::latest()->get();

            if(count($ads) == 0){
                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans('api.no_ads_exist')]);

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
                $c[ 'image' ]    = url( 'dashboard/uploads/adss/' . $ad -> image[ 0 ] -> image );

                $data[ 'ads' ][] = $c;
            }

            
            return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );


        }


        /******************* all offer *******************/

        public function offerAds( Request $request )
        {

            //make validation
            $validator = Validator ::make( $request -> all(), [
                'category' => 'required',
            ] );

            if ( $validator -> passes() ) {

                //check if category exist if it not 0=>all categories
                if ( $request -> category != 0 || !is_numeric( $request -> category ) ) {

                    $cat = Category ::find( $request -> category );
                    if ( !$cat )
                        return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                     'msg'   => trans( 'api.cat_not_exist' ) ] );
                }

                //get all ads
                if ( $request -> category == 0 )
                    $ads = Ads ::where( 'offer', 1 ) -> where( 'active', 1 ) -> latest() -> get();
                else
                    $ads = Ads ::where( 'category_id', $request -> category )
                               -> where( 'offer', 1 )
                               -> where( 'active', 1 )
                               -> latest() -> get();

                // custom data shape
                $data          = [];
                $data[ 'ads' ] = [];
                $name          = 'name_' . App ::getLocale();

                foreach ( $ads as $ad ) {

                    $c               = collect();
                    $c[ 'id' ]       = $ad -> id;
                    $c[ 'name' ]     = $ad -> $name;
                    $c[ 'lat' ]      = $ad -> lat;
                    $c[ 'lng' ]      = $ad -> lng;
                    $c[ 'category' ] = $ad -> category -> $name;
                    $c[ 'desc' ]     = $ad -> desc;
                    $c[ 'image' ]    = url( 'dashboard/uploads/adss/' . $ad -> image[ 0 ] -> image );

                    $data[ 'ads' ][] = $c;
                }

                // make paginate instance
                //				$c                    = collect();
                //				$c[ 'total' ]         = $ads->total();
                //				$c[ 'count' ]         = $ads->count();
                //				$c[ 'per_page' ]      = 10;
                //				$c[ 'next_page_url' ] = $ads->nextPageUrl();
                //				$c[ 'prev_page_url' ] = $ads->previousPageUrl();
                //				$c[ 'current_page' ]  = $ads->currentPage();
                //				$c[ 'total_pages' ]   = $ads->lastPage();
                //				$data[ 'paginate' ]   = $c;
                //				$projects = $projects->latest()->paginate( 10 );


                return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

            }//if validation faild
            else {

                foreach ( (array)$validator -> errors() as $error ) {

                    if ( isset( $error[ 'category' ] ) ) {
                        $msg = trans( 'api.cat_req' );
                    } else {
                        $msg = trans( 'api.error' );
                    }

                    return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => $msg ] );
                }
            }

        }


        /******************* Get today ads *******************/

        public
        function todayAds( Request $request )
        {

            //get today ads
            $name = 'name_' . App ::getLocale();


            $ads = Ads ::with( 'image', 'category' )
                       -> where( 'active', 1 )
//                       -> whereDate( 'created_at', Carbon ::today() )
                       -> orderBy( 'id', 'desc' )
                       -> where( 'offer', 0)
                       -> paginate( 10 );


            $data          = [];
            $data[ 'ads' ] = [];
            foreach ( $ads as $ad ) {

                $c            = collect();
                $c[ 'id' ]    = $ad -> id;
                $c[ 'name' ]  = $ad -> $name;
                $c[ 'desc' ]  = $ad -> desc;
                $c[ 'image' ] = url( 'dashboard/uploads/adss/' . $ad -> image[ 0 ] -> image );
                //check if ads is offer
                if ( $ad -> category_id ) {

                    $c[ 'category_id' ] = $ad -> category -> id;
                    $c[ 'category' ]    = $ad -> category -> $name;
                } else {

                    $c[ 'category_id' ] = $ad -> adsCategory -> id;
                    $c[ 'category' ]    = $ad -> adsCategory -> $name;
                }


                $data[ 'ads' ][] = $c;
            }

            // make paginate instance
            $c                    = collect();
            $c[ 'total' ]         = $ads -> total();
            $c[ 'count' ]         = $ads -> count();
            $c[ 'per_page' ]      = 10;
            $c[ 'next_page_url' ] = $ads -> nextPageUrl();
            $c[ 'prev_page_url' ] = $ads -> previousPageUrl();
            $c[ 'current_page' ]  = $ads -> currentPage();
            $c[ 'total_pages' ]   = $ads -> lastPage();
            $data[ 'paginate' ]   = $c;

            return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

        }

        /******************* Get project ads *******************/

        public
        function projectAds( Request $request )
        {
            //make validation
            $validator = Validator ::make( $request -> all(), [
                'project_id' => 'required',
                'category'   => 'required',
            ] );


            if ( $validator -> passes() ) {

                //check if category exist if it not 0=>all categories
                if ( $request -> category != 0 || !is_numeric( $request -> category ) ) {

                    $cat = AdsCategory ::find( $request -> category );

                    if ( !$cat )
                        return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                     'msg'   => trans( 'api.cat_not_exist' ) ] );
                }

                //check if category
                //check if project exist
                $project = Project ::find( $request -> project_id );
                if ( !$project )
                    return response() -> json( [ 'value' => '0', 'key' => 'fail',
                                                 'msg'   => trans( 'api.no_project' ) ] );

                //get all project ads
                if ( $request -> category == 0 )
                    $ads = Ads ::where( 'project_id', $request -> project_id ) -> where( 'active', 1 ) -> latest()
                               -> get();

                else
                    $ads = Ads ::where( 'ads_category_id', $request -> category )
                               -> where( 'project_id', $request -> project_id )
                               -> where( 'active', 1 )
                               -> latest() -> get();


                $name          = 'name_' . App ::getLocale();
                $data          = [];
                $data[ 'ads' ] = [];
                foreach ( $ads as $ad ) {

                    $c                  = collect();
                    $c[ 'id' ]          = $ad -> id;
                    $c[ 'name' ]        = $ad -> $name;
                    $c[ 'desc' ]        = $ad -> desc;
                    $c[ 'lat' ]         = $ad -> lat;
                    $c[ 'lng' ]         = $ad -> lng;
                    $c[ 'image' ]       = url( 'dashboard/uploads/adss/' . $ad -> image[ 0 ] -> image );
                    $c[ 'category_id' ] = $ad -> adsCategory -> id;
                    $c[ 'category' ]    = $ad -> adsCategory -> $name;

                    $data[ 'ads' ][] = $c;
                }

                // make paginate instance
                //				$c                    = collect();
                //				$c[ 'total' ]         = $ads->total();
                //				$c[ 'count' ]         = $ads->count();
                //				$c[ 'per_page' ]      = 10;
                //				$c[ 'next_page_url' ] = $ads->nextPageUrl();
                //				$c[ 'prev_page_url' ] = $ads->previousPageUrl();
                //				$c[ 'current_page' ]  = $ads->currentPage();
                //				$c[ 'total_pages' ]   = $ads->lastPage();
                //				$data[ 'paginate' ]   = $c;

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

            }//if validation fail
            else {

                return response() -> json( [ 'value' => '0', 'key' => 'fail', 'msg' => trans( 'api.missing_data' ) ] );

            }
        }
        
                public function ads_category()
        {
            
            $name          = 'name_' . App ::getLocale();

            $ads_category = Category::select($name)->get();

             $categories = $ads_category->unique($name)->values();

             $data = [];
             $data['categories'] = $categories;

            if(count($categories) == 0){
                
                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.no_category_exist' ) ] );

            }

            return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );


        }

        public function filter_ads(Request $request)
        {

            $data = [];
            $ads = Ads::query();

            
            if($request->has('category_id') && $request->category_id != null){
                $ads->where('category_id', $request->category_id);
            }

            if($request->has('price_min') && $request->price_min != null ){
                $ads->where('cost', '>=', $request->price_min);
            }

            if($request->has('price_max') && $request->price_max != null ){
                $ads->where('cost', '<=', $request->price_max);
            }

            if($request->has('area_min') && $request->area_min != null ){
                $ads->where('area', '>=', $request->area_min);
            }

            if($request->has('area_max') && $request->area_max != null ){
                $ads->where('area', '<=', $request->area_max);
            }

            if($request->has('sort') && $request->sort != null ){

                if($request->sort == 'latest'){

                    $ads->latest();

                }else if($request->sort == 'asc' || $request->sort == 'desc'){
                    
                    $ads->orderBy('cost',  $request->sort);

                }
            }


            $ads = $ads->get();

           if(count($ads)==0){

                return response() -> json( [ 'value' => '1', 'key' => 'success', 'msg' => trans( 'api.no_ad' )  ] );

           }
            
            foreach($ads as $ad){
                
            $image_name = $ad->image()->first()->image ?? '';
            $ad['image'] = $image_name == '' ? '' : url('dashboard/uploads/adss/'. $image_name);


            }

						
            $data['ads'] = $ads;

            return response() -> json( [ 'value' => '1', 'key' => 'success', 'data' => $data ] );

        }
    }
