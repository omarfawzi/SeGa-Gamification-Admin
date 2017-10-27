@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">{{$usersRole}}</h4>

                            <p class="category">All {{$usersRole}}</p>

                        </div>

                        <div class="content table-responsive table-full-width">

                            <table class="table table-striped">

                                <thead>

                                <th></th>

                                <th>Name</th>

                                <th>Email</th>
                                @if(auth()->user()->role == 'company'||auth()->user()->role == 'teacher')
                                    <th>Role</th>

                                @endif
                                <th>Date Of Registeration</th>

                                </thead>

                                <tbody>

                                @foreach($users as $user)
                                    <form method="get" action="{{route('useTeacher')}}">
                                        <tr>

                                            <input type="hidden" name="teacherID" value="{{$user->id}}">
                                            <td>
                                                <img src="{{($user->profilePicture)?asset('assets/admin/images/userPhotos/'.$user->profilePicture):'https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png'}}" alt="https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png" class="img-rounded" width="30px" height="30px">
                                            </td>

                                            <td>
                                                {{$user->name}}
                                            </td>
                                            <td>
                                                {{$user->email}}
                                            </td>

                                            @if(auth()->user()->role == 'company'||auth()->user()->role == 'teacher')
                                               <td>
                                                   {{$user->educationRole}}
                                               </td>
                                            @endif

                                            <td>
                                                {{date('F j, Y, g:i a', strtotime($user->created_at))}}
                                            </td>

                                            @if(auth()->user()->role == 'company')
                                            <td>
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fa fa-hand-pointer-o"></i>
                                                    Use
                                                </button>

                                                <a class="btn btn-primary" href="{{route('editTeacher',['teacherID'=>$user->id])}}">
                                                    <i class="fa fa-wrench"></i>
                                                    Edit
                                                </a>

                                                <a class="btn btn-danger" data-href="{{route('removeTeacher',['teacherID'=>$user->id])}}" data-toggle="modal" data-target="#confirm-delete">
                                                    <i class="fa fa-remove"></i>
                                                    Delete
                                                </a>


                                            </td>
                                            @endif
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
