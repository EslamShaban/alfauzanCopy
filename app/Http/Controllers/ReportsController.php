<?php

	namespace App\Http\Controllers;

	use App\Models\Report;
	use Session;

	class ReportsController extends Controller
	{
		#reports page
		public function ReportsPage()
		{
			$usersReports      = Report::with( 'User' )->latest()->paginate( 30 );
			$supervisorReports = Report::with( 'User.Role' )->latest()->paginate( 30 );
			//return $supervisorReports;
			return view( 'dashboard.reports.reports',
			             compact( 'usersReports', $usersReports, 'supervisorReports', $supervisorReports ) );
		}

		#delete users reports
		public function DeleteUsersReports()
		{
			$usersReports = Report::where( 'supervisor', '0' )->get();
			foreach ($usersReports as $r) {
				$r->delete();
			}
			Session::flash( 'success', 'تم الحذف' );
			return back();
		}

		#delete supervisors reports
		public function DeleteSupervisorsReports()
		{
			$supervisorReports = Report::where( 'supervisor', '1' )->get();
			foreach ($supervisorReports as $r) {
				$r->delete();
			}
			Session::flash( 'success', 'تم الحذف' );
			return back();
		}
	}
