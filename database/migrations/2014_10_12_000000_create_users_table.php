<?php

	use App\User;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class CreateUsersTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'users', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'name' );
				$table->string( 'email' )->unique();
				$table->string( 'password' );
				$table->string( 'phone' )->unique();
				$table->string( 'code' )->nullable();
				$table->string( 'avatar' )->default( 'default.png' );
				$table->integer( 'role' )->unsigned()->default( '0' );
				$table->foreign( 'role' )->references( 'id' )->on( 'roles' )->onDelete( 'cascade' );
				$table->integer( 'active' )->default( '1' );
				$table->text( 'jwt_token' )->nullable();
				$table->tinyInteger( 'notifilable' )->default( '1' );

//				$table->softDeletes();
				$table->rememberToken();
				$table->timestamps();

			} );

			// Insert some stuff
			$user           = new User;
			$user->name     = 'اوامر الشبكه';
			$user->email    = 'aait@info.com';
			$user->password = bcrypt( 111111 );
			$user->phone    = '123456789';
			$user->avatar   = 'default.png';
			$user->code     = '123';
			$user->role     = '1';
			$user->save();

			// Insert some stuff
			$user           = new User;
			$user->name     = 'فكرى';
			$user->email    = 'm1@a.s';
			$user->password = bcrypt( 123456 );
			$user->phone    = '01069541294';
			$user->avatar   = 'default.png';
			$user->code     = '123';
			$user->role     = '1';
			$user->save();


		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema::dropIfExists( 'users' );
		}
	}
