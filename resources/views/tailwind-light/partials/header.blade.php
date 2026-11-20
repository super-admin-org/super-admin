{{-- Glassmorphism Header --}}
<header class="glass-header sticky top-0 z-40 px-4 lg:px-6">
    <div class="flex items-center justify-between h-16">

        {{-- Left: Menu toggle --}}
        <div class="flex items-center gap-3">
            <button id="menu-toggle" class="p-2 rounded-lg text-gray-500 hover:bg-white/50 hover:text-gray-700 transition-colors lg:hidden" aria-label="Toggle navigation">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <button id="sidebar-collapse-toggle" class="hidden lg:block p-2 rounded-lg text-gray-500 hover:bg-white/50 hover:text-gray-700 transition-colors" aria-label="Collapse sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Left navbar items --}}
            <div class="hidden md:flex items-center">
                {!! Admin::getNavbar()->render('left') !!}
            </div>
        </div>

        {{-- Right: Nav items + User menu --}}
        <div class="flex items-center gap-2">

            {!! Admin::getNavbar()->render() !!}

            {{-- User dropdown --}}
            <div class="relative">
                <button data-sa-toggle="dropdown" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/50 transition-colors cursor-pointer">
                    <span class="w-8 h-8 rounded-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center ring-2 ring-white/50">
                        <img src="{{ Admin::user()->avatar }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.style.display='none'">
                    </span>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Admin::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div data-sa-dropdown class="hidden absolute right-0 mt-2 w-64 glass-panel-solid rounded-xl shadow-glass-lg overflow-hidden animate-slide-down z-50">
                    {{-- User info --}}
                    <div class="px-4 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white">
                        <div class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-full overflow-hidden bg-white/20 flex items-center justify-center ring-2 ring-white/30">
                                <img src="{{ Admin::user()->avatar }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.style.display='none'">
                            </span>
                            <div>
                                <div class="font-semibold">{{ Admin::user()->name }}</div>
                                <div class="text-xs text-white/70">Member since {{ Admin::user()->created_at->format('M Y') }}</div>
                            </div>
                        </div>
                    </div>
                    {{-- Actions --}}
                    <div class="p-2">
                        <a href="{{ admin_url('auth/setting') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100/60 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ __('admin.setting') }}
                        </a>
                        <a href="{{ admin_url('auth/logout') }}" class="no-ajax flex items-center gap-2 px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50/60 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            {{ __('admin.logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
