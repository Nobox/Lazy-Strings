<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Lazy Strings v{{ $lazyVersion }}</title>

        <!-- Bootstrap core CSS -->
        <link href="{{ asset('vendor/nobox/lazy-strings/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="{{ asset('vendor/nobox/lazy-strings/css/cover.css') }}" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="{{ asset('vendor/nobox/lazy-strings/js/ie10-viewport-bug-workaround.js') }}"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div class="cover-container">
                    <div class="inner cover">
                        <h1 class="cover-heading">LazyStrings v{{ $lazyVersion }}</h1>
                        <p class="lead">Strings have been generated successfully!</p>
                        <p>Refreshed by: {{ $refreshedBy }}</p>
                        <p>Refreshed on: {{ $refreshedOn }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="{{ asset('packages/nobox/lazy-strings/js/bootstrap.min.js') }}"></script>
    </body>
</html>
