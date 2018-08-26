<nav class="navbar navbar-expand-sm bg-dark text-white">
    <!-- Links -->
    @guest
        <ul class="navbar-nav mr-auto"></ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
        </ul>

    @else
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('index-item') }}">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('create-item') }}">Create</a>
        </li>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('logout') }}">Logout</a>
        </li>
    </ul>
    @endguest
</nav>
