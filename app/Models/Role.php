<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Role extends Model
	{
//		use SoftDeletes;
		protected $table = 'roles';

//		protected $dates = ['deleted_at'];

		public function Permissions()
		{
			return $this->hasMany( 'App\Models\Permission', 'role_id', 'id' );
		}
	}
