<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class JobTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'jobs', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'name' );
				$table->string( 'email' );
				$table->string( 'phone' );
				$table->integer( 'user_id' )->unsigned()->default( '0' );
				$table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
				$table->string( 'location' );
				$table->string( 'lat' );
				$table->string( 'lng' );
				$table->text( 'details' );
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
			Schema::dropIfExists( 'jobs' );
		}
	}
