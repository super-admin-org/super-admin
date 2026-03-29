@if($error = session()->get('error'))
    <div class="glass-alert-danger mb-4 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="flex-1">
            <h4 class="font-semibold">{{ \Illuminate\Support\Arr::get($error->get('title'), 0) }}</h4>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($error->get('message'), 0) !!}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@elseif ($errors = session()->get('errors'))
    @if ($errors->hasBag('error'))
        <div class="glass-alert-danger mb-4 animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="flex-1">
                @foreach($errors->getBag("error")->toArray() as $message)
                    <p class="text-sm">{!! \Illuminate\Support\Arr::get($message, 0) !!}</p>
                @endforeach
            </div>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
@endif

@if($success = session()->get('success'))
    <div class="glass-alert-success mb-4 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="flex-1">
            <h4 class="font-semibold">{{ \Illuminate\Support\Arr::get($success->get('title'), 0) }}</h4>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($success->get('message'), 0) !!}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

@if($info = session()->get('info'))
    <div class="glass-alert-info mb-4 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="flex-1">
            <h4 class="font-semibold">{{ \Illuminate\Support\Arr::get($info->get('title'), 0) }}</h4>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($info->get('message'), 0) !!}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

@if($warning = session()->get('warning'))
    <div class="glass-alert-warning mb-4 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div class="flex-1">
            <h4 class="font-semibold">{{ \Illuminate\Support\Arr::get($warning->get('title'), 0) }}</h4>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($warning->get('message'), 0) !!}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif
