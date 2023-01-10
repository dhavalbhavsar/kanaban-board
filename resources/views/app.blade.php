<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Scripts -->
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        
    </head>

    <body translate="no">
        <div class="wrapper" id="app">
            <kanban-board></kanban-board>

            <a href="{{ route('export.db') }}"  class="export-db-button" title="Export DB">Export Database</a>
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>