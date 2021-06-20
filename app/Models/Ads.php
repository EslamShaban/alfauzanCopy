<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Ads extends Model
	{
//		use SoftDeletes;

		protected $table   = 'ads';
//		protected $dates   = ['deleted_at'];
		protected $appends = ['type'];

		public function getTypeAttribute()
		{
			return 'ad';
		}

		protected $hidden
			= [
				'updated_at',// 'deleted_at'
			];

		public function category()
		{

			return $this->belongsTo( 'App\Models\Category', 'category_id' );
		}

		public function adsCategory()
		{

			return $this->belongsTo( 'App\Models\AdsCategory', 'ads_category_id' );
		}

		public function project()
		{

			return $this->belongsTo( 'App\Models\Project', 'project_id' );
		}

		public function image()
		{

			return $this->hasMany( 'App\Models\Image', 'ads_id', 'id' );
		}


		public function user()
		{

			return $this->belongsTo( 'App\User', 'user_id' );
		}

		public function review()
		{

			return $this->hasMany( 'App\Models\Review', 'ads_id' );
		}

		public function view()
		{

			return $this->hasMany( 'App\Models\View', 'ads_id' );
		}

		public function chat()
		{

			return $this->hasMany( 'App\Models\Chat', 'ads_id' );
		}


	}
