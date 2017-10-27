@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Characters</h4>

                            <p class="category">All Characters</p>

                        </div>

                        <div class="content table-responsive table-full-width">

                            <table class="table table-striped">

                                <thead>
                                <th></th>

                                <th>Name</th>

                                <th>Games</th>

                                <th>Description</th>


                                </thead>

                                <tbody>

                                @foreach($gamesCharacters as $gamesCharacter)
                                    <form method="get" action="{{route('useCharacter')}}">
                                        <input type="hidden" name="characterID" value="{{$gamesCharacter->character->characterID}}">
                                    <tr>
                                        <td><img src="{{asset('assets/admin/images/characterPhotos/'.$gamesCharacter->character->characterPhoto)}}" width="50px" height="50px"></td>
                                        <td>
                                            {{$gamesCharacter->character->characterName}}
                                        </td>
                                        <td>
                                            @foreach($gamesCharacter->character->games as $key => $game)
                                                {{$game->gameName}}
                                                @if($key != count($gamesCharacter->character->games) -1 )
                                                    {{','}}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            {{$gamesCharacter->character->characterDescription}}
                                        </td>

                                        <td>
                                            <button type="submit" class="btn btn-primary btn-fill"> Use </button>
                                            <a href="{{route('editCharacter',['characterID'=>$gamesCharacter->characterID])}}" class="btn btn-default btn-fill">
                                                <i class="fa fa-edit"></i>
                                                Edit
                                            </a>
                                            <a data-href="{{route('deleteCharacter',['characterID'=>$gamesCharacter->characterID])}}" class="btn btn-danger btn-fill" data-toggle="modal" data-target="#confirm-delete">
                                                <i class="fa fa-remove"></i>
                                                Delete
                                            </a>

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