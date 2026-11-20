@if(! $switcherEnabled)
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="icon-lock"></i>
        <div>{{ Admin::template()->text('disabled') }}</div>
    </div>
@elseif(! $canStore)
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="icon-exclamation-triangle"></i>
        <div>{{ Admin::template()->text('not_migrated') }}</div>
    </div>
@else
    <div class="alert alert-info d-flex align-items-center gap-2">
        <i class="icon-info-circle"></i>
        <div class="flex-grow-1">
            {{ $stored ? Admin::template()->text('source_panel') : Admin::template()->text('source_config') }}
        </div>
        @if($stored)
            <form method="POST" action="{{ route('admin.auth.templates.destroy') }}" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-light">
                    <i class="icon-undo"></i> {{ Admin::template()->text('reset') }}
                </button>
            </form>
        @endif
    </div>
@endif
