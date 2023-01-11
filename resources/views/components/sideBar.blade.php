<div class="sidebar">
    <div class="user">
        <div class="profile-img">
            <img src="{{asset($user->profile)}}" alt="">
        </div>
        <div class="info">
            <span class="name">{{$user->name}}</span>
            <br>
            <span class="email">{{$user->email}}</span>
        </div>
        <button class="btn editInfo" onclick="openModal('editUserInfo')">
            Edit profile
        </button>
        <form class="logoutForm" action="{{route('logout')}}" method="POST">
            <input type="submit" id="logout">
            <label for="logout"><ion-icon name="log-out"></ion-icon></label>
        </form>
    </div>
    <div class="nav">
        <ul class="navigation default">
            <li>
                <a href="{{route('myDay')}}" class="navItem navLink" theme="1">
                    <ion-icon name="sunny-outline"></ion-icon>
                    <span class="navTitle">My Day</span>
                </a>
            </li>
            <li>
                <a href="{{route('all')}}" class="navItem navLink" theme="2">
                    <ion-icon name="infinite-outline"></ion-icon>
                    <span class="navTitle">All</span>
                </a>
            </li>
            <li>
                <a href="{{route('complete')}}" class="navItem navLink" theme="3">
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                    <span class="navTitle">Complete</span>
                </a>
            </li>
        </ul>
        <div class="userListsWrapper">
            <ul class="navigation userLists">
                @foreach($lists as $list)
                    @php $url = route('list',$list->id) @endphp
                    <li>
                        <a href="{{$url}}"  class="navItem navLink @if($url == url()->current()) active @endif" theme="{{$list->theme_id}}">
                            <ion-icon name="albums-outline"></ion-icon>
                            <span class="navTitle">{{$list->title}}</span>
                        </a>
                        <button onclick="remove({{$list->id}}, 'List')">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <form class="controls" id="listForm" method="POST" action="{{route('addList')}}">
        <input type="text" name="title" placeholder="New List" maxlength="15" required>
        <button type="submit" class="btn btn-sq"><ion-icon name="add-outline"></ion-icon></button>
    </form>
</div>
