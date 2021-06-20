@extends('dashboard.layout.master')
<!-- style -->
@section('style')
    <style>
        /* Live Chat */
        .live-chat {
            padding: 20px 0;
            overflow: hidden;
        }

        .chats {
            padding: 5px;
            overflow: hidden;
            background-color: #f2f2f2;
        }

        .user-sent {

        }

        .title-chat {
            margin: 5px 0;
            background-color: #FFF;
            padding: 6px;
            text-align: center;
            font-size: 20px;
            overflow: hidden;
            height: 52px;
            line-height: 40px;
            color: #30a5ff;
        }

        form.srearch-user {
            margin: 10px 0;
        }

        form.srearch-user input {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
        }

        .chat-message {
            padding: 0 5px;
            overflow: hidden;
        }

        .title-user {
            margin: 5px 0;
            background-color: #FFF;
            padding: 6px;
            overflow: hidden;
            height: 52px;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            align-items: center;
            justify-content: flex-start
        }

        .title-user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }

        .title-user h6 {
            font-size: 15px;
            color: #30a5ff;
        }

        .section-user {
            background-color: #f2f2f2;
            padding: 5px 0;
            margin: 5px;
            border-radius: 5px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .active-user {
            position: absolute;
            bottom: 0px;
            font-size: 10px;
            left: 0;
        }

        .block-users {
            background-color: #FFF;
            overflow-y: auto;
            height: 350px;
        }

        .block-users::-webkit-scrollbar {
            width: 1em;
        }

        .block-users::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .block-users::-webkit-scrollbar-thumb {
            background-color: darkgrey;
            outline: 1px solid slategrey;
        }

        .img-block-users {
            display: inline-block;
            width: 50px;
            height: 50px;
            margin: 0px 10px;
        }

        .img-block-users img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-top: 5px;
        }

        .name-users {
            flex-basis: 100%;
        }

        .name-users h4 {
            margin: 0;
            margin-bottom: 10px;
            margin-top: 5px;
            font-size: 15px;
            color: #000;
            display: flex;
            justify-content: space-between;
        }

        .name-users p {
            color: #30a5ff;
        }

        .chat-room {
            background-color: #FFF;
            overflow-y: auto;
            height: 350px;
            word-wrap: break-word;
            min-height: 350px;
        }

        .chat-room #chat {
            height: 308px;
            min-height: 308px;
        }

        .chat-room::-webkit-scrollbar {
            width: 1em;
        }

        .chat-room::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .chat-room::-webkit-scrollbar-thumb {
            background-color: darkgrey;
            outline: 1px solid slategrey;
        }

        .pls-1, .pls-2 {
            width: 100%;
            display: inline-block;
        }

        .sents {
            background-color: #f2f2f2;
            color: #000;
            width: 300px;
            padding: 5px;
            margin: 10px;
            border-radius: 10px;
            font-size: 13px;
            overflow: hidden;
        }

        .receive {
            background-color: #d3d3d3;
            color: #000;
            width: 300px;
            padding: 5px;
            margin: 10px;
            border-radius: 10px;
            font-size: 13px;
            overflow: hidden;
        }

        .pls-1 .sents {
            margin-right: auto
        }

        .pls-2 .sents {
            margin-left: auto
        }

        div.sent-massage {
            margin: 5px 0;
            display: table;
            width: 100%;
            position: relative;
        }

        div.sent-massage input {
            width: 100%;
            padding: 5px;
            border: 1px solid #30a5ff;
            border-radius: 5px;
        }

        div.sent-massage button {
            background-color: #30a5ff;
            padding: 6px;
            width: 5%;
            color: #FFF;
            border-radius: 5px;
            position: absolute;
            top: 0;
            left: 0;
        }

        .upload-img-chat {
            display: inline;
        }

        .upload-img-chat .images-upload-block {
            display: inline-block;
            position: absolute;
            background-color: #30a5ff;
            padding: 0px;
            top: 0px;
            width: 5%;
            height: 33px;
            color: #FFF;
            margin: 0;
            line-height: 34px;
            z-index: 9;
        }

        .upload-img-chat .upload-img {
            display: inline-block;
            width: 100%;
            height: 100%;
            border: 0;
            padding-top: 0;
            text-align: center;
            position: relative;
            border-radius: 5px;
            background-color: transparent;
            margin: 0;
        }

        .upload-img-chat i.fa.fa-camera {
            font-size: 15px;
            color: #fff;
            margin-top: 0;
            display: inline-block;
        }

        a.single_1 img {
            width: 100%;
            height: 200px;
            border-radius: 10px;
        }

        button {
            border: 0;
            outline: 0;
        }

    </style>
@endsection

{{--<body data-spy = 'scroll' data-target = '#navscroll-spy' data-offset = '97'>--}}

