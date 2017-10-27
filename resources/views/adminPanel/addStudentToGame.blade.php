@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Add Students</h4>

                        </div>

                        <div class="content">

                            <form method="post" action="{{route('addStudentToGameDB')}}" enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Excel File</label>

                                            <input id="uploadFile" name="imageName" type="text"

                                                   class="form-control border-input" value="Choose Excel File"

                                                   disabled="disabled"/>

                                            <div class="fileUpload btn btn-primary btn-fill">

                                                <span>Browse</span>

                                                <input type="file" name="excelFile" id="uploadBtn" class="upload"
                                                       accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>

                                            </div>

                                            <script>

                                                document.getElementById("uploadBtn").onchange = function () {

                                                    var name = '';

                                                    for (var i = 0; i < this.files.length; i++) {

                                                        name += this.files[i].name;

                                                        name += ',';

                                                    }

                                                    document.getElementById("uploadFile").value = name;

                                                };

                                            </script>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label >Choose Game</label>
                                            <select class="selectpicker dropdown form-control" name="game" data-header="Select or Search by Game Name" data-size="5" data-live-search="true" data-actions-box="true" required>
                                                @foreach($userGames as $userGame)
                                                    <option data-tokens="{{$userGame->game->gameName}}" value="{{$userGame->game->gameID}}">{{$userGame->game->gameName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
                                </div>

                                <div class="clearfix"></div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection