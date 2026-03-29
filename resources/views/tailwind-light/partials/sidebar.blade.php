{{-- Glassmorphism Sidebar --}}
<aside id="sidebar" class="glass-sidebar fixed lg:sticky top-0 left-0 z-50 h-full w-[260px] flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 glass-scrollbar overflow-y-auto">

    {{-- Logo --}}
    <div class="flex items-center h-16 px-5 border-b border-white/[0.08] flex-shrink-0">
        <a href="{{ admin_url('/') }}" class="flex items-center gap-2 text-white no-underline">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-sm font-bold shadow-glow">
                {!! config('admin.logo-mini', 'SA') !!}
            </div>
            <span class="text-lg font-semibold sidebar-title">{!! config('admin.logo', config('admin.name')) !!}</span>
        </a>
    </div>

    @if(config('admin.enable_user_panel'))
    {{-- User panel --}}
    <div class="px-4 py-4 border-b border-white/[0.08]">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-full overflow-hidden bg-white/10 ring-2 ring-white/20">
                <img src="{{ Admin::user()->avatar }}" alt="Avatar" class="w-full h-full object-cover">
            </span>
            <div>
                <div class="text-sm font-medium text-white">{{ Admin::user()->name }}</div>
                <div class="flex items-center gap-1 text-xs text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    {{ trans('admin.online') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(config('admin.enable_menu_search'))
    {{-- Search --}}
    <div class="px-3 pt-4 pb-2">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" autocomplete="off" class="autocomplete w-full pl-9 pr-3 py-2 bg-white/[0.08] border border-white/[0.1] rounded-lg text-sm text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary-500/50 focus:bg-white/[0.12] transition-all" placeholder="Search menu...">
            <ul class="dropdown-menu hidden absolute left-0 right-0 mt-1 bg-slate-800/95 backdrop-blur-md rounded-lg border border-white/10 shadow-glass-lg max-h-60 overflow-auto z-50">
                @foreach(Admin::menuLinks() as $link)
                <li>
                    <a href="{{ admin_url($link['uri']) }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
                        <i class="{{ $link['icon'] }} w-4 text-center"></i>
                        {{ admin_trans($link['title']) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Menu --}}
    <nav class="flex-1 px-2 py-3 overflow-y-auto glass-scrollbar">
        <ul class="space-y-0.5" id="menu">
            @each('admin::partials.menu', Admin::menu(), 'item')
        </ul>
    </nav>

    {{-- Sidebar footer --}}
    <div class="px-4 py-3 border-t border-white/[0.08] text-xs text-gray-500 flex-shrink-0">
        Super Admin v{{ \SuperAdmin\Admin\Admin::VERSION }}
    </div>
</aside>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden lg:hidden" onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden');"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const menuToggle = document.getElementById('menu-toggle');
    const collapseToggle = document.getElementById('sidebar-collapse-toggle');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
    }

    if (collapseToggle) {
        collapseToggle.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
});
</script>
