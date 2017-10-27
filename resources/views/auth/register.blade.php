@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" placeholder="Username" class="form-control border-input" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" placeholder="Email" class="form-control border-input" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if (auth()->user()->role == 'company')

                        <div class="form-group">
                            <label for="educationRole" class="col-md-4 control-label">Role</label>

                            <div class="col-md-6">
                                <input id="educationRole" type="text" placeholder="Education Role" class="form-control border-input" name="educationRole" required>
                            </div>
                        </div>

                        @endif

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input  id="password" type="password" placeholder="Password" class="form-control border-input" name="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control border-input" name="password_confirmation" required>
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-4 control-label">Type</label>

                            <div class="col-md-6">
                                <div class="form-control" style="box-shadow: none; border: none; background-color: transparent;">
                                    @if (auth()->user()->role == 'admin')
                                    <input type="radio" id="role" name="role" value="admin">
                                Admin &nbsp; &nbsp;
                                        <input type="radio" id="role" name="role" value="company">
                                        Company
                                    @endif
                                        @if (auth()->user()->role == 'company')
                                        <input type="radio" id="role" name="role" value="teacher">
                                            Teacher
                                        @endif
                                @if ($errors->has('role'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('role') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                               <center>
                                <button type="submit" class="btn btn-l btn-primary">
                                    Register
                                </button>
                               </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
