<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>

<body>
    <div class="container">
        <header class="d-flex py-3 border-bottom row">
            <ul class="nav nav-pills justify-content-left col-md-6">
                <li class="nav-item">
                    <a class="navbar-brand" href="/">
                        <img src="https://www.mailerlite.com/assets/logo-color.png" alt="MailerLite">
                    </a>
                </li>
                <li class="nav-item {{ request()->is('subscribers') ? 'active' : '' }}">
                    <a href="/subscribers" class="nav-link text-dark" aria-current="page">Subscribers List</a>
                </li>
                <li class="nav-item {{ request()->is('subscribers/create') ? 'active' : '' }}">
                    <a href="/subscribers/create" class="nav-link text-dark" aria-current="page">Add Subscriber</a>
                </li>
            </ul>
            <div class="col-md-6 text-right key-wrapper">
                Your API Key: <span>{{ Session::get('key') }}</span>
                <form action="/keys/{{ Session::get('key_id') }}" method="POST">    
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </button>
                </form>
                
            </div>
        </header>
    </div>

    @yield('content')

</body>
</html>

<script type="text/javascript" language="javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
@stack('scripts')