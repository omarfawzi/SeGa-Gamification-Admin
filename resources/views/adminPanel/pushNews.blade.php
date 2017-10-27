@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">
                            <div class="pull-right">
                                <img src="{{($user->profilePicture)?asset('assets/admin/images/userPhotos/'.$user->profilePicture):'https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png'}}" class="img-circle" width="50" height="50">
                                &nbsp; <span>{{$user->name}}</span>
                            </div>
                            <h4 class="title">Push News</h4>

                            {{--<p style="display: inline;" class="category">Push news</p>--}}

                        </div>
                        <div class="content">

                            <form id="form">

                                <br>
                                <div class="row">

                                    <div class="col-md-10">

                                        <div class="form-group">

                                            <label>Message :</label>

                                            <textarea rows="5" id="messageContent"  class="form-control border-input"

                                                      placeholder="Write your message here...." required></textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label >Choose Game</label>
                                            <select class="selectpicker dropdown form-control" name="games[]" data-header="Select or Search by Game Name" data-size="5" data-live-search="true" data-actions-box="true" multiple required>
                                                @foreach($userGames as $userGame)
                                                    <option data-tokens="{{$userGame->game->gameName}}" value="{{$userGame->gameID}}">{{$userGame->game->gameName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" id="sendBtn" class="btn btn-info btn-fill btn-wd">Send</button>
                                </div>



                                <div class="clearfix"></div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script>

        $('#sendBtn').click(function () {
            var message = $('#messageContent').val();
            var userID = '{{$user->id}}';
            var games = $("[name='games[]']").val();
            console.log(games);
            console.log(message);
            console.log(userID);
            if (games && message) {
                $('#sendBtn').prop( "disabled", true );
                toastr.options.closeButton = true;
                $('#form').submit(false);
                $.ajax({

                    type: 'GET',

                    url: './sendNews',

                    data: {message: message, userID: userID, games: games},

                    success: function (data) {
                        $('#sendBtn').prop( "disabled", false );

                        console.log(data.message);
                        console.log(data.games);
                        toastr.success('Message successfully delivered', 'Successful');

                    },

                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error('Message delivery failed', 'Failure');
                        $('#sendBtn').prop( "disabled", false );

                    }
                });
            }
        });

    </script>
@endsection