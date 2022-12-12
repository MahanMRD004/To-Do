@extends('layouts.main')


{{--SideBar--}}
@section('sideBar')
    @include('components.sideBar')
@endsection
{{--Main Content--}}
@section('mainContent')
    <main class="mainContent">
        <div  class="bg-img">
            @foreach( $themes as $theme )
                <img src="{{asset('./img/'.$theme->img)}}" alt="background"  class="backgruond @if($theme->id == $currentTheme->id) active @endif" id="{{$theme->id}}">
            @endforeach
        </div>
        <header>
            <div class="wrapper d-flex align-items-center gap-3">
                <button class="btn" onclick="toggleSidebar()">
                    <ion-icon name="menu"></ion-icon>
                    <ion-icon name="close"></ion-icon>
                    <div class="absolute"></div>
                </button>
                <div class="info">
                    <div class="listTitle">
                        <h4>{{$listTitle}}</h4>
                    </div>
                    <span class="date">{{$date}}</span>
                </div>
            </div>
            <div class="controls">
                <button class="btn" onclick="openModal()"><ion-icon name="sparkles-outline"></ion-icon></button>
            </div>
        </header>
        <section class="content">
            <ul class="todoList">
                @forelse($tasks as $task)
                    <li class="todoItem @if($task->priority == 1)stared @endif  @if($task->status == 1)done @endif" id="item{{$task->id}}">
                            <span class="info">
                                <button onclick="check({{$task->id}})" class="check" id="check{{$task->id}}">
                                    <ion-icon name="checkmark-outline"></ion-icon>
                                </button>
                                <span class="taskName">
                                    {{$task->title}}
                                    <br>
                                    <span>{{$task->date}}</span>
                                </span>
                            </span>
                        <div class="controls">
                            <button onclick="setPriority({{$task->id}})">
                                <ion-icon name="star-outline"></ion-icon>
                                <ion-icon name="star"></ion-icon>
                            </button>
                            <button onclick="remove({{$task->id}}, 'Task')">
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>
                    </li>

                @empty
                    <li class="todoItem emptyList">
                        <div class="emptyContent">
                            <div class="text">
                                <p>{{$greeting['text']}}</p>
                            </div>
                            <div class="imgWrapper">
                                <img src="{{asset('img/'.$greeting['img'])}}" alt="calender">
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </section>
        <section class="taskInput">
            <form  id="taskForm" action="{{route('addTask')}}" method="POST">
                <input type="text" name="title" id="title" placeholder="Add Task" required>
                <div class="wrapper">
                    <div class="selectWrapper">
                        <select name="id" class="listSelect">
                            @foreach($lists as $list)
                                <option value="{{$list->id}}" @if($list->id == $todo_list_id) selected @endif  class="selectOption">{{$list->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="datePicker">
                        <label for="date" id="dateLabel"><ion-icon name="calendar"></ion-icon></label>
                        <input type="date" name="date" id="date" required>
                    </div>
                    <button type="submit"><ion-icon name="add-outline"></ion-icon></button>
                </div>
            </form>
        </section>
    </main>
@endsection
@section('modal')
    <div class="modalWrapper">
        <div class="modal">
            <button class="btn" onclick="closeModal()"><ion-icon name="close-outline"></ion-icon></button>
            <form  class="themeSelector" action="{{route('setTheme')}}" method="POST">
                {{csrf_field()}}
                <h5>Select Theme</h5>
                <ul class="themes">
                    @foreach($themes as $theme)
                        <input type="radio" id="{{$theme->name}}" name="selected" value="{{$theme->id}}" @if($loop->first) checked @endif>
                        <li class="theme @if($loop->first) selected @endif">
                            <label for="{{$theme->name}}"></label>
                            <div class="image"><img src="{{asset('img/'.$theme->img)}}" alt=""></div>
                            <p>{{$theme->name}}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="selectWrapper">
                    <select name="id" id="listSelect">
                        @foreach($lists as $list)
                            <option value="{{$list->id}}" @if($list->id == $todo_list_id) selected @endif  class="selectOption">{{$list->title}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit">
            </form>
        </div>
    </div>
@endsection
