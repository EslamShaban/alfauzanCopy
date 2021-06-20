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
				$table->string( 'name' );
				$table->string( 'phone' );
				$table->integer( 'accept' )->default(0);
				$table->integer( 'ads_id' )->unsigned();
				$table->foreign( 'ads_id' )->references( 'id' )->on( 'ads' )->onDelete( 'cascade' );
				$table->integer( 'user_id' )->unsigned();
				$table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
				$table->integer( 'reason_id' )->unsigned();
				$table->foreign( 'reason_id' )->references( 'id' )->on( 'reasons' )->onDelete( 'cascade' );
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
