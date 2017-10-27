<html>
<head>
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
            background-color: rgba(50, 98, 149, .3);
        }

        td.rank {
            text-transform: capitalize;
        }
    </style>
    <meta charset="utf-8"/>

    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/admin/img/apple-icon.png')}}">

    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/admin/img/favicon.png')}}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>Score Board</title>
    <link href="{{asset('assets/admin/css/paper-dashboard.css')}}" rel="stylesheet"/>
    <script src="{{asset('assets/admin/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script src="{{asset('assets/admin/js/externalScoreboard.js')}}" type="text/javascript"></script>

</head>

<body>

<div class="container">

    <div class="card">

        <center class="header">
            <h3 class="title">{{$scoreboard[0]->game->gameName}}</h3>
            <hr>
        </center>


        <div class="demo content table-responsive table-full-width">

            <table id="table">
                <thead>
                <tr>
                    <th class="anim:update anim:number">Rank</th>
                    <th class="anim:constant"></th>
                    <th class="anim:id"></th>

                    <th class="anim:update anim:sort anim:number">Points</th>
                    <th class="anim:constant">Progress</th>
                    {{--<th class="anim:constant">+</th>--}}
                    {{--<th class="anim:constant">-</th>--}}

                    {{--<th class="">Progress</th>--}}
                </tr>
                </thead>
                <tbody>
                <input type="hidden" value="{{$scoreboard[0]->gameID}}" id="gameID">
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
                        <td>{{explode('@', $item->student->email)[0]}}</td>
                        <td class="points">
                            {{$item->points+$item->moodlePoints}}
                        </td>
                        <td class="points">
                            {{$item->progress+$item->moodleProgress}}
                        </td>
                        {{--<td><span class="up">&uarr;</span></td>--}}
                        {{--<td><span class="down">&darr;</span></td>--}}
                        <td style="display: none;">{{$item->studentID}}</td>
                        {{--<td class="up-down">--}}
                        {{--<input class="form-control border-input" style="width: 30%;">--}}
                        {{--</td>--}}
                    </tr>
                @endforeach
                {{--<tr id="2">--}}
                    {{--<td class="rank">2</td>--}}
                    {{--<td>Mark</td>--}}

                    {{--<td class="team">--}}
                        {{--<img src="https://upload.wikimedia.org/wikipedia/commons/8/87/Mani_Zadeh_Profile.jpg"--}}
                             {{--width="50px" height="50px">--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--<input class="form-control border-input up" style="width: 30%;" type="number" value="0">--}}
                    {{--</td>--}}
                    {{--<td><span class="up">&uarr;</span></td>--}}
                    {{--<td><span class="down">&darr;</span></td>--}}


                {{--</tr>--}}
                {{--<tr id="3">--}}
                    {{--<td class="rank">3</td>--}}
                    {{--<td>Keanu</td>--}}

                    {{--<td class="team">--}}
                        {{--<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2b/Dr_Johnny_Hon.profile_shot.jpg/160px-Dr_Johnny_Hon.profile_shot.jpg"--}}
                             {{--width="50px" height="50px">--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--<input class="form-control border-input up" style="width: 30%;" type="number" value="0">--}}
                    {{--</td>--}}
                    {{--<td><span class="up">&uarr;</span></td>--}}
                    {{--<td><span class="down">&darr;</span></td>--}}

                {{--</tr>--}}
                {{--<tr id="4">--}}
                    {{--<td class="rank">4</td>--}}
                    {{--<td>Chan</td>--}}

                    {{--<td class="team">--}}
                        {{--<img src="https://content-static.upwork.com/uploads/2014/10/02123010/profile-photo_friendly.jpg"--}}
                             {{--width="50px" height="50px">--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
                    {{--</td>--}}
                    {{--<td class="points">0--}}
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


</body>
</html>