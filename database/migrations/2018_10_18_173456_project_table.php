<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class ProjectTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'projects', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'name_ar' );
				$table->string( 'name_en' );
				$table->string( 'lat' );
				$table->string( 'lng' );
				$table->integer( 'category_id' )->unsigned();
				$table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->onDelete( 'cascade' );
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
