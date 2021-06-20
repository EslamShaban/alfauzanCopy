<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class RepairTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema ::create( 'repairs', function ( Blueprint $table ) {
                $table -> increments( 'id' );
                $table -> text( 'details' );
                $table -> string( 'phone' );
                $table -> integer( 'type_id' ) -> unsigned();
                $table -> foreign( 'type_id' ) -> references( 'id' ) -> on( 'repair_type' ) -> onDelete( 'cascade' );
                $table -> timestamps();
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema ::dropIfExists( 'repairs' );
        }
    }
