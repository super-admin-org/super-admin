<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-2">{{ $label }}</label>
    @foreach($options as $option => $label_opt)
    <label class="flex items-center gap-2 mb-2 cursor-pointer">
        <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="rounded border-gray-300 text-primary-500" {{ in_array($option, (array)old($column, $value ?? [])) ?'checked':'' }} {!! $attributes !!} />
        <span class="text-sm text-gray-700">{{ $label_opt }}</span>
    </label>
    @endforeach
    @include('admin::actions.form.help-block')
</div>
