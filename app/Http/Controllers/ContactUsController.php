<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Contact;
    use Illuminate\Support\Facades\Mail;
    use App\Mail\MailMessage;
    use App\Mail\SendEmail;
    use App\Models\SmsEmailNotification;
    use Session;

    class ContactUsController extends Controller
    {
        #inbox page
        public function InboxPage()
        {
            $messages = Contact ::latest() -> get();
            return view( 'dashboard.inbox.inbox', compact( 'messages', $messages ) );
        }

        #show message
        public function ShowMessage( $id )
        {
            $message              = Contact ::findOrFail( $id );
            $message -> ShowOrNow = 1;
            $message -> update();
            return view( 'dashboard.inbox.show_message', compact( 'message', $message ) );
        }

        #send SMS
        public function SMS( Request $request )
        {
            $this -> validate( $request, [
                'phone'       => 'required',
                'sms_message' => 'required|min:1'
            ] );

            // dd($request->all());
            // Session ::flash( 'success', 'تم ارسال الرساله' );
            // return back();

                   if(send_mobile_sms('0502333777'/* $request->phone */,$request->sms_message))
                   {
                       Session::flash('success','تم ارسال الرساله');
                       return back();
                   }else
                   {
                       Session::flash('warning','لم يتم ارسال الرساله ! ... تأكد من بيانات ال SMTP');
                       return back();
                   }
        }

        #send EMAIL
        public function EMAIL( Request $request )
        {
            $this -> validate( $request, [
                'email'         => 'required',
                'email_message' => 'required|min:1'
            ] );
            try {
                Mail ::to( $request->email ) -> send(new SendEmail( $request->email_message ));
                Session::flash( 'success', 'تم ارسال الرساله' );
                return back();

            } catch ( \Exception $e ) {
                
                Session ::flash( 'danger', 'لم يتم ارسال الرساله ! .. يرجى مراجعة بيانات ال SMTP' );
                return back();
            }
        }

        #delete mesage
        public function DeleteMessage( Request $request )
        {
            Contact ::findOrFail( $request -> id ) -> delete();
            Session ::flash( 'success', 'تم حذف الرساله' );
            return redirect( route( 'inbox' ) );
        }
    }
