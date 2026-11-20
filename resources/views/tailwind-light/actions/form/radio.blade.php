<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-2">{{ $label }}</label>
    @foreach($options as $option => $label_opt)
    <label class="flex items-center gap-2 mb-2 cursor-pointer">
        <input type="radio" name="{{$name}}" value="{{$option}}" class="border-gray-300 text-primary-500" {{ ($option == old($column, $value)) ?'checked':'' }} {!! $attributes !!} />
        <span class="text-sm text-gray-700">{{ $label_opt }}</span>
    </label>
    @endforeach
    @include('admin::actions.form.help-block')
</div>
