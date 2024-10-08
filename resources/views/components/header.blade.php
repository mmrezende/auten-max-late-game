<header>
    <div class="px-3 py-2 header d-flex align-items-center justify-content-center">
        <div class="container d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center me-4 me-sm-0 text-decoration-none me-lg-auto">
                <img class="img-header-logo"/>
            </a>
            <nav class="hidden d-flex flex-row align-items-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('home') }}" class="header-link">Acessar Plataforma</a>
                    @else
                        <a href="{{ route('login') }}" class="header-link me-2 me-sm-4{{ Route::currentRouteName() !== 'login' ? '' : ' fw-bold' }}">Entrar</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ms-2 ms-sm-4 header-link{{ Route::currentRouteName() !== 'register' ? '': ' fw-bold' }}">Cadastrar-se</a>
                        @endif
                    @endauth
                @endif

                @if (Auth::check())
                    <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                        <button type="submit" class="btn btn-link ms-2 ms-sm-4 header-link">Logout</button>
                    </form>
                @endif
            </nav>
        </div>
    </div>
</header>