@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Add Character</h4>

                        </div>

                        <div class="content">

                            <form method="post" action="{{route('addCharacterDB')}}" enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Character Name</label>

                                            <input type="text" class="form-control border-input"

                                                   placeholder="Character Name" name="characterName" required>

                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label >Add Game</label>
                                            <select class="selectpicker dropdown form-control" name="games[]" data-header="Select or Search by Game Name" data-size="5" data-live-search="true" data-actions-box="true" multiple required>
                                                @foreach($userGames as $userGame)
                                                    <option data-tokens="{{$userGame->game->gameName}}" value="{{$userGame->gameID}}">{{$userGame->game->gameName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Character Image</label>

                                            <input id="uploadFile" name="imageName" type="text"

                                                   class="form-control border-input" value="Choose Image"

                                                   disabled="disabled"/>

                                            <div class="fileUpload btn btn-primary btn-fill">

                                                <span>Browse</span>

                                                <input type="file" name="imageFile" id="uploadBtn" class="upload"
                                                       accept="image/*"/>

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

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>About Character</label>

                                            <textarea rows="5" name="characterDescription" class="form-control border-input"

                                                      placeholder="Character Description" ></textarea>

                                        </div>

                                    </div>

                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Add Character</button>
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