<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
    <input type="file" {!! $attributes !!} class="glass-input">
    @include('admin::actions.form.help-block')
</div>
