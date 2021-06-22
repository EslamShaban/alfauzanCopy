<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class OrderTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'orders', function ( Blueprint $table )
			{
				$table->increments( 'id' );

				$table->integer( 'user_id' )->unsigned();
				$table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
				$table->integer( 'appoint_id' )->unsigned();
				$table->foreign( 'appoint_id' )->references( 'id' )->on( 'appointments' )->onDelete( 'cascade' );
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
			Schema::dropIfExists( 'orders' );
		}
	}
