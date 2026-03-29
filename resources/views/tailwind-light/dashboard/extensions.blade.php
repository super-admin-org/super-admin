<div class="glass-card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200/30">
        <h3 class="text-sm font-semibold text-gray-700">Available Extensions</h3>
        <button data-sa-toggle="collapse" data-sa-target="#ext-body" class="p-1 rounded hover:bg-gray-100/50 text-gray-400 transition-colors">
            <svg class="w-4 h-4 collapse-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
    </div>
    <div id="ext-body">
        <ul class="divide-y divide-gray-100/50">
            @foreach($extensions as $extension)
            <li class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50/30 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-primary-600">
                    <i class="icon-{{ $extension['icon'] }} text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ $extension['link'] }}" target="_blank" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                        {{ $extension['name'] }}
                    </a>
                </div>
                @if($extension['installed'])
                    <span class="glass-badge-success">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Installed
                    </span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    <div class="px-5 py-3 border-t border-gray-200/30 text-center">
        <a href="https://github.com/super-admin-org" target="_blank" class="text-xs font-medium text-primary-600 hover:text-primary-700 uppercase tracking-wider transition-colors">
            View All Extensions
        </a>
    </div>
</div>
