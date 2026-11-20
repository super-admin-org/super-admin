@include("admin::form._header")

        <div class="flex gap-3 flex-wrap">
            <div class="flex flex-1 min-w-36">
                <span class="inline-flex items-center px-3 text-sm text-gray-400 bg-gray-50/50 border border-r-0 border-gray-200/60 rounded-l-lg"><i class="icon-clock"></i></span>
                <input type="text" name="{{$name['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="glass-input rounded-l-none {{$class['start']}}" autocomplete="off" {!! $attributes !!} />
            </div>

            <div class="flex flex-1 min-w-36">
                <span class="inline-flex items-center px-3 text-sm text-gray-400 bg-gray-50/50 border border-r-0 border-gray-200/60 rounded-l-lg"><i class="icon-clock"></i></span>
                <input type="text" name="{{$name['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="glass-input rounded-l-none {{$class['end']}}" autocomplete="off" {!! $attributes !!} />
            </div>
        </div>
@include("admin::form._footer")
