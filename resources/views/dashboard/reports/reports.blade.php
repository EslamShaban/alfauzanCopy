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
                    <h6 class = "panel-title">التقارير</h6>

                </div>

                <div class = "panel-body">
                    <div class = "tabbable">
						
                        <div class = "tab-content">

                            <!-- supervisors reports  -->
                            <div class = "tab-pane active" id = "basic-tab2">
                                <table class = "table text-nowrap">
                                    <thead>
                                    <tr>
                                        <th style = "width: 50px">الوقت</th>
                                        <th style = "width: 300px;">المشرف</th>
                                        <th>الحدث</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($supervisorReports as $r)
                                        <tr>
                                            <td class = "text-center">
                                                <h6 class = "no-margin">
                                                    <small
                                                        class = "display-block text-size-small no-margin">{{$r->created_at->diffForHumans()}}</small>
                                                </h6>
                                            </td>
                                            <td>
                                                <div class = "media-left media-middle">
													<span class = "btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class = "img-circle"
                                                             src = "{{asset('dashboard/uploads/users/'.$r->User->avatar)}}">
													</span>
                                                </div>

                                                <div class = "media-body">
                                                    <a href = "#"
                                                       class = "display-inline-block text-default text-semibold letter-icon-title">{{$r->User->name}}</a>
                                                    <div class = "text-muted text-size-small"><span
                                                            class = "status-mark border-blue position-left"></span>{{$r->User->Role->role}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class = "text-semibold">{{$r->event}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <!-- delete supervisors reports -->
                                    <tr>
                                        <td>
                                            @if(count($supervisorReports) > 0)
                                                <form action = "{{route('deletesupervisorsreports')}}"
                                                      method = "post">
                                                    {{csrf_field()}}
                                                    <button type = "submit"
                                                            class = "btn btn-xs btn-danger generalDelete"
                                                            name = "">حذف الكل
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <!-- pagination -->
                                        <td>{{$supervisorReports->links()}}</td>
                                    </tr>
                                </table>

                            </div>
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
