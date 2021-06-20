<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class ReviewTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'review', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->float( 'rate' );
				$table->text( 'comment' );
				$table->integer( 'ads_id' )->unsigned();
				$table->foreign( 'ads_id' )->references( 'id' )->on( 'ads' )->onDelete( 'cascade' );
				$table->integer( 'user_id' )->unsigned();
				$table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
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
			Schema::dropIfExists( 'review' );
		}
	}
