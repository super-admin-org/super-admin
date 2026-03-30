<div class="glass-card show-panel">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200/30">
        <h3 class="text-base font-semibold text-gray-700">{{ $title }}</h3>
        <div class="flex items-center gap-2">
            {!! $tools !!}
        </div>
    </div>

    <div class="show-fields">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>
</div>
