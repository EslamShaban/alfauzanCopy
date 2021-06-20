<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Order extends Model
	{
//		use SoftDeletes;

		protected $table = 'orders';

		protected $guarded = [];

//		protected $dates = ['deleted_at'];

		public function user()
		{
			return $this->belongsTo( 'App\User', 'user_id' );
		}

		public function ads()
		{
			return $this->belongsTo( 'App\Models\Ads', 'ads_id' );
		}

		public function reason()
		{
			return $this->belongsTo( 'App\Models\Reason', 'reason_id' );
		}
	}
