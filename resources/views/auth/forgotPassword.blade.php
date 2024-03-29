@extends('layouts.main')

@section('mainContent')
    <div  class="bg-img">
        @foreach( $themes as $theme )
            <img src="{{asset('./img/'.$theme->img)}}" alt="background"  class="backgruond @if($theme->id == $currentTheme->id) active @endif" id="{{$theme->id}}">
        @endforeach
    </div>
    <div class="formWrapper">
        <h3>Forgot Password</h3>
        <form action="{{route('forgotPost')}}" method="POST">
            {{csrf_field()}}
            <div class="form-floating">
                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="example@gmail.com" required>
                <label for="floatingInput">Email</label>
            </div>
            <input type="submit" value="Send Email">
        </form>
        @include('components.validationError')
        <p>Return to <a href="@if(auth()->check()) {{route('deleteAccount')}} @else {{route('login')}} @endif">previous page</a></p>
    </div>
@endsection
