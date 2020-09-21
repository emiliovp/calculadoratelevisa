<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Calculadora') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    
    <!-- Adicionales datatables -->
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/adicionales_datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/adicionales_datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('js/adicionales_datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/adicionales_datatables/vfs_fonts.js') }}"></script>

    <!-- <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script> -->
    <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Adicionales datatables -->
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <!-- jquery-ui -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- end jquery-ui -->
    <!-- css jquery ui -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <!-- end css jquery ui -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}">

    <script src="{{ asset('js/loading.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Calculadora') }}
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
                        @guest
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="https://portal_pcb.televisa.net/televisa_triada/pes2018/public/index.php">{{ __('Home') }}</a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li> -->
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        sessionStorage.desactivaLogout = 1;

        function mostrarError(id = null) {
            $("#"+id).addClass("error-msj-activo");
            $("#"+id).removeClass("error-msj-inactivo");
        }
        function ocultarError(id = null) {
            $("#"+id).removeClass("error-msj-activo");
            $("#"+id).addClass("error-msj-inactivo");
        }

        function logout() {
            if(sessionStorage.desactivaLogout == 1) {
                document.getElementById('logout-form').submit();
            }
        }

        var mouseStop = null;
        var Time = 600000; // Tiempo en milisegundos que espera para efectuarse la funcion

        $(document).on('mousemove', function() {
            clearTimeout(mouseStop);
            mouseStop = setTimeout(logout,Time);
        });
    @auth
        var id = "{{ Auth::user()->noEmployee }}";
        var modulo = $('#modulo').val();
        var url = "{{ url('validateaccesos') }}";
        if(modulo != undefined) { 
            $.ajax({
                type: 'GET',
                url: url+'/'+id+'/'+modulo,
                async: false 
            }).done(function(response){
                if(response[0] == 'failed') {
                    swal({
                        title: 'Acceso Denegado',
                        text: "Su perfil no cuenta con permisos para acceder a este sitio",
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'Regresar'
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = "{{ route('home') }}";
                        }
                    })

                }
            });  
        }
    @endauth
    </script>
    @stack('scripts')
    <div id="content-loading" class="text-center loading_hide">
        <div id="loading-logo">
            <img src="{{ asset('images/procesando.gif') }}"/>
        </div>
    </div>
</body>
</html>
