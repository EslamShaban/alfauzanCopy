<?php

	namespace App\Http\Controllers\dashboard;

	use App\Http\Controllers\Controller;
	use App\Models\Repair;
	use App\Models\RepairType;
	use Illuminate\Http\Request;
	use Session;

	class RepairController extends Controller
	{

		/************ all repair orders ***********/

		public function repairs()
		{
			$repairs = Repair::latest()->paginate( 40 );

			return view( 'dashboard.repairs.orders', compact( 'repairs' ) );

		}

		/************ delete repair order ***********/

		public function deleteRepairOrder( Request $request )
		{
			//check if repair order is exist
			$order = Repair::findOrFail( $request->id );

			$order->delete();

			Session::flash( 'success', 'تم حذف طلب الصيانة' );
			return back();

		}

		/************  repair types ***********/

		public function repairTypes()
		{

			//get all repair types
			$types = RepairType::latest()->get();

			return view( 'dashboard.repairs.repair_types', compact( 'types' ) );
		}

		/************  add repair types ***********/

		public function addRepairType( Request $request )
		{

			$this->validate( $request,
			                 [
				                 'name_ar' => 'required',
				                 'name_en' => 'required',
			                 ], [
				                 'name_ar.required' => 'ادخل الاسم العربى',
				                 'name_en.required' => 'ادخل الاسم بالانجليزى',
			                 ] );

			//insert in database
			$type          = new RepairType();
			$type->name_ar = $request->name_ar;
			$type->name_en = $request->name_en;
			$type->save();

			Session::flash( 'success', 'تم اضافة الصيانة' );
			return back();
		}

		/************  update repair types ***********/

		public function updateRepairType( Request $request )
		{

			//check if repair type exist
			$type = RepairType::findOrFail( $request->id );

			//arabic name
			if ($request->edit_name_ar) {
				$type->name_ar = $request->edit_name_ar;
			}

			//english name
			if ($request->edit_name_en) {
				$type->name_en = $request->edit_name_en;
			}

			$type->save();

			Session::flash( 'success', 'تم تعديل الصيانة' );
			return back();
		}

		/************  delete repair types ***********/

		public function deleteRepairType(Request $request)
		{

			//check if repair type exist
			$type = RepairType::findOrFail( $request->id );

			//delete repair order for this type
            Repair::where('type_id',$request->id)->delete();

			$type->delete();

			Session::flash( 'success', 'تم حذف الصيانة' );
			return back();
		}
	}
