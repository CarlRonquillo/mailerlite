<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MailerLite | Add API Key</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="d-flex text-center">
    <div class="container keys-wrapper border p-5 bg-white">
        <p class="h1">Howdy!</p>
        <p class="h3 mb-3">please enter your <img class="mailerlite-logo px-1" src="{{ asset('img/mailerlite-logo.png') }}" alt="MailerLite-logo"> API Key</p>

        @error('api_key')
            <div class="alert alert-danger alert-block col-md-12">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @enderror

        @include('layouts/flash-message')

        <form class="mt-2 row" action="/keys" method="POST">
            <input name="api_key" class="col-md-10 offset-md-1 form-control form-control-lg" type="text"
                placeholder="your API Key here" aria-label=".form-control-lg example" value="{{ session('key') }}">
            <div class="col-md-12 mt-4">
                <button type="submit" class="col-md-3 btn btn-primary btn-lg ">Submit</button>
            </div>
        </form>

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>