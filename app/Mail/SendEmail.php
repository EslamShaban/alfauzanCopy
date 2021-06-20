<?php

    namespace App\Mail;

    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Contracts\Queue\ShouldQueue;

    class SendEmail extends Mailable
    {
        use Queueable, SerializesModels;

        /**
         * Create a new message instance.
         *
         * @return void
         */

        public $msg;

        public function __construct( $msg )
        {
            $this -> msg = $msg;
        }

        /**
         * Build the message.
         *
         * @return $this
         */
        public function build()
        {
            $data = $this -> msg;
            return $this -> view( 'emails.WelcomeMail', compact( 'data' ) ) -> subject( 'Al Fauzan' )
                         -> from( 'ABDULMALIK@ALFAUZAN.COM' );
        }
    }
