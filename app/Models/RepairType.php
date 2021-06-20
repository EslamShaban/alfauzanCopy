<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class RepairType extends Model
	{
//		use SoftDeletes;

		protected $table = 'repair_type';

//		protected $dates = ['deleted_at'];

		public function repair()
		{
			return $this->hasMany( 'App\Models\Repair', 'type_id' );
		}
	}
