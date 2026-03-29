<div class="flex items-center gap-2">
    <input type="text" class="glass-input text-sm flex-1" placeholder="{{$label}}" name="{{$name['start']}}" value="{{ request()->input("{$column}.start", \Illuminate\Support\Arr::get($value, 'start')) }}">
    <span class="text-gray-400 shrink-0">—</span>
    <input type="text" class="glass-input text-sm flex-1" placeholder="{{$label}}" name="{{$name['end']}}" value="{{ request()->input("{$column}.end", \Illuminate\Support\Arr::get($value, 'end')) }}">
</div>
