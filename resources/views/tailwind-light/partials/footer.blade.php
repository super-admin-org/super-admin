{{-- Footer --}}
<footer class="px-4 lg:px-6 py-3 border-t border-gray-200/30 bg-white/30 backdrop-blur-sm">
    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-2">
        <div>
            Powered by <a href="https://github.com/super-admin-org/super-admin" target="_blank" class="text-gray-500 hover:text-primary-600 transition-colors font-medium">Super Admin</a>
        </div>
        <div class="flex items-center gap-4">
            @if(config('admin.show_environment'))
                <span class="glass-badge bg-gray-100/60 text-gray-500">{{ config('app.env') }}</span>
            @endif
            @if(config('admin.show_version'))
                <span>v{{ \SuperAdmin\Admin\Admin::VERSION }}</span>
            @endif
        </div>
    </div>
</footer>
