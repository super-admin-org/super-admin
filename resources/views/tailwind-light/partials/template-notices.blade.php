@if(! $switcherEnabled)
    <div class="glass-alert-warning mb-5">
        <i class="icon-lock mt-0.5"></i>
        <div class="flex-1 text-sm">{{ Admin::template()->text('disabled') }}</div>
    </div>
@elseif(! $canStore)
    <div class="glass-alert-warning mb-5">
        <i class="icon-exclamation-triangle mt-0.5"></i>
        <div class="flex-1 text-sm">{{ Admin::template()->text('not_migrated') }}</div>
    </div>
@else
    <div class="glass-alert-info mb-5 items-center">
        <i class="icon-info-circle"></i>
        <div class="flex-1 text-sm">
            {{ $stored ? Admin::template()->text('source_panel') : Admin::template()->text('source_config') }}
        </div>
        @if($stored)
            <form method="POST" action="{{ route('admin.auth.templates.destroy') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="glass-btn-secondary glass-btn-sm">
                    <i class="icon-undo"></i> {{ Admin::template()->text('reset') }}
                </button>
            </form>
        @endif
    </div>
@endif
