@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Send Messages</h4>

                        </div>

                        <div class="content">

                            <form id="form">
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
                                            <select class="selectpicker dropdown form-control" name="games[]" data-header="Select or Search by Game Name" data-size="5" data-live-search="true" data-actions-box="true" required>
                                                @foreach($character->games as $game)
                                                    <option data-tokens="{{$game->gameName}}" value="{{$game->gameID}}">{{$game->gameName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" id="sendBtn" class="btn btn-info btn-fill btn-wd">Send</button>
                                </div>

                                <script>

                                    $('#sendBtn').click(function () {
                                        var message = $('#messageContent').val();
                                        var charID = '{{$character->characterID}}';
                                        var games = $("[name='games[]']").val();
//                                        if (games && message) {
//                                            $('#sendBtn').prop( "disabled", true );
//                                            toastr.options.closeButton = true;
//                                            $('#form').submit(false);
//
//                                            $.ajax({
//
//                                                type: "GET",
//
//                                                url: './sendMessage',
//
//                                                data: {message: message, characterID: charID, games: games},
//
//                                                success: function (data) {
//                                                    $('#sendBtn').prop( "disabled", false );
//
//                                                    console.log(data.message);
//                                                    console.log(data.games);
//                                                    toastr.success('Message successfully delivered', 'Successful');
//
//                                                },
//
//                                                error: function (XMLHttpRequest, textStatus, errorThrown) {
//                                                    toastr.error('Message delivery failed', 'Failure');
//                                                    $('#sendBtn').prop( "disabled", false );
//
//                                                }
//                                            });
//                                        }
                                    });

                                </script>

                                <div class="clearfix"></div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection