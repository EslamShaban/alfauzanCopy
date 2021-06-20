<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class AdsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'ads', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'name_ar' );
				$table->string( 'name_en' );
				$table->float( 'cost' );
				$table->float( 'tax' );
				$table->float( 'vat' );
				$table->text( 'desc' );
				$table->text( 'details' );
				$table->text( 'component' );
				$table->integer( 'category_id' )->unsigned()->nullable();
				$table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->onDelete( 'cascade' );
				$table->integer( 'ads_category_id' )->unsigned()->nullable();
				$table->foreign( 'ads_category_id' )->references( 'id' )->on( 'ads_category' )->onDelete( 'cascade' );
				$table->integer( 'user_id' )->unsigned()->default( '0' );
				$table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
				$table->string( 'lat' );
				$table->string( 'lng' );
				$table->string( 'code' )->unique();
                $table->string( 'qr_image' );
				$table->string( 'video' );
				$table->integer( 'offer' )->default( '0' );
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
			Schema::dropIfExists( 'ads' );
		}
	}
