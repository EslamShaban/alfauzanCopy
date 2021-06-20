<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class RepairTypeTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'repair_type', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'name_ar' );
				$table->string( 'name_en' );
//				$table->softDeletes();
				$table->timestamps();
			} );
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema::dropIfExists( 'repair_type' );
		}
	}
