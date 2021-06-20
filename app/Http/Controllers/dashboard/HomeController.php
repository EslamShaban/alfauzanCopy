<?php

	namespace App\Http\Controllers\dashboard;

	use App\Http\Controllers\Controller;
	use App\Models\Ads;
	use App\Models\Project;
	use App\Models\Auction;
	use App\Models\Branch;
	use App\Models\Banner;
	use App\Models\Job;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\File;
	use Session;

	class HomeController extends Controller
	{

		/************ Job applicants ***********/

		public function jobs()
		{
			$jobs = Job ::latest() -> get();
			//            return $jobs;
			return view( 'dashboard.jobs.index', compact( 'jobs' ) );

		}

		/************ delete job ***********/

		public function deleteJob( Request $request )
		{
			//check if job exist
			$job = Job ::findOrFail( $request -> id );
			File ::delete( 'dashboard/uploads/cv/' . $job -> cv );

			$job -> delete();

			Report( Auth ::user() -> id, 'بحذف طلب العمل الخاص ب' . $job -> name );
			Session ::flash( 'success', 'تم حذف طلب العمل' );
			return redirect( route( 'jobs' ) );

		}

		/************ Ad info ***********/

		public function adInfo( $id )
		{

			//get ad info
			$ad = Ads ::findOrFail( $id );

			//calc total cost
			$ad -> total_cost
				= $ad -> cost + (float)( $ad -> cost * $ad -> tax / 100.00 ) + (float)( $ad -> cost * $ad -> vat / 100.00 );


			return view( 'site.ad_info', compact( 'ad' ) );

		}

		/************ all branches ***********/
		public function branches()
		{
			$branches = Branch ::latest() -> get();
			//return $categories;
			return view( 'dashboard.branches.index', compact( 'branches' ) );

		}

		/************ add branch ***********/
		public function addBranch( Request $request )
		{
			$this -> validate( $request,
			                   [
				                   'name_ar'  => 'required|max:100',
				                   'name_en'  => 'required|max:100',
				                   'location' => 'required',
				                   'lat'      => 'required|max:50',
				                   'lng'      => 'required|max:50',
				                   'start_at' => 'required',
				                   'end_at'   => 'required',
			                   ], [
				                   'name_ar.required'  => 'ادخل الاسم العربى',
				                   'name_en.required'  => 'ادخل الاسم بالانجليزى',
				                   'location.required' => 'ادخل العنوان',
				                   'lat.required'      => 'ادخل العنوان',
				                   'lng.required'      => 'ادخل العنوان',
				                   'start_at.required' => 'ادخل وقت بدأ الدوام',
				                   'end_at.required'   => 'ادخل وقت انتهاء الدوام',
			                   ] );

			$branch             = new Branch();
			$branch -> name_en  = $request -> name_en;
			$branch -> name_ar  = $request -> name_ar;
			$branch -> location = $request -> location;
			$branch -> lat      = $request -> lat;
			$branch -> lng      = $request -> lng;
			$branch -> start_at = $request -> start_at;
			$branch -> end_at   = $request -> end_at;
			$branch -> save();

			Report( Auth ::user() -> id, 'بأضافة فرع جديد' );
			Session ::flash( 'success', 'تم إضافة الفرع' );
			return back();

		}

		/************ update branch ***********/
		public function updateBranch( Request $request )
		{

			//			return $request->all();
			$branch = Branch ::findOrFail( $request -> id );


			if ( $request -> has( 'edit_name_ar' ) && $request -> edit_name_ar != null ) {
				$branch -> name_ar = $request -> edit_name_ar;
			}
			if ( $request -> has( 'edit_name_en' ) && $request -> edit_name_en != null ) {
				$branch -> name_en = $request -> edit_name_en;
			}
			if ( $request -> has( 'edit_location' ) && $request -> edit_location != null ) {
				$branch -> location = $request -> edit_location;
			}
			if ( $request -> has( 'edit_lat' ) && $request -> edit_lat != null ) {
				$branch -> lat = $request -> edit_lat;
			}
			if ( $request -> has( 'edit_lng' ) && $request -> edit_lng != null ) {
				$branch -> lng = $request -> edit_lng;
			}
			if ( $request -> has( 'edit_start_at' ) && $request -> edit_start_at != null ) {
				$branch -> start_at = $request -> edit_start_at;
			}
			if ( $request -> has( 'edit_end_at' ) && $request -> edit_end_at != null ) {
				$branch -> end_at = $request -> edit_end_at;
			}

			$branch -> save();

			Report( Auth ::user() -> id, 'بتحديث بيانات فرع ' . $branch -> name_ar );
			Session ::flash( 'success', 'تم تحديث بيانات الفرع' );
			return back();
		}

		/************ delete branch ***********/
		public function deleteBranch( Request $request )
		{
			$branch = Branch ::findOrFail( $request -> id );


			$branch -> delete();

			Report( Auth ::user() -> id, 'بحذف فرع  ' . $branch -> name_ar );
			Session ::flash( 'success', 'تم حذف الفرع' );
			return back();
		}

		/************ show auction form ***********/
		public function showAuction()
		{
			$projects = Project ::select( 'id', 'name_ar' ) -> get();
			return view( 'site.auction', compact( 'projects' ) );
		}

		/************ show auction form ***********/
		public function auction( Request $request )
		{

			$auction                      = new Auction();
			$auction -> build_type        = json_encode( $request -> build_type );
			$auction -> project_name      = $request -> project_name;
			$auction -> area              = $request -> area;
			$auction -> activity          = $request -> activity;
			$auction -> user_type         = json_encode( $request -> user_type );
			$auction -> client_phone      = $request -> client_phone;
			$auction -> company_rout      = $request -> company_rout;
			$auction -> received_employ   = $request -> received_employ;
			$auction -> employ_job        = $request -> employ_job;
			$auction -> employ_phone      = $request -> employ_phone;
			$auction -> receive_date      = Carbon ::parse( $request -> receive_date );
			$auction -> auction_available = $request -> auction_available;
			$auction -> order_status      = $request -> order_status;
			$auction -> details           = $request -> details;
			$auction -> save();
			return redirect( '/auction' );
		}

		/************ Job applicants ***********/

		public function auctions()
		{
			$auctions = Auction ::latest() -> get();
			//            return $jobs;
			return view( 'dashboard.auctions.index', compact( 'auctions' ) );

		}

		/************ delete auction ***********/

		public function deleteAuction( Request $request )
		{
			//check if job exist
			$auction = Auction ::findOrFail( $request -> id );
			$auction -> delete();

			Report( Auth ::user() -> id, 'بحذف طلب العميل' );
			Session ::flash( 'success', 'تم حذف طلب العمل' );
			return back();

		}

        /************ banners ***********/

        public function banners()
        {
            $banners = Banner::latest()->get();
            return view( 'dashboard.banners.index', compact( 'banners' ) );
        }

        /************  add repair types ***********/

        public function addBanner( Request $request )
        {

            $this->validate( $request,
                [
                    'url' => 'required',
                    'image'          => 'required|mimes:jpg,jpeg,png,bmp,tiff,svg |max:4096',
                ], [
                    'url.required' => 'ادخل الاسم العربى',
                    'image.required'       => 'ادخل الصورة',
                    'image.image'          => 'صيغة الصورة خاطئة',
                    'image.max'          => 'لا يجب ان يتجاوز حجم الصورة ٤ ميجا',
                ] );

            //insert in database
            $banner          = new Banner();
            $banner->image = upload_file($request->image,'dashboard/uploads/banners');
            $banner->url = $request->url;
            $banner->save();

            Session::flash( 'success', 'تم اضافة البنر الاعلاني' );
            return back();
        }

        /************  update banner***********/

        public function updateBanner( Request $request )
        {

            $banner = Banner::findOrFail( $request->id );

            if ($request->url) {
                $banner->url = $request->url;
            }


            if ($request->image) {
                $banner->image = upload_file($request->image,'dashboard/uploads/banners');
            }

            $banner->save();

            Session::flash( 'success', 'تم تعديل البنر الاعلاني' );
            return back();
        }

        /************  delete banner ***********/

        public function deleteBanner(Request $request)
        {

            $banner = Banner::findOrFail( $request->id );
            $banner->delete();

            Session::flash( 'success', 'تم حذف البنر الاعلاني' );
            return back();
        }


	}
