<nav id="permanent-navbar" class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a id="homeNavTitle" class="navbar-brand" href="{{ url('/') }}">
            {{ config('home', 'okhs.tennis') }}
        </a>
{{--        <a class="navbar-brand" href="{{ url('/login') }}">--}}
{{--            {{ config('login', 'login') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/register') }}">--}}
{{--            {{ config('register', 'register') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/createtournament') }}">--}}
{{--            {{ config('createtournament', 'createtournament') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/tournaments') }}">--}}
{{--            {{ config('tournaments', 'tournaments') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/addschool') }}">--}}
{{--            {{ config('addschool', 'addschool') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/players') }}">--}}
{{--            {{ config('players', 'players') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/schools') }}">--}}
{{--            {{ config('schools', 'schools') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/school/1') }}">--}}
{{--            {{ config('school/1', 'school/1') }}--}}
{{--        </a>--}}
{{--        <a class="navbar-brand" href="{{ url('/tournament/51') }}">--}}
{{--            {{ config('tournament/51', 'tournament/51') }}--}}
{{--        </a>--}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <a id="createTournamentNavTitle" class="navbar-brand" href="{{ url('/createtournament') }}">
                    {{ config('createtournament', 'Create Tournament') }}
                </a>
                <a id="viewTournamentsNavTitle" class="navbar-brand" href="{{ url('/tournaments') }}">
                    {{ config('tournaments', 'View Tournaments') }}
                </a>
                <a id="playerRankingsNavTitle" class="navbar-brand" href="{{ url('/players') }}">
                    {{ config('players', 'Player Rankings') }}
                </a>
                <a id="viewSchoolsNavTitle" class="navbar-brand" href="{{ url('/schools') }}">
                    {{ config('schools', 'View Schools') }}
                </a>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a id="loginNavTitle" class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a id="registerNavTitle" class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<span id="blue-line"></span>




