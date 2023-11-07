<nav id="navbar" class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{route('notepad')}}">
            {{__('Notepad')}}
        </a>
        <a class="custom-toggler icon-container toggle-mobile-menu" id="toggle-mobile-menu">
            <img src="{{asset('images/bars.png')}}" alt="">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <input type="checkbox" name="switcher" id="switcher-input" class="switcher-input">
                    <!--label with images and span to change color-->
                    <label class="switcher-label" for="switcher-input">
                        <i class='fas fa-solid fa-moon'></i>
                        <span class="switcher-toggler"></span>
                        <i class='fas fa-solid fa-sun'></i>
                    </label>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            {{ __('Login') }}
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

