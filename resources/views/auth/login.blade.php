@extends('layouts.main')

@section('mainContent')
    <div  class="bg-img">
        @foreach( $themes as $theme )
            <img src="{{asset('./img/'.$theme->img)}}" alt="background"  class="backgruond @if($theme->id == $currentTheme->id) active @endif" id="{{$theme->id}}">
        @endforeach
    </div>
    <div class="formWrapper">
        <h3>Login</h3>
        <form action="{{route('loginPost')}}" method="POST">
            {{csrf_field()}}
            <div class="form-floating">
                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="example@gmail.com" required>
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="true" name="remember" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Remember me
                </label>
            </div>
            <input type="submit" value="Login">
        </form>
        @include('components.validationError')
        <div class="wrapper">
            <p>Don't have an account? <a href="{{route('signup')}}">Signup page</a> <br> Need Help? <a href="{{route('forgot')}}">Reset password</a></p>
        </div>
    </div>
@endsection
