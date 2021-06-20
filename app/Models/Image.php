<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Image extends Model
	{
		protected $table = 'images';

		public function ads()
		{
			return $this->belongsTo( 'App\Models\Ads','ads_id');
		}
	}
