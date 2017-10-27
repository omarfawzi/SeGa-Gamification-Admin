@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">
                            <center>
                            <h3 class="title">Games</h3>
                            <hr>
                            </center>

                        </div>
            <div class="content">
            <div class="row">
                @foreach($userGames as $userGame)
                <div class="col-lg-3 col-md-3">
                    <div class="card card-user">
                        <div class="image">
                            <img src="{{asset('assets/admin/images/gamesPhotos/'.$userGame->game->gamePhoto)}}" alt="..."/>
                        </div>
                        {{--<div class="content">--}}
                            {{--<div class="author">--}}
                                {{--<img class="avatar border-white" src="assets/img/faces/face-2.jpg" alt="..."/>--}}
                                {{--<h4 class="title">Chet Faker<br />--}}
                                    {{--<a href="#"><small>@chetfaker</small></a>--}}
                                {{--</h4>--}}
                            {{--</div>--}}
                            {{--<p class="description text-center">--}}
                                {{--"I like the way you work it <br>--}}
                                {{--No diggity <br>--}}
                                {{--I wanna bag it up"--}}
                            {{--</p>--}}
                        {{--</div>--}}
                        <div class="text-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                   <p> Name : {{$userGame->game->gameName}} </p>
                                    <p style="display: inline"> Code : <p style="color: green;display: inline;"> {{$userGame->game->gameCode}}</p> </p>
                                    <form method="get" action="{{route('editGame')}}" style="display: inline">
                                        <input type="hidden" name="gameID"  value="{{$userGame->game->gameID}}">
                                    <button type="submit"  class="btn btn-default btn-fill" title="edit">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </button>
                                    </form>
                                    {{--<a class="btn btn-primary btn-fill" title="show students">--}}
                                        {{--<i class="fa fa-eye"></i>--}}
                                        {{--Students--}}
                                    {{--</a>--}}
                                    <form method="get" action="{{route('scoreBoard')}}" style="display: inline">
                                        <input type="hidden" name="gameID" value="{{$userGame->game->gameID}}">

                                    <button type="submit" class="btn btn-danger" title="rankings">
                                        <i class="fa fa-calendar-o"></i>
                                        ScoreBoard
                                    </button>
                                    </form>
                                </div>
                            </div>
                            <br>

                        </div>
                    </div>
                </div>

                @endforeach
            </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection