@extends('layouts.admin')

@section('content')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Add Game</h4>

                        </div>

                        <div class="content">

                            <form method="post" action="{{route('addGameToDB')}}" enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Game Name</label>

                                            <input type="text" class="form-control border-input"

                                                   placeholder="Game Name" name="gameName" value="{{old('gameName')}}" required>

                                        </div>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-group {{ $errors->has('gameCode') ? ' has-error' : '' }}">

                                            <label>Game Code</label>

                                            <input type="text" class="form-control border-input"

                                                   placeholder="Game Code" name="gameCode" value="{{old('gameCode')}}" required>
                                            @if ($errors->has('gameCode'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('gameCode') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label >Add Teachers</label>
                                            <select class="selectpicker dropdown form-control" name="teachers[]" data-header="Select or Search by email" data-size="5" data-live-search="true" data-actions-box="true" multiple>
                                                @foreach($teachers as $teacher)
                                                <option data-tokens="{{$teacher->email}}" data-subtext="{{$teacher->email}}" value="{{$teacher->id}}">{{$teacher->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Game Image</label>

                                            <input id="uploadFile" name="imageName" type="text"

                                                   class="form-control border-input" value="Choose Image"

                                                   disabled="disabled"/>

                                            <div class="fileUpload btn btn-primary btn-fill">

                                                <span>Browse</span>

                                                <input type="file" name="imageFile" id="uploadBtn" class="upload"
                                                       accept="image/*" required/>

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

                                    <div class="col-md-10">

                                        <div class="form-group">

                                            <label>About Game</label>

                                            <textarea rows="5" name="gameDescription" class="form-control border-input"

                                                      placeholder="Game Description"></textarea>

                                        </div>

                                    </div>

                                </div>

                                <div class="text-center">

                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Add Game</button>

                                </div>

                                <div class="clearfix"></div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@stop