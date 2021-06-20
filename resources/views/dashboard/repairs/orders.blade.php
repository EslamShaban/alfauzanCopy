@extends('dashboard.layout.master')

<!-- style -->
@section('style')

@endsection
<!-- /style -->

@section('content')


    <div class = "row">
        <div class = "col-md-12">
            <div class = "panel panel-flat">
                <div class = "panel-heading">
                    <h6 class = "panel-title">طلبات الصيانة</h6>

                </div>
                <!-- buttons -->
                <div class = "panel-body">
                    <div class = "row">
                        <div class = "col-xs-4">
                            <a href = "{{route('repair_types')}}"
                               class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal"
                               type = "button"
                               data-target = "#exampleModal"><i class = "icon-plus3"></i>
                                <span>انواع الصيانة</span>
                            </a>
                        </div>
                        <div class = "col-xs-4">
                            <button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button">
                                <i
                                    class = "icon-list-numbered"></i>
                                <span>عدد الطلبات: {{\App\Models\Repair::count()}} </span>
                            </button>
                        </div>
                        <div class = "col-xs-4">
                            <a href = "{{route('logout')}}"
                               class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
                               type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
                        </div>

                    </div>
                </div>
                <!-- /buttons -->
                <div class = "panel-body">
                    <div class = "tabbable">


                        <!--  reports  -->
                        <div class = "tab-pane" id = "basic-tab2">
                            <table class = "table text-nowrap" data-order = "[]">
                                <thead>
                                <tr>
                                    <th style = "width:200px">نوع الصيانة</th>
                                    <th style = "width:150px">رقم الهاتف</th>
                                    <th style = "width: 400px;">بيانات العقار</th>
                                    <th style = "width: 100px;">الوقت</th>
                                    <th>الحدث</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($repairs as $r)


                                    <tr>

                                        <td>
                                            {{$r->type->name_ar}}
                                        </td>
                                        <td>
                                            {{$r->phone}}
                                        </td>

                                        <td>
                                            <span class = "text-semibold">{{$r->details}}</span>
                                        </td>

                                        <td class = "text-center">
                                            <h6 class = "no-margin">
                                                <small
                                                    class = "display-block text-size-small no-margin">{{$r->created_at->diffForHumans()}}</small>
                                            </h6>
                                        </td>
                                        <td>
                                            <form action = "{{route('delete_repair_order')}}"
                                                  method = "post">
                                                {{csrf_field()}}
                                                <input type = "hidden" value = "{{$r->id}}"
                                                       name = "id">
                                                <button type = "submit"
                                                        class = "btn btn-xs btn-danger generalDelete"
                                                        name = "">حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                                <!-- delete supervisors reports -->
                                <tr>

                                    <!-- pagination -->
                                    <td>{{$repairs->links()}}</td>
                                </tr>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- javascript -->
@section('script')
    <script type = "text/javascript">
        //stay in current tab after reload
        $( function () {
            // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
            $( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab', function ( e ) {
                // save the latest tab; use cookies if you like 'em better:
                localStorage.setItem( 'lastTab', $( this ).attr( 'href' ) );
            } );

            // go to the latest tab, if it exists:
            var lastTab = localStorage.getItem( 'lastTab' );
            if ( lastTab ) {
                $( '[href="' + lastTab + '"]' ).tab( 'show' );
            }
        } );
    </script>

@endsection
<!-- /javascript -->

@endsection
