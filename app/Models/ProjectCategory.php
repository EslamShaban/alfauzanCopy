<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class ProjectCategory extends Model
    {
        //	use SoftDeletes;

        protected $table = 'projects_categories';
        
        public $timestamps = false;


    }
