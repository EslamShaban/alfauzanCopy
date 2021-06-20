<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Category extends Model
    {
        //		use SoftDeletes;

        protected $table = 'categories';

        //		protected $dates = ['deleted_at'];


        public function project()
        {

            return $this -> hasMany( 'App\Models\Project', 'category_id' );
        }

        public function projects()
        {

            return $this -> belongsToMany( 'App\Models\Project', 'projects_categories', 'category_id', 'project_id' );
        }

    }
