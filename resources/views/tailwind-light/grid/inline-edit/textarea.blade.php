@extends('admin::grid.inline-edit.comm')
@php
    $type = "textarea";
@endphp

@section('field')
    <textarea class="glass-input ie-input" rows="{{ $rows }}">{{$value}}</textarea>
@endsection

@section('assert')
    <script>
       admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
            }
        }
    </script>

    <script>
    @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))
        $popover.data('display').html(val);
    @endcomponent
    </script>
@endsection

