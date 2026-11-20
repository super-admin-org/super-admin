@if($help)
<span class="block text-xs text-gray-500 mt-1">
    <i class="{{ \Illuminate\Support\Arr::get($help, 'icon') }}"></i>&nbsp;{!! \Illuminate\Support\Arr::get($help, 'text') !!}
</span>
@endif
