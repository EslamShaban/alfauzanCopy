<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stars', function (Blueprint $table) {
            Schema::dropIfExists('stars');
            $table->increments('id');
            $table->integer( 'user_id' )->unsigned();
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            
            $table->integer( 'doctor_id' )->unsigned();
            $table->foreign( 'doctor_id' )->references( 'id' )->on( 'doctors' )->onDelete( 'cascade' );
           
            $table->tinyInteger('star');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stars');
    }
}
