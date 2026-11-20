@extends('admin::grid.inline-edit.comm')

@section('field')
    @foreach($options as $option => $label)
        <label class="flex items-center gap-2 py-1 cursor-pointer">
            <input type="radio" name='radio-{{ $name }}' class="border-gray-300 text-primary-500 focus:ring-primary-400 ie-input" value="{{ $option }}" data-label="{{ $label }}"/>
            <span class="text-sm text-gray-700">{{$label}}</span>
        </label>
    @endforeach
@endsection

@section('assert')
    <style>
        .ie-content-{{ $name }} .ie-container {
            width: 180px;
            position: relative;
        }
    </style>

    <script>
     admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
                let fields = content.querySelectorAll('input');
                fields.forEach(el=>{
                    if (trigger.dataset.value == el.value){
                        el.checked = true;
                    }
                })
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
                let field = content.querySelector('input:checked');
                return  {'val':field.value,'label':field.dataset.label}
            }
        }

    </script>

@endsection

