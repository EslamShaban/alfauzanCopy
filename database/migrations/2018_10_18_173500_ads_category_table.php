<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class AdsCategoryTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'ads_category', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'name_ar' );
				$table->string( 'name_en' );
				$table->integer( 'project_id' )->unsigned();
				$table->foreign( 'project_id' )->references( 'id' )->on( 'projects' )->onDelete( 'cascade' );
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
			Schema::dropIfExists( 'projects' );
		}
	}
