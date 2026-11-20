@if($upgradeNotice = Admin::upgrade()->notice())
    <div class="alert alert-warning d-flex gap-3 align-items-start" role="alert">
        <i class="icon-sync mt-1"></i>
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-1">{{ Admin::upgrade()->text('title') }}</h6>
            <ul class="small mb-2 ps-3">
                @if($upgradeNotice['assets'])
                    <li>{{ $upgradeNotice['published']
                        ? Admin::upgrade()->text('assets_outdated', ['published' => $upgradeNotice['published'], 'installed' => $upgradeNotice['installed']])
                        : Admin::upgrade()->text('assets_missing', ['installed' => $upgradeNotice['installed']]) }}</li>
                @endif
                @if($upgradeNotice['migrations'])
                    <li>{{ Admin::upgrade()->text('migrations', ['count' => $upgradeNotice['migrations']]) }}</li>
                @endif
            </ul>
            <p class="small mb-1">{{ Admin::upgrade()->text('run') }}</p>
            <code class="user-select-all">php artisan admin:upgrade</code>
            @unless($upgradeNotice['hook'])
                <p class="small text-muted mt-2 mb-0">{{ Admin::upgrade()->text('hook') }}</p>
            @endunless
        </div>
    </div>
@endif
