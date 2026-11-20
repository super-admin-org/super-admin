@if($upgradeNotice = Admin::upgrade()->notice())
    <div class="glass-alert-warning mb-5" role="alert">
        <i class="icon-sync mt-0.5"></i>
        <div class="flex-1 text-sm">
            <p class="font-semibold">{{ Admin::upgrade()->text('title') }}</p>
            <ul class="list-disc pl-5 mt-1 space-y-0.5">
                @if($upgradeNotice['assets'])
                    <li>{{ $upgradeNotice['published']
                        ? Admin::upgrade()->text('assets_outdated', ['published' => $upgradeNotice['published'], 'installed' => $upgradeNotice['installed']])
                        : Admin::upgrade()->text('assets_missing', ['installed' => $upgradeNotice['installed']]) }}</li>
                @endif
                @if($upgradeNotice['migrations'])
                    <li>{{ Admin::upgrade()->text('migrations', ['count' => $upgradeNotice['migrations']]) }}</li>
                @endif
            </ul>
            <p class="mt-2">{{ Admin::upgrade()->text('run') }}</p>
            <code class="select-all inline-block mt-1 rounded-md bg-white/70 border border-amber-200 px-2 py-1 font-mono text-xs text-gray-800">php artisan admin:upgrade</code>
            @unless($upgradeNotice['hook'])
                <p class="mt-2 text-xs opacity-80">{{ Admin::upgrade()->text('hook') }}</p>
            @endunless
        </div>
    </div>
@endif
