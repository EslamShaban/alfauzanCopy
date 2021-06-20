<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Chat extends Model
	{
		protected $table = 'chats';

		public function sender()
		{
			return $this->belongsTo( 'App\User', 's_id' );
		}

		public function receiver()
		{
			return $this->belongsTo( 'App\User', 'r_id' );
		}

		public function ads()
		{
			return $this->belongsTo( 'App\Models\Ads', 'ads_id' );
		}

	}
