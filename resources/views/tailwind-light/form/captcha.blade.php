@include("admin::form._header")

        <div class="flex gap-2 items-center" style="width: 280px;">

            <input {!! $attributes !!} class="glass-input flex-1" />

            <img id="{{$column}}-captcha" src="{{ captcha_src() }}" class="h-9 rounded cursor-pointer border border-gray-200/60" title="Click to refresh" onclick="document.getElementById('{{$column}}-captcha').src = '{{ captcha_src() }}?'+Math.random()"/>

        </div>

@include("admin::form._footer")
