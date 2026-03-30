<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Admin::title() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    {!! Admin::css() !!}
    <link rel="stylesheet" href="{{ Admin::asset('tailwind-light/dist/super-admin.css') }}">
    {!! Admin::headerJs() !!}
    {!! Admin::js() !!}
    {!! Admin::js_trans() !!}
    <script src="{{ Admin::asset('tailwind-light/dist/super-admin-js.js') }}"></script>
</head>

<body class="glass-bg antialiased">

    @if($alert = config('admin.top_alert'))
        <div class="glass-alert-warning mx-4 mt-2">
            {!! $alert !!}
        </div>
    @endif

    <div class="flex">

        @include('admin::partials.sidebar')

        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 lg:ml-[260px]" id="main-content">

            @include('admin::partials.header')

            <main id="main" class="flex-1 p-4 lg:p-6">
                <div id="pjax-container">
                <!--start-pjax-container-->
                    {!! Admin::style() !!}
                    <div id="app">
                        @yield('content')
                    </div>
                    {!! Admin::html() !!}
                    {!! Admin::script() !!}
                <!--end-pjax-container-->
                </div>
            </main>

            @include('admin::partials.footer')
        </div>
    </div>

    {{-- Back to top --}}
    <button id="totop" title="Go to top" class="fixed bottom-6 right-6 z-50 hidden glass-btn-icon shadow-glass-lg rounded-full p-3 hover:scale-110 transition-transform">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
    </button>

    <script>
        function LA() {}
        LA.token = "{{ csrf_token() }}";
        LA.user = @json($_user_);
    </script>

</body>
</html>
