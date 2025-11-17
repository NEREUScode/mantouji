<div class="nav-container">
        <div class="navbar">
            <div class="hidden">
                <ul>
                    <li><a href="#acc">Accueil</a></li>
                    <li><a href="#propo">À propos</a></li>
                    <li><a href="#service">Services</a></li>
                    <li><a href="#conta">اتصل</a></li>
                </ul>
            </div>
            <div class="nav-icone">
                <img id="nav-toggle" src="images/icones/nav-icone.png" alt="" srcset="">
            </div>
            <div class="nav-logo">
                <img src="images/logo.png" alt="" srcset="">
            </div>
            <div class="nav-links">
                @if (Route::has('login'))
                
                    @auth
                        <button><a href="{{ url('/dashboard') }}">Dashboard</a></button>
                    @else
                        <button><a href="{{Route('login')}}">Login</a></button>
                    @endauth
                @endif
                
                {{-- <button><a href="{{Route('login')}}">Login</a></button> --}}
            </div>
        </div>

        <div id="side-nav" class="side-nav">
            <span id="close-nav" class="close-btn">&times;</span>
            <ul>
                <li><a href="#acc">Accueil</a></li>
                <li><a href="#propo">À propos</a></li>
                <li><a href="#service">Services</a></li>
                <li><a href="#conta">اتصل</a></li>
            </ul>
        </div>
    </div>