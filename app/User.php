<?php

	namespace App;

	use Illuminate\Database\Eloquent\SoftDeletes;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Tymon\JWTAuth\Contracts\JWTSubject;

	class User extends Authenticatable implements JWTSubject
	{

		use Notifiable;
//		use SoftDeletes;


		protected $fillable
			= [
				'name', 'email', 'password', 'phone', 'mobile', 'code', 'device_id','jwt_token','active','block'
			];

//		protected $dates = ['deleted_at'];

		protected $hidden
			= [
				'password', 'remember_token','jwt_token','updated_at','deleted_at'
			];

		public function Role()
		{
			return $this->belongsTo( 'App\Models\Role', 'role' );
		}

		public function Reports()
		{
			return $this->hasMany( 'App\Models\Report', 'user_id', 'id' );
		}

		public function favourite()
		{

			return $this->belongsToMany( 'App\Models\Ads', 'favourite', 'user_id', 'ads_id' );
		}

		public function ads()
		{

			return $this->hasMany( 'App\Models\Ads',  'user_id' );
		}

		public function review()
		{

			return $this->hasMany( 'App\Models\Review', 'user_id' );
		}

		
		public function notification()
		{
			return $this->hasMany( 'App\Models\Notification', 'user_id', 'id' );
		}

		public function appointments()
		{
			return $this->belongsToMany( 'App\Models\Appointment', 'orders', 'user_id', 'id');
		}


		/**
		 * Get the identifier that will be stored in the subject claim of the JWT.
		 *
		 * @return mixed
		 */
		public function getJWTIdentifier()
		{
			return $this->getKey();
		}

		/**
		 * Return a key value array, containing any custom claims to be added to the JWT.
		 *
		 * @return array
		 */
		public function getJWTCustomClaims()
		{
			return [];
		}

	}
