@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-5">
                    <div class="card card-user">
                        <img class="img-rounded" src="{{(auth()->user()->profilePicture)?asset('assets/admin/images/userPhotos/'.auth()->user()->profilePicture):'https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png'}}" alt="John" style="width:100%">
                    </div>
                    {{--<div class="card card-user">--}}
                        {{--<div class="image">--}}
                        {{--</div>--}}
                        {{--<div class="content">--}}
                            {{--<div class="author">--}}
                                {{--<img class="avatar border-white" src="{{(auth()->user()->profilePicture)?asset('assets/admin/images/userPhotos/'.auth()->user()->profilePicture):'https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png'}}" alt=""/>--}}
                                {{--<h4 class="title">{{auth()->user()->name}}<br />--}}
                                    {{--<a href="#"><small>{{ucfirst(auth()->user()->role)}}</small></a>--}}
                                {{--</h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<hr>--}}
                    {{--</div>--}}
                    @if (count($members) > 0)
                    <div class="card">
                        <div class="header">
                            <h4 class="title">{{(auth()->user()->role == 'teacher')?'Mates':'Members'}}</h4>
                        </div>
                        <hr>
                        <div class="content">
                            <ul class="list-unstyled team-members">
                                @foreach($members as $member)
                                <li>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="avatar">
                                                <img src="{{($member->profilePicture)?asset('assets/admin/images/userPhotos/'.$member->profilePicture):'https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png'}}" alt="https://openclipart.org/image/2400px/svg_to_png/211821/matt-icons_preferences-desktop-personal.png" class="img-circle img-no-padding img-responsive">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            {{$member->name}}
                                            <br />
                                            <span class="text-muted"><small>{{ucfirst($member->educationRole)}}</small></span>
                                        </div>

                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                        @endif

                </div>
                <div class="col-lg-8 col-md-7">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Edit Profile</h4>
                        </div>
                        <div class="content">
                            <form method="post" action="{{route('updateProfile')}}"  enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">
                                    @if(auth()->user()->role == 'teacher')
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <input type="text" class="form-control border-input" disabled placeholder="Company" value="{{$company->name}}">
                                        </div>
                                    </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Role</label>
                                                <input type="text" class="form-control border-input" disabled placeholder="Role" value="{{auth()->user()->educationRole}}">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control border-input" placeholder="username" name="username" value="{{auth()->user()->name}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control border-input" placeholder="Email" value="{{auth()->user()->email}}" disabled>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Profile Picture</label>

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

                                {{--<div class="row">--}}
                                    {{--<div class="col-md-12">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label>About Me</label>--}}
                                            {{--<textarea rows="5" class="form-control border-input" placeholder="Here can be your description" value="Mike">Oh so, your weak rhyme--}}
{{--You doubt I'll bother, reading into it--}}
{{--I'll probably won't, left to my own devices--}}
{{--But that's the difference in our opinions.</textarea>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="text-center">
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Update Profile</button>
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