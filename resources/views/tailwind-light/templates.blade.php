@include('admin::partials.template-notices')

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @foreach($templates as $template)
        <div class="glass-card overflow-hidden flex flex-col {{ $template['isActive'] ? 'ring-2 ring-primary-500/70' : '' }}">
            <div class="p-4 pb-0">
                @if($template['preview'])
                    <img src="{{ $template['preview'] }}" alt="{{ $template['label'] }}" class="w-full h-36 object-cover rounded-xl border border-gray-200/60">
                @else
                    <div class="h-36 rounded-xl overflow-hidden border border-gray-200/60 flex" style="background: {{ $template['swatches']['background'] }}" aria-hidden="true">
                        <div class="w-1/4 h-full p-2 space-y-1.5" style="background: {{ $template['swatches']['sidebar'] }}">
                            <div class="h-2 w-3/4 rounded-full" style="background: {{ $template['swatches']['primary'] }}"></div>
                            <div class="h-1.5 w-full rounded-full bg-gray-400/40"></div>
                            <div class="h-1.5 w-5/6 rounded-full bg-gray-400/40"></div>
                            <div class="h-1.5 w-2/3 rounded-full bg-gray-400/40"></div>
                        </div>
                        <div class="flex-1 flex flex-col">
                            <div class="h-6 border-b border-gray-200/60" style="background: {{ $template['swatches']['header'] }}"></div>
                            <div class="flex-1 p-2.5">
                                <div class="h-full rounded-lg bg-white/80 border border-gray-200/60 p-2 space-y-1.5">
                                    <div class="h-2 w-12 rounded-full" style="background: {{ $template['swatches']['primary'] }}"></div>
                                    <div class="h-1.5 w-full rounded-full bg-gray-300/60"></div>
                                    <div class="h-1.5 w-full rounded-full bg-gray-300/60"></div>
                                    <div class="h-1.5 w-3/4 rounded-full bg-gray-300/60"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="p-4 flex-1 flex flex-col gap-2">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-gray-800">{{ $template['label'] }}</h3>
                    <div class="flex items-center gap-1.5">
                        @if($template['isActive'])
                            <span class="badge bg-success">{{ Admin::template()->text('active') }}</span>
                        @endif
                        @if($template['isConfigured'])
                            <span class="badge bg-secondary">{{ Admin::template()->text('default') }}</span>
                        @endif
                    </div>
                </div>

                <p class="text-xs font-mono text-gray-400">{{ $template['name'] }}@if($template['framework']) &middot; {{ $template['framework'] }}@endif</p>

                @if($template['description'])
                    <p class="text-sm text-gray-600">{{ $template['description'] }}</p>
                @endif

                <p class="text-xs {{ $template['missingViews'] ? 'text-amber-600' : 'text-emerald-600' }}">
                    <i class="{{ $template['missingViews'] ? 'icon-exclamation-triangle' : 'icon-check-circle' }}"></i>
                    {{ $template['missingViews'] ? Admin::template()->text('missing_views', ['count' => $template['missingViews']]) : Admin::template()->text('complete') }}
                </p>

                <div class="mt-auto pt-2">
                    @if($template['isActive'])
                        <button type="button" class="glass-btn-secondary w-full justify-center" disabled>
                            <i class="icon-check"></i> {{ Admin::template()->text('active') }}
                        </button>
                    @else
                        <form method="POST" action="{{ route('admin.auth.templates.update') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="template" value="{{ $template['name'] }}">
                            <button type="submit" class="glass-btn-primary w-full justify-center" {{ $switcherEnabled && $canStore ? '' : 'disabled' }}>
                                <i class="icon-paint-brush"></i> {{ Admin::template()->text('activate') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<p class="text-xs text-gray-500 mt-6">{!! Admin::template()->text('help') !!}</p>
