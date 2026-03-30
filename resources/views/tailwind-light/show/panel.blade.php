<div class="glass-card">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200/30">
        <h3 class="text-sm font-semibold text-gray-700">{{ $title }}</h3>
        <div class="flex items-center gap-2">
            {!! $tools !!}
        </div>
    </div>

    <div class="divide-y divide-gray-100/50">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>
</div>
