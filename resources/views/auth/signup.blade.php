@extends('layouts.main')

@section('customStyle')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
@endsection

@section('mainContent')
        <div  class="bg-img">
            @foreach( $themes as $theme )
                <img src="{{asset('./img/'.$theme->img)}}" alt="background"  class="backgruond @if($theme->id == $currentTheme->id) active @endif" id="{{$theme->id}}">
            @endforeach
        </div>
        <div class="formWrapper">
            <h3>Signup</h3>
            <form action="{{route('signupPost')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-floating">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Sombody" required>
                    <label for="name">Name</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" name="email" id="floatingInput" placeholder="email@example.com" required>
                    <label for="floatingInput">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="verifyPassword" id="verifyPassword" placeholder="Password" required>
                    <label for="verifyPassword">Re-Enter Password</label>
                </div>
                <div class="form-floating imageInput">
                    <input type="file" name="profile" id="profile">
                    <label for="profile">Profile</label>
                </div>
                <input type="submit" value="Signup">
            </form>
            @include('components.validaitionError')
            <p>Already have an account? <a href="{{route('login')}}">Login</a></p>
        </div>
@endsection

@section('customJS')
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        const inputElement = document.querySelector('#profile');
        const pond = FilePond.create(inputElement);
        FilePond.setOptions({
            server: {
                url: '{{route('store')}}',
                headers : {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            }
        });
    </script>
@endsection
