<div class="flex flex-wrap">
    @foreach($fields as $field)
    <div style="flex: 0 0 {{ ($field['width']/12 * 100) }}%; max-width: {{ ($field['width']/12 * 100) }}%;">
        {!! $field['element']->render() !!}
    </div>
    @endforeach
</div>
