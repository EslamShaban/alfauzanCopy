<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('offer_title');
            $table->text('offer_description');
            $table->decimal('price');
            $table->string('discount');
            $table->decimal('price_after_discount');
            
            $table->integer( 'doctor_id' )->unsigned();
            $table->foreign( 'doctor_id' )->references( 'id' )->on( 'doctors' )->onDelete( 'cascade' );

            $table->integer( 'category_id' )->unsigned();
            $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->onDelete( 'cascade' );
            
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
        Schema::dropIfExists('offers');
    }
}
