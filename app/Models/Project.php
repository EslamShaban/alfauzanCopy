<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Project extends Model
    {
        //	use SoftDeletes;

        protected $table = 'projects';

        //	protected $dates = ['deleted_at'];

        protected $appends = [ 'type' ];

        public function getTypeAttribute()
        {
            return 'project';
        }

        public function ads()
        {
            return $this -> hasMany( 'App\Models\Ads', 'project_id' );
        }

        public function category()
        {
            return $this -> belongsTo( 'App\Models\Category', 'category_id' );
        }

        public function adsCategory()
        {
            return $this -> hasMany( 'App\Models\AdsCategory', 'project_id' );
        }

        public function categories()
        {

            return $this -> belongsToMany( 'App\Models\Category', 'projects_categories', 'project_id', 'category_id' );
        }
    }
