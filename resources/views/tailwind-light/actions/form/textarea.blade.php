<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
    <textarea {!! $attributes !!} class="glass-input"></textarea>
    @include('admin::actions.form.help-block')
</div>
