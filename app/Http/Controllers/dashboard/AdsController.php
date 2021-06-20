<?php

  namespace App\Http\Controllers\dashboard;

  use App\Http\Controllers\Controller;
  use App\Models\Ads;
  use App\Models\AdsCategory;
  use App\Models\Category;
  use App\Models\Chat;
  use App\Models\Project;
  use App\User;
  use File;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Image;
  use Session;

  //	use Intervention\Image\Facades\Image;

  class AdsController extends Controller
  {

    /************ ordnary ads ***********/

    public function ads()
    {
      $ads = Ads::where('offer', 0)->latest()->get();

      //get  ordinary  categories
      $categories = AdsCategory::get();

      //get all projects
      $projects = Project::get();


      return view('dashboard.ads.ads', compact('ads', 'projects', 'categories'));

    }

    /************ offerd ads ***********/

    public function offerAds()
    {
      $ads = Ads::where('offer', 1)->latest()->get();

      //get all categories
      $categories = Category::where('offer', 1)->get();

      //return $ads[0]->image;
      return view('dashboard.ads.offer', compact('ads', 'categories'));

    }


    /************ add ad ***********/

    public function addAd(Request $request)
    {
      //return $request->all();
      if (!$request->no_tax)
        $request['no_tax'] = 0;
      //make validation
      $this->validate($request,
        [
          'avatar'       => 'required',
          'avatar.*'     => 'required',
          'name_ar'      => 'required',
          'name_en'      => 'required',
          'cost'         => 'required',
          'area'         => 'required',
          'area_number'  => 'required|max:100',
          'block_number' => 'required|max:100',
          'is_rent'      => 'in:0,1',
          'no_tax'       => 'in:0,1',
          //					'code'      => 'required|unique:ads',
          'video'        => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
          'desc'         => 'required',
          'details'      => 'required',
          'lat'          => 'required',
          'lng'          => 'required',
        ], [
          'avatar.required'       => 'ادخل صور الاعلان',
          'avatar.image'          => 'صيغة صور الاعلان خاطئة',
          //				                   'avatar.size'       => 'الحجم الاقصى لصور الاعلان 2 ميجا',
          'name_ar.required'      => 'ادخل الاسم العربى',
          'name_en.required'      => 'ادخل الاسم بالانجليزى',
          'cost.required'         => 'ادخل السعر',
          'area.required'         => 'ادخل المساحة',
          'area_number.required'  => 'ادخل رقم القطعة',
          'block_number.required' => 'ادخل رقم البلوك',
          //'code.required'      => 'ادخل كود العرض',
          //'code.unique'        => 'كود العرض غير متاح',
          'video.required'        => 'ادخل رايط الفيديو',
          'video.mimetypes'       => 'رابط الفيديو غير صحيح',
          'video.regex'           => 'رابط الفيديو غير صحيح',
          'desc.required'         => 'ادخل الوصف',
          'details.required'      => 'ادخل التفاصيل',
          'lat.required'          => 'ادخل العنوان',
          'lng.required'          => 'ادخل العنوان',
          'category.required'     => 'اختر القسم',
          'category.exists'       => 'القسم غير موجود',
        ]);

      if (!$request->category && !$request->ads_category) {

        return back()->withErrors('اختار القسم');
      }

      //            //generate qr code
      //
      //            $code = generate_code() . time();
      //            QRCode ::text( $code ) -> setSize( 10 )
      //                   -> setOutfile( 'dashboard/uploads/ads_qr/' . $code . '.png' ) -> png();


      //add data to database
      $ad               = new Ads();
      $ad->name_en      = $request->name_en;
      $ad->name_ar      = $request->name_ar;
      $ad->cost         = $request->cost;
      $ad->area         = $request->area;
      $ad->area_number  = $request->area_number;
      $ad->block_number = $request->block_number;
      $ad->is_rent      = $request->is_rent;
      $ad->tax          = 5;
      $ad->vat          = 2.5;
      $ad->no_tax       = $request->no_tax;
      //            $ad -> code        = $code;
      //            $ad -> qr_image    = $code . '.png';
      $ad->desc        = $request->desc;
      $ad->details     = $request->details;
      $ad->lat         = $request->lat;
      $ad->lng         = $request->lng;
      $ad->category_id = $request->category;
      $ad->user_id     = Auth::id();


      if ($request->has('video') && $request->video != null)
        $ad->video = $request->video;

      if ($request->has('project') && $request->project != null)
        $ad->project_id = $request->project;

      if ($request->has('type') && $request->type != null)
        $ad->offer = 1;

      if ($request->has('category') && $request->category != null)
        $ad->category_id = $request->category;

      if ($request->has('ads_category') && $request->ads_category != null)
        $ad->ads_category_id = $request->ads_category;


      $ad->save();

      //add images to database

      if ($request->hasFile('avatar')) {


        $images          = $request->file('avatar');
        $destinationPath = 'dashboard/uploads/adss';

        foreach ($images as $im) {
          $imgName = str_random(10) . '.' . 'png';
          $imgs    = Image::make($im);
          $imgs->resize(400, 350)->save($destinationPath . '/' . $imgName);
          $photo         = new \App\Models\Image();
          $photo->ads_id = $ad->id;
          $photo->image  = $imgName;
          $photo->save();
        }
      } else {
        $ad->delete();
        Session::flash('danger', 'ادخل صور الاعلان');
        return back();

      }

      Report(Auth::user()->id, 'بأضافة اعلان جديد');

      Session::flash('success', 'تم إضافة الاعلان');
      return back();

    }

    /************ update ad ***********/

    public function updateAd(Request $request)
    {
      //            return $request->all();
      if (!$request->no_tax)
        $request['no_tax'] = 0;

      //make validation
      $this->validate($request,
        [
          'edit_video'        => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
          'edit_category'     => 'exists:categories,id',
          'edit_ads_category' => 'exists:ads_category,id',
          'is_rent'           => 'nullable|in:0,1',
          'no_tax'            => 'nullable|in:0,1',
        ], [
          'edit_video.regex'         => 'رابط الفيديو غير صحيح',
          'edit_category.exists'     => 'القسم غير موجود',
          'edit_ads_category.exists' => 'القسم غير موجود',
        ]);


      //check if project exist
      $ad = Ads::findOrFail($request->id);

      //arabic name
      if ($request->edit_name_ar) {
        $ad->name_ar = $request->edit_name_ar;
      }

      //english name
      if ($request->edit_name_en) {
        $ad->name_en = $request->edit_name_en;
      }

      //category
      if ($request->edit_category)
        $ad->category_id = $request->edit_category;

      //ads_category
      if ($request->edit_ads_category)
        $ad->ads_category_id = $request->edit_ads_category;

      //lat
      if ($request->edit_lat) {
        $ad->lat = $request->edit_lat;
      }

      //lng
      if ($request->edit_lng) {
        $ad->lng = $request->edit_lng;
      }

      //area
      if ($request->edit_area) {
        $ad->area = $request->edit_area;
      }

      //area
      if ($request->edit_is_rent == '1' || $request->edit_is_rent == '0') {
        $ad->is_rent = $request->edit_is_rent;
      }

      //has tax
      if ($request->edit_no_tax) {
        $ad->no_tax = $request->edit_no_tax;
      }

      //area number
      if ($request->edit_area_number) {
        $ad->area_number = $request->edit_area;
      }

      //block number
      if ($request->edit_block_number) {
        $ad->block_number = $request->edit_block_number;
      }

      //cost
      if ($request->edit_cost) {
        $ad->cost = $request->edit_cost;
      }

      //video
      if ($request->edit_video) {
        $ad->video = $request->edit_video;
      }

      //desc
      if ($request->edit_desc) {
        $ad->desc = $request->edit_desc;
      }

      //details
      if ($request->edit_details) {
        $ad->details = $request->edit_details;
      }

      //project
      if ($request->edit_project != null) {
        $ad->project_id = $request->edit_project;
      }

      $ad->save();

      Report(Auth::user()->id, 'بتحديث بيانات اعلان ' . $ad->name_ar);
      Session::flash('success', 'تم حفظ الاعلان');
      return back();
    }


    /************ all image ***********/

    public function images($id)
    {
      //check id ad is exist
      $ad = Ads::findOrFail($id);

      $images = \App\Models\Image::where('ads_id', $ad->id)->get();

      return view('dashboard.ads.images', compact('images', 'ad'));
    }

    /************ add image ***********/

    public function addImage(Request $request)
    {
      //return $request->all();
      $ad = Ads::findOrFail($request->ad_id);
      //			dd($request->all());

      if ($request->hasFile('avatar')) {

        //set destination path
        $images = $request->avatar;

        foreach ($images as $im) {
          $imgName = str_random(10) . '.' . 'png';
          $imgs    = Image::make($im)->save('dashboard/uploads/adss/' . $imgName);

          $photo         = new \App\Models\Image();
          $photo->ads_id = $ad->id;
          $photo->image  = $imgName;
          $photo->save();
        }

        Session::flash('success', 'تم إضافة  صور الاعلان');
        return back();
      }
    }

    /************ delete image ***********/

    public function deleteImage(Request $request)
    {
      //check if image exist
      $img = \App\Models\Image::findOrFail($request->id);

      //check if it last image
      $imgs_num = \App\Models\Image::where('ads_id', $img->ads_id)->count();
      if ($imgs_num <= 1)
        return response()->json(['status' => 0]);

      File::delete('dashboard/uploads/adss/' . $img->image);
      $img->delete();
      return response()->json(['status' => 1]);
    }

    /************ delete ads ***********/
    public function deleteAd(Request $request)
    {
      //check if ad exist
      $ad = Ads::findOrFail($request->id);

      //delete project images
      $images = \App\Models\Image::where('ads_id', $ad->id)->get();
      $ad->delete();

      foreach ($images as $image) {
        File::delete('dashboard/uploads/adss/' . $image->image);
        $image->delete();
      }

      Report(Auth::user()->id, 'بحذف اعلان ' . $ad->name_ar);
      Session::flash('success', 'تم حذف الاعلان');
      return back();
    }

    /************ ad rooms ***********/
    public function rooms($id)
    {
      //check if ad exist
      $ad = Ads::findOrFail($id);


      //get chat rooms for this ads
      $chat_id = Chat::select(DB::raw('max(id) as lid'))
        ->where('ads_id', $id)
        ->where(function ($query) use ($ad) {
          $query->where('s_id', $ad->user_id)
            ->orWhere('r_id', $ad->user_id);
        })
        ->groupBy('room')
        ->orderBy('lid', 'desc')
        ->pluck('lid')->toArray();


      $rooms = Chat::whereIn('id', $chat_id)->get();

      //count unread messages in each chat
      foreach ($rooms as $room) {
        $room->unread = Chat::where('r_id', Auth::id())
          ->where('room', $room->room)
          ->where('seen', 0)
          ->where('ads_id', $room->ads_id)
          ->count();
      }

      //			return $rooms;
      return view('dashboard.ads.chat', compact('rooms'));

    }

    /************ get room messages ***********/
    public function chat($room)
    {

      //make message seen
      Chat::where('room', $room)->where('r_id', Auth::id())->update(['seen' => 1]);

      //get all msgs in this room
      $msgs = Chat::where('room', $room)->get();

      //set every msg if it sent or recived
      foreach ($msgs as $msg) {
        if ($msg->s_id == Auth::id())
          $msg->type = 'out';
        else
          $msg->type = 'in';
      }

      $msgs = view('dashboard.ads.msgs', compact('msgs'))->render();
      return response($msgs);
    }

    /************ send messages ***********/
    public function send(Request $request)
    {

      //make validation
      //			$this->validate( $request, [
      //				'other_id' => 'required',
      //				'ads_id'   => 'required',
      //				'message'  => 'required',
      //				'room'     => 'required',
      //			] );

      //check if ads exist
      Ads::findorFail($request->ads_id);

      //check if other user is exist
      User::findOrFail($request->other_id);

      //send message
      $msg          = new Chat();
      $msg->message = $request->message;
      $msg->room    = $request->room;
      $msg->s_id    = Auth::id();
      $msg->r_id    = $request->other_id;
      $msg->ads_id  = $request->ads_id;
      $msg->save();

      $msg = view('dashboard.ads.msgs', compact('msg'))->render();
      return response($msg);
    }


    /****************** change ad status ******************/
    public function changeStatus(Request $request)
    {

      $ad = Ads::find($request->id);

      if (!$ad) {
        return response()->json(['status' => 0]);
      }


      $ad->active = $request->status;
      $ad->save();

      return response()->json(['status' => 1]);
    }
  }
