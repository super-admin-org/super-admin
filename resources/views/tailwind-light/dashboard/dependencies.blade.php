<div class="glass-card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200/30">
        <h3 class="text-sm font-semibold text-gray-700">Dependencies</h3>
        <button data-sa-toggle="collapse" data-sa-target="#deps-body" class="p-1 rounded hover:bg-gray-100/50 text-gray-400 transition-colors">
            <svg class="w-4 h-4 collapse-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
    </div>
    <div id="deps-body">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <tbody>
                    @foreach($dependencies as $dependency => $version)
                    <tr>
                        <td class="font-medium text-gray-600">{{ $dependency }}</td>
                        <td><span class="glass-badge-primary">{{ $version }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
