<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('admin.title') }} | {{ __('admin.login') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <link rel="stylesheet" href="{{ Admin::asset('tailwind-light/dist/super-admin.css') }}">
</head>

<body class="h-full antialiased"
    @if(config('admin.login_background_image'))
        style="background: url({{ config('admin.login_background_image') }}) no-repeat center center; background-size: cover;"
    @else
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);"
    @endif
>
    {{-- Animated background shapes --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>
    </div>

    {{-- Login card --}}
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ admin_url('/') }}" class="inline-flex items-center gap-3 no-underline">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-xl font-bold shadow-glass border border-white/30">
                        {!! config('admin.logo-mini', 'SA') !!}
                    </div>
                    <h1 class="text-2xl font-bold text-white drop-shadow-lg">{{ config('admin.name') }}</h1>
                </a>
            </div>

            {{-- Glass card --}}
            <div class="bg-white/20 backdrop-blur-[20px] border border-white/30 rounded-2xl shadow-glass-lg p-8 animate-fade-in">

                @if($errors->has('attempts'))
                    {{-- Throttle error --}}
                    <div class="bg-red-500/20 border border-red-400/30 backdrop-blur-sm rounded-xl p-4 text-center text-white">
                        <svg class="w-6 h-6 mx-auto mb-2 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <p class="text-sm font-medium">{{ $errors->first('attempts') }}</p>
                    </div>
                @else

                <form action="{{ admin_url('auth/login') }}" method="post" class="space-y-5">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-white/90 mb-1.5">{{ __('admin.username') }}</label>
                        @if($errors->has('username'))
                            <div class="text-red-300 text-xs mb-1.5 bg-red-500/20 rounded-lg px-3 py-1.5">{{ $errors->first('username') }}</div>
                        @endif
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/40">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                                class="w-full pl-10 pr-4 py-2.5 bg-white/15 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/40 focus:bg-white/20 transition-all"
                                placeholder="{{ __('admin.username') }}">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-white/90 mb-1.5">{{ __('admin.password') }}</label>
                        @if($errors->has('password'))
                            <div class="text-red-300 text-xs mb-1.5 bg-red-500/20 rounded-lg px-3 py-1.5">{{ $errors->first('password') }}</div>
                        @endif
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/40">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-10 pr-4 py-2.5 bg-white/15 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/40 focus:bg-white/20 transition-all"
                                placeholder="{{ __('admin.password') }}">
                        </div>
                    </div>

                    {{-- Remember me --}}
                    @if(config('admin.auth.remember'))
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-white/30 bg-white/15 text-primary-500 focus:ring-primary-500/50 focus:ring-offset-0">
                        <label for="remember" class="ml-2 text-sm text-white/80">{{ __('admin.remember_me') }}</label>
                    </div>
                    @endif

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-2.5 bg-white/25 hover:bg-white/35 border border-white/30 text-white font-semibold rounded-xl shadow-glass transition-all duration-300 hover:shadow-glass-lg hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('admin.login') }}
                    </button>
                </form>

                @endif
            </div>

            {{-- Footer --}}
            <p class="text-center text-white/40 text-xs mt-6">
                Powered by <a href="https://github.com/super-admin-org/super-admin" target="_blank" class="text-white/60 hover:text-white transition-colors">Super Admin</a>
            </p>
        </div>
    </div>

</body>
</html>
