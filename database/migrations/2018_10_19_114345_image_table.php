<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class ImageTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'images', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'image' );
				$table->integer( 'ads_id' )->unsigned();
				$table->foreign( 'ads_id' )->references( 'id' )->on( 'ads' )->onDelete( 'cascade' );
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
			Schema::dropIfExists( 'images' );
		}
	}
