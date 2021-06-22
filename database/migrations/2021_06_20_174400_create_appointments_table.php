<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            Schema::dropIfExists('appointments');

            $table->increments('id');
            $table->date('appoint_day');
            $table->time('start_at');
            $table->time('end_at');
            $table->decimal('price');
            $table->string('address');
            $table->time('wait_time');

            
            $table->integer( 'doctor_id' )->unsigned();
            $table->foreign( 'doctor_id' )->references( 'id' )->on( 'doctors' )->onDelete( 'cascade' );
            
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
        Schema::dropIfExists('appointments');
    }
}
