<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
    <div class="flex">
        <span class="inline-flex items-center px-3 text-sm text-gray-400 bg-gray-50/50 border border-r-0 border-gray-200/60 rounded-l-lg"><i class="icon-calendar"></i></span>
        <input {!! $attributes !!} class="glass-input rounded-l-none">
    </div>
    @include('admin::actions.form.help-block')
</div>
