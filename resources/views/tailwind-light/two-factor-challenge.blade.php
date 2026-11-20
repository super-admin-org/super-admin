<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('admin.title') }} | {{ Admin::twoFactor()->text('title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <link rel="stylesheet" href="{{ Admin::asset('tailwind-light/dist/super-admin.css') }}">
    <link rel="stylesheet" href="{{ Admin::asset('tailwind-light/dist/super-admin-icons.css') }}">
</head>

<body class="h-full antialiased"
    @if(config('admin.login_background_image'))
        style="background: url({{ config('admin.login_background_image') }}) no-repeat center center; background-size: cover;"
    @else
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);"
    @endif
>
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md">

            <div class="text-center mb-8">
                <a href="{{ admin_url('/') }}" class="inline-flex items-center gap-3 no-underline">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-xl font-bold shadow-glass border border-white/30">
                        {!! config('admin.logo-mini', 'SA') !!}
                    </div>
                    <h1 class="text-2xl font-bold text-white drop-shadow-lg">{{ config('admin.name') }}</h1>
                </a>
            </div>

            <div class="bg-white/20 backdrop-blur-[20px] border border-white/30 rounded-2xl shadow-glass-lg p-8 animate-fade-in text-white">

                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center text-lg"><i class="icon-shield-alt"></i></span>
                    <h2 class="text-lg font-semibold">{{ Admin::twoFactor()->text('title') }}</h2>
                </div>
                <p class="text-sm text-white/80 mb-6">{{ Admin::twoFactor()->text('challenge_help', ['name' => $account]) }}</p>

                <form action="{{ admin_url('auth/two-factor/challenge') }}" method="post" class="space-y-5">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div>
                        <label for="code" class="block text-sm font-medium text-white/90 mb-1.5">{{ Admin::twoFactor()->text('code') }}</label>
                        @if($errors->has('code'))
                            <div class="text-red-100 text-xs mb-1.5 bg-red-500/30 rounded-lg px-3 py-1.5">{{ $errors->first('code') }}</div>
                        @endif
                        <input type="text" name="code" id="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9 ]*" maxlength="7" placeholder="000000" required autofocus
                            class="w-full px-4 py-3 bg-white/15 border border-white/20 rounded-xl text-white text-center text-2xl font-mono tracking-[0.5em] placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-white/40 focus:bg-white/20 transition-all">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-white/25 hover:bg-white/35 border border-white/30 text-white font-semibold rounded-xl shadow-glass transition-all duration-300 hover:shadow-glass-lg hover:scale-[1.02] active:scale-[0.98]">
                        {{ Admin::twoFactor()->text('verify') }}
                    </button>
                </form>

                <details class="mt-6 group" @if($errors->has('recovery_code')) open @endif>
                    <summary class="cursor-pointer text-sm text-white/80 hover:text-white">{{ Admin::twoFactor()->text('use_recovery_code') }}</summary>

                    <form action="{{ admin_url('auth/two-factor/challenge') }}" method="post" class="space-y-3 mt-3">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <p class="text-xs text-white/70">{{ Admin::twoFactor()->text('challenge_recovery_help') }}</p>

                        @if($errors->has('recovery_code'))
                            <div class="text-red-100 text-xs bg-red-500/30 rounded-lg px-3 py-1.5">{{ $errors->first('recovery_code') }}</div>
                        @endif

                        <label for="recovery_code" class="sr-only">{{ Admin::twoFactor()->text('recovery_code') }}</label>
                        <input type="text" name="recovery_code" id="recovery_code" autocomplete="off" placeholder="xxxxx-xxxxx" required
                            class="w-full px-4 py-2.5 bg-white/15 border border-white/20 rounded-xl text-white font-mono placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-white/40 focus:bg-white/20 transition-all">

                        <button type="submit" class="w-full py-2 bg-white/15 hover:bg-white/25 border border-white/30 text-white text-sm font-medium rounded-xl transition-all">
                            {{ Admin::twoFactor()->text('verify') }}
                        </button>
                    </form>
                </details>

                <p class="text-center text-sm mt-6">
                    <a href="{{ admin_url('auth/login') }}" class="text-white/70 hover:text-white transition-colors">{{ Admin::twoFactor()->text('back_to_login') }}</a>
                </p>
            </div>

            <p class="text-center text-white/40 text-xs mt-6">
                Powered by <a href="https://github.com/super-admin-org/super-admin" target="_blank" class="text-white/60 hover:text-white transition-colors">Super Admin</a>
            </p>
        </div>
    </div>
</body>
</html>
