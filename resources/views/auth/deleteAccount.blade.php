@extends('layouts.main')

@section('mainContent')
    <div  class="bg-img">
        @foreach( $themes as $theme )
            <img src="{{asset('./img/'.$theme->img)}}" alt="background"  class="backgruond @if($theme->id == $currentTheme->id) active @endif" id="{{$theme->id}}">
        @endforeach
    </div>
    <div class="formWrapper">
        <h3>Delete Account</h3>
        <form action="{{route('deleteAccountPost')}}" method="POST">
            {{csrf_field()}}
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input class="danger" type="submit" value="Delete Account">
        </form>
        @include('components.validationError')
        <div class="wrapper">
            <p>Changed your mind? <a href="{{route('myDay')}}">get back home</a> <br> Need Help? <a href="{{route('forgot')}}">Reset password</a></p>
        </div>
    </div>
@endsection
