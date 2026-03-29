@if(is_array($errorKey))

    @foreach($errorKey as $key => $col)
        @if($errors->has($col.$key))
        <div class="glass-alert glass-alert-danger mb-2">
            <ul class="m-0 ps-4 list-disc">
            @foreach($errors->get($col.$key) as $message)
                <li class="text-sm"> {{$message}}</li>
            @endforeach
            </ul>
        </div>
        @endif
    @endforeach

@else

    @if($errors->has($errorKey))
        <div class="glass-alert glass-alert-danger mb-2">
            <ul class="m-0 ps-4 list-disc">
            @foreach($errors->get($errorKey) as $message)
                <li class="text-sm"> {{$message}}</li>
            @endforeach
            </ul>
        </div>
    @endif

@endif
