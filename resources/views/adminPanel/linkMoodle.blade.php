@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Moodle Account</h4>

                            <p class="category">link your account</p>

                        </div>

                        <div class="content">
                            @if ($errors->has('error'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('error')}}</strong>
                                </div>
                            @endif
                            <form method="get" action="{{route('linkMoodleCheck')}}" autocomplete="off">

                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Domain</label>

                                            <input type="text" class="form-control border-input"

                                                   placeholder="Ex: https://rootseducators.moodle.school" value="{{old('domain')}}" name="domain" required>

                                        </div>

                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Moodle Username</label>

                                            <input type="text" autocomplete="off" class="form-control border-input"

                                                   placeholder="Username" value="{{old('username')}}" name="username" required>

                                        </div>

                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Moodle Password</label>

                                            <input type="password" class="form-control border-input"

                                                   placeholder="Password" value="{{old('password')}}" name="password" required>

                                        </div>

                                </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Continue</button>
                                </div>

                                <div class="clearfix"></div>

                            </form>

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