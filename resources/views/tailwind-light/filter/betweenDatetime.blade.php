<div class="flex items-center gap-2">
    <span class="text-gray-400 shrink-0"><i class="icon-calendar"></i></span>
    <input type="text"
           class="glass-input text-sm flex-1"
           id="{{$id['start']}}"
           placeholder="{{$label}}"
           name="{{$name['start']}}"
           value="{{ request()->input("{$column}.start", \Illuminate\Support\Arr::get($value, 'start')) }}"
           autocomplete="off"
    />
    <span class="text-gray-400 shrink-0">—</span>
    <input type="text"
           class="glass-input text-sm flex-1"
           id="{{$id['end']}}"
           placeholder="{{$label}}"
           name="{{$name['end']}}"
           value="{{ request()->input("{$column}.end", \Illuminate\Support\Arr::get($value, 'end')) }}"
           autocomplete="off"
    />
</div>
