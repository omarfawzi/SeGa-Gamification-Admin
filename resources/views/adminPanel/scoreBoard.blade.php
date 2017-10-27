@extends('layouts.admin')

@section('content')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }

        th {
            background-color: #326295;
            font-weight: bold;
            color: #fff;
            white-space: nowrap;
        }

        td, th {
            padding: 1em 1.5em;
            text-align: left;
        }

        tbody th {
            background-color: #2ea879;
        }
        tbody tr:nth-child(2n-1) {
            background-color: #f5f5f5;
            transition: all .125s ease-in-out;
        }
        tbody tr:hover {
            background-color: rgba(50,98,149,.3);
        }

        td.rank {
            text-transform: capitalize;
        }
    </style>
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">
                            <div class="pull-right">
                                <form style="display: inline" method="get" action="{{route('updateCourse')}}">
                                    <input type="hidden" name="courseID" value="{{$scoreboard[0]->game->moodleID}}">
                                    <input type="hidden" name="courseName" value="{{$scoreboard[0]->game->gameName}}">
                                    <button type="submit" class="btn btn-default"> Update </button>
                                </form>
                                <a href="{{route('externalScoreBoard',['gameID'=>$scoreboard[0]->gameID])}}" target="_blank" class="btn btn-primary btn-fill">
                                    External ScoreBoard
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <h4 class="title">ScoreBoard</h4>

                            <p class="category">{{$scoreboard[0]->game->gameName}}</p>


                        </div>



                        <div class="demo content table-responsive table-full-width">

                            <table id="table">
                                <thead>
                                <tr>
                                    <th class="anim:update anim:number">Rank</th>
                                    <th class="anim:constant"></th>

                                    <th class="anim:id"></th>

                                    <th class="anim:constant">Moodle Points</th>

                                    <th class="anim:constant">Moodle Progress</th>

                                    <th class="anim:update anim:sort anim:number">Points</th>

                                    <th class="anim:constant">Progress</th>
                                    {{--<th class="anim:constant">+</th>--}}
                                    {{--<th class="anim:constant">-</th>--}}

                                    {{--<th class="">Progress</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($scoreboard as $key => $item)
                                    <tr id="{{$item->studentID}}">
                                        <td class="rank">{{$key+1}}</td>
                                        <td class="team">
                                            @if($item->student->profilePicture)
                                                @if($item->student->profilePicture[0] == 'h')
                                                <img src="{{$item->student->profilePicture}}" width="50px" height="50px">
                                                    @else
                                                    <img src="{{asset('assets/admin/images/studentPhotos/'.$item->student->profilePicture)}}" width="50px" height="50px">

                                                @endif
                                            @else
                                                <img src="https://www.1plusx.com/app/mu-plugins/all-in-one-seo-pack-pro/images/default-user-image.png" width="50px" height="50px">
                                            @endif
                                        </td>
                                        @if  ($item->student->firstName)
                                        <td>{{$item->student->firstName .' '.$item->student->lastName}}</td>
                                        @else
                                            <td>{{$item->student->nickName}}</td>
                                        @endif
                                        <td>{{$item->moodlePoints}}</td>
                                        <td>{{$item->moodleProgress}}</td>
                                            <td class="points">
                                                <input id="start" class="form-control border-input up" value="{{$item->points}}"  type="number">
                                            </td>
                                        <td class="points">
                                            <input class="form-control border-input up" min="0" max="100" value="{{$item->progress}}"  type="number">
                                        </td>
                                        {{--<td><span class="up">&uarr;</span></td>--}}
                                        {{--<td><span class="down">&darr;</span></td>--}}
                                        <td style="display: none;">{{$item->studentID}}</td>
                                        {{--<td class="up-down">--}}
                                        {{--<input class="form-control border-input" style="width: 30%;">--}}
                                        {{--</td>--}}
                                    </tr>
                                    @endforeach

                                {{--<tr id="1">--}}
                                    {{--<td class="rank">1</td>--}}
                                    {{--<td>John</td>--}}
                                    {{--<td class="team">--}}
                                        {{--<img src="https://tctechcrunch2011.files.wordpress.com/2010/07/scott-forstall.jpeg?w=270" width="50px" height="50px">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input id="start" class="form-control border-input up" style="width: 60%;" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                       {{--<input class="form-control border-input" style="width: 60%;" min="0" max="100" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td><span class="up">&uarr;</span></td>--}}
                                    {{--<td><span class="down">&darr;</span></td>--}}
                                    {{--<td style="display: none;">1</td>--}}
                                    {{--<td class="up-down">--}}
                                        {{--<input class="form-control border-input" style="width: 30%;">--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr id="2">--}}
                                    {{--<td class="rank">2</td>--}}
                                    {{--<td>Mark</td>--}}

                                    {{--<td class="team">--}}
                                        {{--<img src="https://upload.wikimedia.org/wikipedia/commons/8/87/Mani_Zadeh_Profile.jpg" width="50px" height="50px">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input class="form-control border-input up" style="width: 60%;" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input class="form-control border-input" style="width: 60%;" min="0" max="100" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td style="display: none;">2</td>--}}

                                    {{--<td class="points">--}}
                                    {{--<input class="form-control border-input up" style="width: 30%;" type="number" value="0">--}}
                                    {{--</td>--}}
                                    {{--<td><span class="up">&uarr;</span></td>--}}
                                    {{--<td><span class="down">&darr;</span></td>--}}


                                {{--</tr>--}}
                                {{--<tr id="3">--}}
                                    {{--<td class="rank">3</td>--}}
                                    {{--<td>Keanu</td>--}}

                                    {{--<td class="team">--}}
                                        {{--<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2b/Dr_Johnny_Hon.profile_shot.jpg/160px-Dr_Johnny_Hon.profile_shot.jpg" width="50px" height="50px">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input class="form-control border-input up" style="width: 60%;" value="0" type="number">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input class="form-control border-input" style="width: 60%;" min="0" max="100" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td style="display: none;">3</td>--}}

                                    {{--<td class="points">--}}
                                    {{--<input class="form-control border-input up" style="width: 30%;" type="number" value="0">--}}
                                    {{--</td>--}}
                                    {{--<td><span class="up">&uarr;</span></td>--}}
                                    {{--<td><span class="down">&darr;</span></td>--}}

                                {{--</tr>--}}
                                {{--<tr id="4">--}}
                                    {{--<td class="rank">4</td>--}}
                                    {{--<td>Chan</td>--}}

                                    {{--<td class="team">--}}
                                        {{--<img src="https://content-static.upwork.com/uploads/2014/10/02123010/profile-photo_friendly.jpg" width="50px" height="50px">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input class="form-control border-input up" style="width: 60%;" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td class="points">--}}
                                        {{--<input class="form-control border-input" style="width: 60%;" min="0" max="100" value="0"  type="number">--}}
                                    {{--</td>--}}
                                    {{--<td style="display: none;">4</td>--}}

                                    {{--<td class="points">--}}
                                    {{--<input class="form-control border-input up" style="width: 30%;" type="number" value="0">--}}
                                    {{--</td>--}}
                                    {{--<td><span class="up">&uarr;</span></td>--}}
                                    {{--<td><span class="down">&darr;</span></td>--}}


                                {{--</tr>--}}
                                </tbody>
                            </table>

                            {{--<button class="btn btn-primary btn-fill"> Update Results</button>--}}



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
