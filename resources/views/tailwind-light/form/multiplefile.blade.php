@include("admin::form._header")

        <div class="flex gap-2">
                <input type="file" class="glass-input {{$class}}" name="{{$name}}[]" {!! $attributes !!} />
                @isset($btn){!! $btn !!}@endisset
        </div>
        @isset($sortable)
        <input type="hidden" class="{{$class}}_sort" name="{{ $sort_flag."[$name]" }}"/>
        @endisset

@include("admin::form._footer")
