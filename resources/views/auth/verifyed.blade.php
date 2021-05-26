<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        
    </head>
    <body class="antialiased">
        <div id="app">
    
            <main class="">
                <div class="container">
                    @if ($is_verified)
                        <div>
                            登録が完了しました。以下のリンクからログインできます。
                            <a href="/login" class="btn btn-primary">ログイン</a>
                        </div>
                    @else
                        <div>
                            認証に失敗しました。再度認証するために以下のリンクから新たに認証メールを受け取りください。
                            <a href="">認証メールを受け取る</a>
                        </div>
                    @endif
                </div>
            </main>
            
        </div>
        <!-- Scripts -->
        {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    </body>
</html>