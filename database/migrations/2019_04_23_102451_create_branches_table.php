<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class CreateBranchesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema ::create( 'branches', function ( Blueprint $table ) {

				$table -> increments( 'id' );
				$table -> string( 'name_ar', 100 );
				$table -> string( 'name_en', 100 );
				$table -> text( 'location' );
				$table -> string( 'lat', 100 );
				$table -> string( 'lng', 100 );
				$table -> time( 'start_at' );
				$table -> time( 'end_at' );
				$table -> timestamps();
			} );
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema ::dropIfExists( 'branches' );
		}
	}
