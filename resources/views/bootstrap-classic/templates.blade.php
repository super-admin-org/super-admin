@include('admin::partials.template-notices')

<div class="row g-4">
    @foreach($templates as $template)
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 {{ $template['isActive'] ? 'border-primary border-2' : '' }}">
                <div class="p-3 pb-0">
                    @if($template['preview'])
                        <img src="{{ $template['preview'] }}" alt="{{ $template['label'] }}" class="w-100 rounded border" style="height: 9rem; object-fit: cover;">
                    @else
                        <div class="d-flex rounded border overflow-hidden" style="height: 9rem; background: {{ $template['swatches']['background'] }};" aria-hidden="true">
                            <div class="h-100 p-2" style="width: 25%; background: {{ $template['swatches']['sidebar'] }};">
                                <div class="rounded-pill mb-2" style="height: .5rem; width: 75%; background: {{ $template['swatches']['primary'] }};"></div>
                                <div class="rounded-pill mb-1" style="height: .375rem; background: rgba(156, 163, 175, .4);"></div>
                                <div class="rounded-pill mb-1" style="height: .375rem; width: 80%; background: rgba(156, 163, 175, .4);"></div>
                                <div class="rounded-pill" style="height: .375rem; width: 65%; background: rgba(156, 163, 175, .4);"></div>
                            </div>
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="border-bottom" style="height: 1.5rem; background: {{ $template['swatches']['header'] }};"></div>
                                <div class="flex-grow-1 p-2">
                                    <div class="h-100 rounded bg-white border p-2">
                                        <div class="rounded-pill mb-2" style="height: .5rem; width: 3rem; background: {{ $template['swatches']['primary'] }};"></div>
                                        <div class="rounded-pill mb-1" style="height: .375rem; background: rgba(209, 213, 219, .7);"></div>
                                        <div class="rounded-pill mb-1" style="height: .375rem; background: rgba(209, 213, 219, .7);"></div>
                                        <div class="rounded-pill" style="height: .375rem; width: 75%; background: rgba(209, 213, 219, .7);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-body d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <h5 class="card-title mb-0">{{ $template['label'] }}</h5>
                        <div class="d-flex gap-1">
                            @if($template['isActive'])
                                <span class="badge bg-success">{{ Admin::template()->text('active') }}</span>
                            @endif
                            @if($template['isConfigured'])
                                <span class="badge bg-secondary">{{ Admin::template()->text('default') }}</span>
                            @endif
                        </div>
                    </div>

                    <small class="text-muted font-monospace">{{ $template['name'] }}@if($template['framework']) &middot; {{ $template['framework'] }}@endif</small>

                    @if($template['description'])
                        <p class="card-text mb-0">{{ $template['description'] }}</p>
                    @endif

                    <small class="{{ $template['missingViews'] ? 'text-warning' : 'text-success' }}">
                        <i class="{{ $template['missingViews'] ? 'icon-exclamation-triangle' : 'icon-check-circle' }}"></i>
                        {{ $template['missingViews'] ? Admin::template()->text('missing_views', ['count' => $template['missingViews']]) : Admin::template()->text('complete') }}
                    </small>

                    <div class="mt-auto pt-2">
                        @if($template['isActive'])
                            <button type="button" class="btn btn-light w-100" disabled>
                                <i class="icon-check"></i> {{ Admin::template()->text('active') }}
                            </button>
                        @else
                            <form method="POST" action="{{ route('admin.auth.templates.update') }}" class="m-0">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="template" value="{{ $template['name'] }}">
                                <button type="submit" class="btn btn-primary w-100" {{ $switcherEnabled && $canStore ? '' : 'disabled' }}>
                                    <i class="icon-paint-brush"></i> {{ Admin::template()->text('activate') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<p class="text-muted small mt-4">{!! Admin::template()->text('help') !!}</p>
