<?php

    namespace App\Http\Controllers\dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\Category;
    use App\Models\AdsCategory;
    use App\Models\Project;
    use App\Models\Ads;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;

    class CategoryController extends Controller
    {
        /************ all categories ***********/
        public function categories()
        {
            $categories = Category ::latest() -> get();
            //return $categories;
            return view( 'dashboard.categories.index', compact( 'categories' ) );

        }

        /************ add categories ***********/
        public function addCategory( Request $request )
        {
            //dd( $request);
            $this -> validate( $request,
                               [
                                   'name_ar' => 'required',
                                   'name_en' => 'required',
                                   'type'    => 'required',
                               ], [
                                   'name_ar.required' => 'ادخل الاسم العربى',
                                   'name_en.required' => 'ادخل الاسم بالانجليزى',
                                   'type.required'    => 'ادخل نوع القسم',
                               ] );

            $category            = new Category();
            $category -> name_en = $request -> name_en;
            $category -> name_ar = $request -> name_ar;
            $category -> offer   = $request -> type;
            $category -> save();

            Report( Auth ::user() -> id, 'بأضافة قسم جديد' );
            Session ::flash( 'success', 'تم إضافة القسم' );
            return back();

        }

        /************ update categories ***********/
        public function updateCategory( Request $request )
        {

            //			return $request->all();
            $category = Category ::findOrFail( $request -> id );


            if ( $request -> has( 'edit_name' ) && $request -> edit_name != null ) {
                $category -> name_ar = $request -> edit_name;
            }
            if ( $request -> has( 'edit_name_en' ) && $request -> edit_name_en != null ) {
                $category -> name_en = $request -> edit_name_en;
            }
            if ( $request -> has( 'edit_type' ) && $request -> edit_type != null ) {
                $category -> offer = $request -> edit_type;
            }

            $category -> save();

            Report( Auth ::user() -> id, 'بتحديث بيانات قسم ' . $category -> name_ar );
            Session ::flash( 'success', 'تم حفظ القسم' );
            return back();
        }

        /************ delete categories ***********/
        public function deleteCategory( Request $request )
        {
            $category = Category ::findOrFail( $request -> id );


            $category -> delete();

            Report( Auth ::user() -> id, 'بحذف القسم ' . $category -> name_ar );
            Session ::flash( 'success', 'تم حذف القسم' );
            return back();
        }

        /************ all projects ***********/

        public function projects()
        {
            $projects = Project ::with( 'category', 'categories' ) -> latest() -> get();

            //get all categories
            $categories = Category ::where( 'offer', 0 ) -> get();
            //return $projects;
            return view( 'dashboard.ads.projects', compact( 'projects', 'categories' ) );

        }

        /************ add project ***********/

        public function addproject( Request $request )
        {


            //		    return $request->all();
            //make validation
            $this -> validate( $request, [
                'name_ar'    => 'required',
                'name_en'    => 'required',
                'category'   => 'required',
                'category.*' => 'exists:categories,id',
                'lat_add'    => 'required',
                'lng_add'    => 'required',
            ], [
                                   'name_ar.required'  => 'ادخل الاسم العربى',
                                   'name_en.required'  => 'ادخل الاسم بالانجليزى',
                                   'lat_add.required'  => 'ادخل العنوان',
                                   'lng_add.required'  => 'ادخل العنوان',
                                   'category.required' => 'اختر القسم',
                                   'category.*.exists' => 'القسم غير موجود',
                               ] );

            //add data to database
            $project                = new Project();
            $project -> name_en     = $request -> name_en;
            $project -> name_ar     = $request -> name_ar;
            $project -> lat         = $request -> lat_add;
            $project -> lng         = $request -> lng_add;
            $project -> category_id = $request -> category[ 0 ];
            $project -> save();

            $project -> categories() -> attach( $request -> category );

            Report( Auth ::user() -> id, 'بأضافة مخطط جديد' );
            Session ::flash( 'success', 'تم إضافة المخطط' );
            return back();

        }

        /************ update projects ***********/

        public function updateProject( Request $request )
        {


            //make validation
            $this -> validate( $request,
                               [
                                   'edit_category'   => 'required',
                                   'edit_category.*' => 'exists:categories,id',
                               ], [
                                   'edit_category.required' => 'القسم مطلوب',
                                   'edit_category.*.exists' => 'القسم غير موجود',
                               ] );

            //check if project exist
            $project = Project ::findOrFail( $request -> id );

            //arabic name
            if ( $request -> edit_name ) {
                $project -> name_ar = $request -> edit_name;
            }

            //english name
            if ( $request -> edit_name_en ) {
                $project -> name_en = $request -> edit_name_en;
            }

            //category
            if ( $request -> edit_category ) {
                $project -> category_id = $request -> edit_category[ 0 ];
            }

            //lat
            if ( $request -> edit_lat ) {
                $project -> lat = $request -> edit_lat;
            }

            //lng
            if ( $request -> edit_lng ) {
                $project -> lng = $request -> edit_lng;
            }

            $project -> save();


            $project -> categories() -> sync( $request -> edit_category );

            Report( Auth ::user() -> id, 'بتحديث بيانات مخطط  ' . $project -> name_ar );

            Session ::flash( 'success', 'تم حفظ المخطط' );
            return back();
        }

        /************ delete project ***********/
        public function deleteProject( Request $request )
        {
            //check if project exist
            $project = Project ::findOrFail( $request -> id );

            $project -> delete();

            Report( Auth ::user() -> id, 'بحذف مخطط ' . $project -> name_ar );
            Session ::flash( 'success', 'تم حذف المخطط' );
            return back();
        }

        /************** project cats **************/

        public function projectCats( Request $request )
        {

            $cats = AdsCategory ::where( 'project_id', $request -> project_id ) -> latest() -> get();

            $cats = view( 'dashboard.ads.project_categories_render', compact( 'cats' ) ) -> render();
            return response( $cats );
        }

        /************ all Ads Categories ***********/
        public function projectCategories()
        {
            $categories = AdsCategory ::latest() -> get();
            $projects   = Project ::get();

            return view( 'dashboard.categories.ads_category', compact( 'categories', 'projects' ) );

        }

        /************ add ads categories ***********/
        public function addProjectCategory( Request $request )
        {
            //dd( $request);
            $this -> validate( $request,
                               [
                                   'name_ar'    => 'required',
                                   'name_en'    => 'required',
                                   'project_id' => 'required',
                               ], [
                                   'name_ar.required'    => 'ادخل الاسم العربى',
                                   'name_en.required'    => 'ادخل الاسم بالانجليزى',
                                   'project_id.required' => 'اختر المخطط',
                               ] );

            $category               = new AdsCategory();
            $category -> name_en    = $request -> name_en;
            $category -> name_ar    = $request -> name_ar;
            $category -> project_id = $request -> project_id;
            $category -> save();

            Report( Auth ::user() -> id, 'بأضافة قسم فرعى جديد' );
            Session ::flash( 'success', 'تم إضافة القسم' );
            return back();

        }

        /************ update ads categories ***********/
        public function updateProjectCategory( Request $request )
        {

            //			return $request->all();
            $category = AdsCategory ::findOrFail( $request -> id );


            if ( $request -> has( 'edit_name' ) && $request -> edit_name != null ) {
                $category -> name_ar = $request -> edit_name;
            }
            if ( $request -> has( 'edit_name_en' ) && $request -> edit_name_en != null ) {
                $category -> name_en = $request -> edit_name_en;
            }
            if ( $request -> has( 'edit_project_id' ) && $request -> edit_project_id != null ) {
                $category -> project_id = $request -> edit_project_id;
            }

            $category -> save();

            Report( Auth ::user() -> id, 'بتحديث بيانات القسم الفرعى ' . $category -> name_ar );
            Session ::flash( 'success', 'تم حفظ القسم' );
            return back();
        }

        /************ delete ad categories ***********/
        public function deleteProjectCategory( Request $request )
        {
            $category = AdsCategory ::findOrFail( $request -> id );

            $category -> delete();

            Report( Auth ::user() -> id, 'بحذف القسم الفرعى ' . $category -> name_ar );
            Session ::flash( 'success', 'تم حذف القسم' );
            return back();
        }

    }
