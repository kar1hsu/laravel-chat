<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">


        <el-container>
            <el-header>
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                    <div class="container">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav mr-auto">

                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ml-auto">
                                <!-- Authentication Links -->
                                <li class="nav-item" id="login">
                                    <a href="/login" class="nav-link">login</a>
                                </li>
                                <li class="nav-item" id="register">
                                    <a href="/register" class="nav-link">register</a>
                                </li>
                                <li id='show-my-name' class="nav-item dropdown" style="display: none" >
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a href="/friend" class="dropdown-item">MyFriends</a>
                                        <a class="dropdown-item" href=""
                                           onclick="logout()">
                                            logout
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </el-header>
        </el-container>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script>
    window.onload = function(){
        if(localStorage.getItem("is_login") !== null){
            document.getElementById("login").style.display="none";
            document.getElementById("register").style.display="none";
            document.getElementById("show-my-name").style.display="block";
            document.getElementById("navbarDropdown").innerHTML = localStorage.getItem("name");
        }
    }
    function logout() {
        localStorage.removeItem('is_login');
        localStorage.removeItem('name');
        localStorage.removeItem('token');
        localStorage.removeItem('uuid');
        window.location.href="/login";
    }
</script>
</html>
