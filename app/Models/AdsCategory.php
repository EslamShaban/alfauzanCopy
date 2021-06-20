<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class AdsCategory extends Model
	{
//		use SoftDeletes;

		protected $table   = 'ads_category';
//		protected $dates   = ['deleted_at'];


		protected $softDelete = true;
		protected $hidden
			= [
				'updated_at', //'deleted_at'
			];

		public function ads()
		{

			return $this->hasMany( 'App\Models\Ads', 'ads_category_id' );
		}

		public function project()
		{

			return $this->belongsTo( 'App\Models\Project', 'project_id' );
		}


	}
