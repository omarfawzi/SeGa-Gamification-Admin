@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">
                            <form method="get" action="{{route('unlinkMoodle')}}">

                                <h4 class="title">Courses</h4>

                                <p style="display: inline;" class="category">All Courses</p>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-danger btn-wd">
                                        <i class="fa fa-chain-broken"></i>
                                        Unlink Moodle
                                    </button>
                                </div>
                            </form>

                        </div>

                        <div class="content table-responsive table-full-width">

                            <table class="table table-striped">

                                <thead>

                                <th>Course ID</th>

                                <th>Course Name</th>

                                </thead>

                                <tbody>

                                @foreach($courses as $course)
                                    <form method="get" action="{{route('updateCourse')}}">
                                        <tr>
                                            <input type="hidden" name="courseID" value="{{$course->courseID}}">
                                            <input type="hidden" name="courseName" value="{{$course->courseName}}">

                                            <td>{{$course->courseID}}</td>

                                            <td>{{$course->courseName}}</td>
                                            <td>
                                                <button type="submit"
                                                        class="btn btn-primary btn-fill"> {{$course->function}} </button>
                                            </td>
                                            {{--<td>{{$order->orderID}}</td>--}}

                                            {{--<td>{{$order->name}}</td>--}}

                                            {{--<td>{{$order->phone}}</td>--}}

                                            {{--<td>{{$order->address}}</td>--}}

                                            {{--<td>{{$order->date}}</td>--}}

                                            {{--<td>{{($order->status)?'On Way':'In Stock'}}</td>--}}

                                            {{--<td>--}}
                                            {{--<a class="btn btn-info btn-fill btn-sm" href="{{route('orderDetails',['orderID'=>$order->orderID])}}" title="Details">--}}

                                            {{--<span class="fa fa-edit"></span>--}}

                                            {{--</a>--}}
                                            {{--</td>--}}

                                            {{--<td>--}}
                                            {{--<button class="btn btn-info btn-fill btn-sm" href="javascript:;" title="Track" {{($order->status)?'':'disabled'}}>--}}

                                            {{--<span class="fa fa-map-marker"></span>--}}

                                            {{--</button>--}}
                                            {{--</td>--}}
                                            {{--<td>--}}
                                            {{--<a class="btn btn-danger btn-fill btn-sm" data-toggle="modal" data-target="#confirm-delete" data-href="{{route('cancelOrder',['orderID'=>$order->orderID])}}" title="Cancel Order">--}}

                                            {{--<span class="fa fa-remove"></span>--}}

                                            {{--</a>--}}
                                            {{--</td>--}}
                                        </tr>
                                    </form>

                                @endforeach

                                </tbody>

                            </table>


                        </div>

                    </div>

                    {{--<div style="float: right">--}}
                    {{--{{$orders->links()}}--}}
                    {{--</div>--}}

                </div>

            </div>

        </div>

    </div>

@endsection