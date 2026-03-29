

<div class="px-5 pt-4">
    <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $label }}</h4>
    <hr class="border-gray-200/50">
</div>

<div id="embed-{{$column}}" class="embed-{{$column}}">
    <div class="embed-{{$column}}-forms">
        <div class="embed-{{$column}}-form fields-group">
            @foreach($form->fields() as $field)
                {!! $field->render() !!}
            @endforeach
        </div>
    </div>
</div>

<hr class="border-gray-200/50 mx-5">
