@extends('dashboard.layout.master')

<!-- style -->
@section('style')
    <style type = "text/css">

        .reset {
            border: none;
            background: #fff;
            margin-right: 11px;
        }

        .icon-trash {
            margin-left: 8px;
            color: red;
        }

    </style>
@endsection
<!-- /style -->

@section('content')



    <div class = "panel panel-flat">
        <div class = "panel-body">
            <div class = "row">
                <form action = "{{route('addpermission')}}" method = "POST">
                    {{csrf_field()}}
                    <div class = "col-sm-11" style = "margin-bottom: 20px">
                        <input type = "text" name = "role_name" class = "form-control" placeholder = "اسم الصلاحيه"
                               required>
                    </div>
                    {{Permissions()}}
                    <div class = "col-sm-12">
                        <button class = "btn btn-success btn-block" type = "submit">اضافه</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




@endsection

<!-- javascript -->
@section('script')
    <script>


        $( function () {


            $( ".checkAll" ).on( 'click', function () {
                // alert( 'sds' )


                //check if parent is checked or not
                if ( $( this ).siblings( 'input' ).is( ":checked" ) ) {


                    $( ".abb" ).prop( "checked", false );
                } else {

                    $( ".abb" ).prop( "checked", true );
                }

            } );


            // checl all sub titles

            $( '.par' ).on( 'click', function () {

                var clssName = $( this ).data( 'id' );

                //check if parent is checked or not
                if ( $( this ).siblings( 'input' ).is( ":checked" ) ) {

                    $( '.' + clssName ).prop( "checked", false );
                } else {

                    $( '.' + clssName ).prop( "checked", true );
                }


            } );

        } );
    </script>

@endsection
<!-- /javascript -->
