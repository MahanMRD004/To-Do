@extends('layouts.main')

@section('mainContent')
    <div  class="bg-img">
        @foreach( $themes as $theme )
            <img src="{{asset('./img/'.$theme->img)}}" alt="background"  class="backgruond @if($theme->id == $currentTheme->id) active @endif" id="{{$theme->id}}">
        @endforeach
    </div>
    <div class="formWrapper">
        <h3>Reset Password</h3>
        <form action="{{route('resetPasswordPost')}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" name="token" value="{{$token}}">
            <div class="form-floating">
                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="example@gmail.com" required>
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="verifyPassword" id="verifyPassword" placeholder="Re-Enter Password" required>
                <label for="verifyPassword">Re-Enter Password</label>
            </div>
            <input type="submit" value="Reset Password">
        </form>
        @include('components.validationError')
    </div>
@endsection
