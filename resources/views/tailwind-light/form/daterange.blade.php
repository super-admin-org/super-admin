<div class="{{$viewClass['form-group']}} flex flex-wrap items-start px-5 py-3 {!! ($errors->has($errorKey['start'].'start') || $errors->has($errorKey['end'].'end')) ? 'bg-red-50/30' : '' !!}">

    <label for="{{$id['start']}}" class="{{$viewClass['label']}} text-sm font-medium text-gray-600 pt-2 shrink-0">{{$label}}</label>

    <div class="{{$viewClass['field']}} flex-1 min-w-0">

        @include('admin::form.error')

        <div class="flex gap-3 flex-wrap">
            <div class="flex flex-1 min-w-36">
                <span class="inline-flex items-center px-3 text-sm text-gray-400 bg-gray-50/50 border border-r-0 border-gray-200/60 rounded-l-lg"><i class="icon-calendar"></i></span>
                <input type="text" name="{{$name['start']}}" id="{{$id['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="glass-input rounded-l-none {{$class['start']}}" autocomplete="off" {!! $attributes !!} />
            </div>

            <div class="flex flex-1 min-w-36">
                <span class="inline-flex items-center px-3 text-sm text-gray-400 bg-gray-50/50 border border-r-0 border-gray-200/60 rounded-l-lg"><i class="icon-calendar"></i></span>
                <input type="text" name="{{$name['end']}}" id="{{$id['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="glass-input rounded-l-none {{$class['end']}}" autocomplete="off" {!! $attributes !!} />
            </div>
        </div>

@include("admin::form._footer")
