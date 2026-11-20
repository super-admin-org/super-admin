@include("admin::form._header")

        <div class="flex">

            @if ($prepend)
            <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50/50 border border-r-0 border-gray-200/60 rounded-l-lg">{!! $prepend !!}</span>
            @endif

            <input {!! $attributes !!} class="{{ $prepend ? 'rounded-l-none' : '' }} {{ $append || isset($btn) ? 'rounded-r-none' : '' }}" />

            @if ($append)
                <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50/50 border border-l-0 border-gray-200/60 rounded-r-lg">{!! $append !!}</span>
            @endif

            @isset($btn)
                <span class="inline-flex">
                  {!! $btn !!}
                </span>
            @endisset

        </div>

@include("admin::form._footer")
