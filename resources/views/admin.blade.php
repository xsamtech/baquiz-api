<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="/assets/img/favicon/site.webmanifest">
        <title>Baquiz Admin</title>
        @vite(['resources/css/app.css', 'resources/js/admin.jsx'])
    </head>
    <body class="bg-slate-50 antialiased">
        <div
            id="admin"
            data-path="{{ $path }}"
            data-can-register="{{ $canRegister ? 'true' : 'false' }}"
            data-errors='@json($errors->toArray())'
            data-old='@json(session()->getOldInput())'
            data-user='@json($userData ?? null)'
        ></div>
    </body>
</html>
