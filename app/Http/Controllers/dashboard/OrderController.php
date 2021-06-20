<?php

	namespace App\Http\Controllers\dashboard;

	use App\Http\Controllers\Controller;
	use App\Models\Order;
	use App\Models\Reason;
	use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Session;

	class OrderController extends Controller
	{
		/************ all orders  ***********/

		public function orders()
		{
//			$orders = Order::latest()->get();
			$orders = Order::orderBy('id','DESC')->get();

			//return $orders;

			return view( 'dashboard.orders.index', compact( 'orders' ) );

		}

		/************ accept order ***********/

		public function acceptOrder( Request $request )
		{
			//check if order exist
			$order = Order::findOrFail( $request->id );

			$order->accept = 1;
			$order->save();

            Report( Auth ::user() -> id, 'بقبول طلب الحجز على اعلان  ' . $order -> ads->name_ar );
			Session::flash( 'success', 'تم قبول الطلب' );
			return back();

		}

		/************ delete order ***********/

		public function refuseOrder( Request $request )
		{
			//check if order exist
			$order = Order::findOrFail( $request->id );

			$order->accept = -1;
			$order->save();

            Report( Auth ::user() -> id, 'برفض طلب الحجز على اعلان  ' . $order -> ads->name_ar );
			Session::flash( 'success', 'تم رفض الطلب' );
			return back();

		}

		/************ delete order ***********/

		public function deleteOrder( Request $request )
		{
			//check if order exist
			$order = Order::findOrFail( $request->id );
//			return $order;

			$order->delete();

			Report( Auth ::user() -> id, 'بحذف طلب الحجز' );
			Session::flash( 'success', 'تم حذف الطلب' );
			return back();

		}

		/************ all orders  reasons***********/

		public function reasons()
		{
			$reasons = Reason::get();

			//return $orders;

			return view( 'dashboard.orders.reasons', compact( 'reasons' ) );

		}

		/************  add reason ***********/

		public function addReason( Request $request )
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
			$reason          = new Reason();
			$reason->name_ar = $request->name_ar;
			$reason->name_en = $request->name_en;
			$reason->save();

            Report( Auth ::user() -> id, 'باضافة هدف للحجز  ' );
			Session::flash( 'success', 'تم اضافة هدف الحجز' );
			return back();
		}

		/************  update reason ***********/

		public function updateReason( Request $request )
		{

			//check if reason exist
			$reason = Reason::findOrFail( $request->id );

			//arabic name
			if ($request->edit_name_ar) {
				$reason->name_ar = $request->edit_name_ar;
			}

			//english name
			if ($request->edit_name_en) {
				$reason->name_en = $request->edit_name_en;
			}

			$reason->save();

            Report( Auth ::user() -> id, 'بتحديث بيانات هدف الحجز  ' . $reason -> name_ar );

			Session::flash( 'success', 'تم تعديل هدف الحجز' );
			return back();
		}

		/************  delete  reason ***********/

		public function deleteReason(Request $request)
		{

			//check if reason  exist
			$reason = Reason::findOrFail( $request->id );

			$reason->delete();

            Report( Auth ::user() -> id, 'بحذف هدف الحجز  ' . $reason -> name_ar );
			Session::flash( 'success', 'تم حذف هدف الحجز' );
			return back();
		}
	}