@section('content')

    <div class = "height-content" style = "min-height: 645px;">

        <div class = "live-chat">
            <div class = "">
                <div class = "row">
                    <div class = "chats">
                        <div class = "col-md-4 col-xs-12 no-padding">
                            <div class = "user-sent">
                                <h3 class = "title-chat">المحادثة</h3>

                                <div class = "block-users">

                                    @foreach($rooms as $room)
                                        <?php

                                        //if last message not belong to owner
                                        $other_id = $room -> s_id;
                                        $other_name = $room -> sender -> name;
                                        $other_avatar = url( 'dashboard/uploads/users/' . $room -> sender -> avatar );

                                        if ( Auth ::id() == $room -> s_id ) {
                                            $other_id     = $room -> r_id;
                                            $other_name   = $room -> receiver -> name;
                                            $other_avatar = url( 'dashboard/uploads/users/' . $room -> receiver -> avatar );
                                        }
                                        ?>

                                        <div class = "section-user room"
                                             data-room = "{{$room->room}}"
                                             data-other = "{{$other_id}}"
                                             data-ads = "{{$room->ads_id}}"
                                             data-name = "{{$other_name}}"
                                             data-owner = "{{$room->ads->user_id}}"
                                             data-avatar = "{{$other_avatar}}">
                                            <div class = "img-block-users">
                                                <img src = "{{$other_avatar}}">
                                            </div>
                                            <div class = "name-users">
                                                <h4>{{$other_name}}<span>{{$room->created_at}}</span></h4>
                                                <p>{{substr($room->message,0,30)}}</p>
                                                @if($room->unread)
                                                    <p class = "unread_num">
														<span class = "label label-danger">
															{{$room->unread}}
														</span>
                                                    </p>
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class = "col-md-8 col-xs-12 no-padding">
                            <div class = "chat-message">
                                <div class = "title-user">
                                    <img id = 'title_image'
                                         src = "{{url('dashboard/uploads/users/default.png')}}">
                                    <h6 id = 'title_name'></h6>
                                </div>
                                <div class = "chat-room">
                                    <div id = "chat" data-croom = "" data-cother = "" data-cads = "">

                                    </div>


                                </div>

                                {{--show if user is ads owner--}}

                                <div class = "sent-massage hidden">
                                    <input type = "text" id = "message" placeholder = "إكتب رسالتك">
                                    <button id = "send"><i class = "fa fa-paper-plane"
                                                           aria-hidden = "true"></i>
                                    </button>
                                </div>

                                <div class = "refuse-massage hidden text-center">
                                    <p>الرد متاح فقط لناشر الاعلان</p>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <script>

            //get room messages

            $( function () {
                //on click on room get its messages
                $( '.room' ).on( 'click', function () {


                                     //remove number of unreaded msgs
                                     $( this ).find( ".unread_num" ).hide();

                                     //get valus
                                     var room = $( this ).data( 'room' )
                                     var other = $( this ).data( 'other' )
                                     var adsId = $( this ).data( 'ads' )
                                     var name = $( this ).data( 'name' )
                                     var avatar = $( this ).data( 'avatar' )
                                     var ownerId = $( this ).data( 'owner' )

                                     //set title data
                                     $( '#title_name' ).text( name );
                                     $( '#title_image' ).attr( 'src', avatar );
                                     // /$('#title_namer').append(name);

                                     $( '#chat' ).data( 'cads', adsId )
                                     $( '#chat' ).data( 'cother', other )
                                     $( '#chat' ).data( 'croom', room )


                                     console.log( 'ownerId', ownerId );
                                     console.log( 'auth',{{Auth::id()}});

                                     //show send bar or error mesage
                    if(ownerId=={{Auth::id()}})
                                     $( '.sent-massage' ).removeClass( 'hidden' );
                    else

                        $( '.refuse-massage' ).removeClass( 'hidden' );
                                     $.ajax( {
                                                 type: 'GET',
                                                 url: '/admin/chat/' + room,
                                                 data:
                                                     {}
                                                 ,

                                                 success: function ( data ) {

                                                     $( '#chat' ).empty();
                                                     $( '#chat' ).append( data );
                                                 }
                                                 ,
                                             } )
                                     ;

                                 }
                )


                // send message
                $( "#send" ).click( function ( event ) {


                                        //get message
                                        var msg = $( '#message' ).val()
                                        var ads_id = $( '#chat' ).data( 'cads' )
                                        var other_id = $( '#chat' ).data( 'cother' )
                                        var room = $( '#chat' ).data( 'croom' )

                                        //send if message not empty
                                        if ( msg.length && msg != null && msg.replace( /\s/g, '' ).length ) {

                                            $.ajax( {
                                                        type: 'POST',
                                                        url: '/admin/send-msg',
                                                        data:
                                                            {
                                                                "_token": "{{ csrf_token() }}",
                                                                'other_id': other_id,
                                                                'ads_id': ads_id,
                                                                'message': msg,
                                                                'room': room,
                                                            }
                                                        ,
                                                        success: function ( data ) {
                                                            $( '#chat' ).append( data );
                                                            $( '#message' ).val( '' );
                                                        }
                                                        ,
                                                    } )
                                            ;
                                        }
                                    }
                )
            } )


        </script>

    @endsection
    <!-- start messages -->



