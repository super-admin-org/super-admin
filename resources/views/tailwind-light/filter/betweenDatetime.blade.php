<div class="flex items-start gap-3">
    <label class="text-sm font-medium text-gray-600 shrink-0 pt-2 text-right" style="min-width:6rem;max-width:8rem">{{$label}}</label>
    <div class="flex-1 min-w-0 flex items-center gap-2">
        <span class="text-gray-400 shrink-0"><i class="icon-calendar"></i></span>
        <input type="text"
               class="glass-input text-sm flex-1 min-w-0"
               id="{{$id['start']}}"
               placeholder="{{$label}}"
               name="{{$name['start']}}"
               value="{{ request()->input("{$column}.start", \Illuminate\Support\Arr::get($value, 'start')) }}"
               autocomplete="off"
        />
        <span class="text-gray-400 shrink-0">—</span>
        <input type="text"
               class="glass-input text-sm flex-1 min-w-0"
               id="{{$id['end']}}"
               placeholder="{{$label}}"
               name="{{$name['end']}}"
               value="{{ request()->input("{$column}.end", \Illuminate\Support\Arr::get($value, 'end')) }}"
               autocomplete="off"
        />
    </div>
</div>
